<?php

namespace App\Service;

use App\Entity\Integrity\Account;
use App\Entity\Integrity\AccountState;
use App\Exception as AppException;
use App\Entity\Zoho\Contact as ZohoContact;
use App\Form\Data\SettingsData;
use App\Form\Data\SignInData;
use App\Form\Data\SignUpData;
use App\Lib\DBClient;
use App\Lib\JSONHelper;
use App\Model\Binance\SubAccount AS BinanceSubAccount;
use App\Model\Google2FASettings;
use App\Repository\AccountRepository;
use App\Service\CRM\IntegrityZohoClient;
use Carbon\Carbon;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @author: igor.popravka
 * Date: 06.02.2018
 * Time: 16:34
 */
class Auth {
    const ACTIVITY_DOWNTIME = '+20 min';

    /**
     * @var Account
     */
    private $account;

    private ?int $accountId = null;

    private ContainerInterface $container;

    private ?BinanceSubAccount $binanceSubAccount = null;

    /**
     * @var ZohoContact
     */
    private $zohoContact;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var DBClient
     */
    private $dbClient;

    /**
     * @var PasswordEncoder
     */
    private $encoder;

    /**
     * @var IntegrityZohoClient
     */
    private $zoho;

    /**
     * @var int
     */
    //todo: use Account::keep_signed_key
    private $keep_signed;

    /** @var \DateTime */
    //todo: use Account::last_activity
    private $last_activity;

    private $view_mode;

    private $version;

    /**
     * If TRUE, login will try to use Google 2FA for this.
     *
     * @var bool
     */
    private bool $google2FAStarted = false;

    public function __construct(SessionInterface $session, DBClient $dbClient, PasswordEncoder $encoder,
                                IntegrityZohoClient $zoho, JSONHelper $json, ContainerInterface $container) {
        $this->session = $session;
        $this->dbClient = $dbClient;
        $this->encoder = $encoder;
        $this->zoho = $zoho;
        $this->container = $container;
        $this->view_mode = false;

        $data = $json->parseFile('composer.json');
        $this->version = $data["version"] ?? null;

        $this->loadSession();
    }

    /**
     * Checks if Google 2FA is using for login
     *
     * @return bool
     */
    public function isGoogle2FAStarted(): bool
    {
        return boolval($this->google2FAStarted);
    }

    /**
     * Begin the Google 2FA
     */
    public function beginGoogle2FA(): void
    {
        $this->google2FAStarted = true;
        $this->updateSession();
    }

    /**
     * Finish the Google 2FA
     */
    public function finishGoogle2FA(): void
    {
        $this->google2FAStarted = false;
        $this->updateSession();
    }

    public function getBinanceSubAccount()
    {
        if ( ! $this->isLogged() ) {
            return null;
        }
        if ( is_null($this->binanceSubAccount) ) {
            $this->binanceSubAccount = $this->container->get('app.model.binance.sub_account');
            if ( ($subAccountId = $this->getAccount()->getBinanceSubAccountId()) !== null ) {
                $this->binanceSubAccount->setSubAccountId($subAccountId);
            }

            if ( ($apiKey = $this->getAccount()->getBinanceApiKey()) !== null ) {
                $this->binanceSubAccount->setApiKey($apiKey);
            }

            if ( ($apiSecret = $this->getAccount()->getBinanceApiSecret()) !== null ) {
                $this->binanceSubAccount->setApiSecret($apiSecret);
            }
        }

        return $this->binanceSubAccount;
    }

    /**
     * @return SessionInterface
     */
    protected function getSession(): SessionInterface {
        return $this->session;
    }

    /**
     * @return ManagerRegistry
     */
    //todo: use DBClient class
    protected function getDoctrine(): ManagerRegistry {
        return $this->doctrine;
    }

    protected function getDbClient(): DBClient
    {
        return $this->dbClient;
    }

    /**
     * @return PasswordEncoder
     */
    protected function getEncoder(): PasswordEncoder {
        return $this->encoder;
    }

    /**
     * @return IntegrityZohoClient
     */
    public function getZoho(): IntegrityZohoClient {
        return $this->zoho;
    }

    /**
     * @return Account|null
     */
    public function getAccount(): ?Account
    {
        if ( ! $this->accountId ) {
            return null;
        }
        if ( is_null($this->account) ) {
            $this->account = $this->getDbClient()->getRepository('Integrity:Account')
                ->find($this->accountId);
        }
        return $this->account;
    }

    public function getAccountID() {
        return $this->isLogged() ? $this->accountId : null;
    }

    /**
     * @return ZohoContact
     */
    public function getZohoContact(): ?ZohoContact {
        if ($this->isLogged()) {
            $contact = $this->getZoho()->getContact($this->getAccount()->getZohoContactId(), []);
            return new ZohoContact($contact);
        }
        return null;
    }

    /**
     * @return int
     */
    public function getKeepSigned() {
        return $this->keep_signed;
    }

    /**
     * @param int $keep_signed
     */
    public function setKeepSigned($keep_signed) {
        $this->keep_signed = $keep_signed;
    }

    /**
     * @return \DateTime
     */
    public function getLastActivity() {
        return $this->last_activity;
    }

    /**
     * @param \DateTime $last_activity
     */
    public function setLastActivity($last_activity) {
        $this->last_activity = $last_activity;
    }

    public function isLogged() {
        if (($this->getAccount() instanceof Account) && !$this->isGoogle2FAStarted()) {
            return true;
        }

        return false;
    }

    protected function getSessionKey() {
        return md5(__CLASS__ . '654E654E6RTH4ER9TE9RTERTHER899');
    }

    protected function updateSession() {
        $this->getSession()->set($this->getSessionKey(), serialize([
            $this->accountId,
            $this->last_activity,
            $this->keep_signed,
            $this->view_mode,
            $this->google2FAStarted
        ]));
    }

    protected function loadSession() {
        try {
            $serialize_data = $this->getSession()->get($this->getSessionKey());
            if (isset($serialize_data)) {
                $session_data = unserialize($serialize_data);
                if (is_array($session_data)) {
                    list(
                        $this->accountId,
                        $this->last_activity,
                        $this->keep_signed,
                        $this->view_mode,
                        $this->google2FAStarted
                        ) = $session_data;

                    return true;
                }
            } else if (isset($_COOKIE['uks'])) {
                list ($pub_key, $token, $mac) = explode(':', $_COOKIE['uks']);
                $data = $pub_key . ':' . md5(self::CLIENT_IP() . ':' . self::USER_AGENT());

                if (!hash_equals(hash_hmac('sha256', $data, self::SECRET_KEY()), $mac)) {
                    return false;
                }

                $account = $this->findAccount($pub_key);
                if (($account instanceof Account) && hash_equals($account->getKeepSignedKey(), $token)) {
                    $this->accountId = $account->getId();
                    $this->account = $account;
                    $this->last_activity = $account->getLastActivity();
                    $this->keep_signed = true;
                    $this->view_mode = false;

                    $this->updateSession();
                    return true;
                }
            }
        } catch (\ErrorException $e) {
        }

        return false;
    }

    protected function destroySession() {
        $this->getSession()->invalidate();
        $this->unsetAuthData();
        setcookie('uks', null);
    }

    public function isDowntimeReached() {
        if (($last_activity = $this->getLastActivity()) instanceof \DateTime) {
            $now = new \DateTime();
            $last_activity->modify(self::ACTIVITY_DOWNTIME);

            if ($last_activity->getTimestamp() <= $now->getTimestamp() || $this->getKeepSigned()) {
                $this->setLastActivity($now);
                $this->updateSession();
                return true;
            }
        }

        $this->destroySession();
        return false;
    }

    protected function unsetAuthData() {
        unset($this->accountId);
        unset($this->account);
        unset($this->zohoContact);
        unset($this->last_activity);
        unset($this->keep_signed);
    }

    /**
     * @param SignUpData $signUpData
     * @return Account
     * @throws AppException
     * @throws \Exception
     */
    public function createAccount(SignUpData $signUpData) {
        $this->checkUniqueData($signUpData);

        $account = new Account();
        $signUpData->handleAccount($account);

        $unconfirmedState = $this->getDbClient()->getRepository(AccountState::class)
            ->find(AccountState::UNCONFIRMED);

        $account->setState($unconfirmedState);
        $account->setPublicKey(bin2hex(random_bytes(32)));
        $account->setPassword($this->getEncoder()->hashPassword($signUpData->getPassword()));

        try {
            $em = $this->getDbClient()->getEntityManager();
            $em->persist($account);
            $em->flush();
        } catch (\Throwable $t) {
            throw AppException::create($t->getMessage());
            throw AppException::create('Database error. Please try again later or contact us.');
        }

        return $account;
    }

    public function login(SignInData $signInData) {
        /** @var Account|null $account */
        if (($account = $this->findAccount($signInData->getUsername())) instanceof Account) {
            if ($this->getEncoder()->isValidPassword($signInData->getPassword(), $account->getPassword())) {
                $lastActivity = Carbon::now();
                $this->setLastActivity($lastActivity);
                $this->setKeepSigned($signInData->getKeepsigned());

                $account->setLastActivity($lastActivity);

                if ($signInData->getKeepsigned()) {
                    $token = md5(self::CLIENT_IP() . ':' . self::USER_AGENT());
                    $account->setKeepSignedKey($token);

                    $cookie = $account->getPublicKey() . ':' . $token;
                    $mac = hash_hmac('sha256', $cookie, self::SECRET_KEY());
                    $cookie .= ':' . $mac;
                    setcookie('uks', $cookie);
                }

                /**
                 * @var Google2FASettings $google2FASettings
                 */
                $google2FASettings = $this->container->get('app.service.model')->factory(
                    Google2FASettings::class,
                    ['accountId' => $account->getId()]
                );

                if ($google2FASettings->isGoogle2FAEnabled() && $this->container->getParameter('google.2fa.enabled')) {
                    $this->beginGoogle2FA();
                    $google2FASettings->setGoogle2FAEnabled(true);

                    if (!$google2FASettings->getGoogle2FASecretKey()) {
                        $google2FASettings
                            ->refreshGoogle2FASecretKey()
                            ->refreshGoogle2FARecoveryCodes()
                            ->refreshGoogle2FAQrUrl($account->getEmail())
                            ->setGoogle2FAShowQrUrl(true);
                    }
                }

                $this->saveAccountData($account);

                return true;
            }
        }

        return false;
    }

    public function loginByPublicKey(string $publicKey) {
        /** @var Account|null $account */
        if (($account = $this->findAccount($publicKey)) instanceof Account) {
            $this->view_mode = true;
            $this->accountId = $account->getId();
            $this->account = $account;

            $this->updateSession();

            return true;
        }


        return false;
    }

    public function logout() {
        $this->destroySession();
    }

    public function changePassword(string $password) {
        /** @var Account|null $account */
        if (($account = $this->findAccount()) instanceof Account) {
            $password = $this->getEncoder()->hashPassword($password);
            $account->setPassword($password);

            $this->saveAccountData($account);

            return true;
        }
        return false;
    }

    public function changePasswordAPI(string $publicKey, string $password) {
        /** @var Account|null $account */
        if (($account = $this->findAccount($publicKey)) instanceof Account) {
            $password = $this->getEncoder()->hashPassword($password);
            $account->setPassword($password);

            $this->saveAccountData($account);

            return true;
        }
        return false;
    }

    public function isAccountExist($username) {
        return $this->findAccount($username);
    }

    public function isValidPassword(string $password) {
        if ($this->getAccount() instanceof Account) {
            return $this->getEncoder()->isValidPassword($password, $this->getAccount()->getPassword());
        }
        return false;
    }

    /**
     * @param SignUpData $signUpData
     * @throws AppException
     */
    protected function checkUniqueData(SignUpData $signUpData) {
        /** @var AccountRepository $repository */
        $repository = $this->getDbClient()->getRepository(Account::class);
        /** @var Account|null $account */
        if ($repository->findOneBy(['username' => $signUpData->getUsername()]) instanceof Account) {
            throw AppException::create('Username already taken. Please select other username.');
        }

        if ($repository->findOneBy(['email' => $signUpData->getEmail()]) instanceof Account) {
            throw AppException::create('The same email already used. You can restore your account if you forgot your password.');
        }

        if ($repository->findOneBy(['phone_number' => $signUpData->getPhone()]) instanceof Account) {
            throw AppException::create('The same phone number already taken. Please check value and try again.');
        }
    }

    /**
     * @param $username
     * @return Account|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    protected function findAccount($username = null): ?Account {
        if (!isset($username) && $this->isLogged()) {
            $username = $this->getAccount()->getUsername();
        }

        if ($username) {
            /** @var AccountRepository $repository */
            $repository = $this->getDbClient()->getRepository(Account::class);
            return $repository->findOneByUniqueField($username);
        }
        return null;
    }

    public function updateSettings(SettingsData $settingsData) {
        if (($contact = $this->getZohoContact()) instanceof ZohoContact) {
            $settingsData->handleZohoContact($contact);

            if ($this->getZoho()->updateRecordContact($contact)) {
                $account = $this->findAccount();
                $account->setFirstName($contact->getFirstName());
                $account->setLastName($contact->getLastName());
                $account->setEmail($contact->getEmail());
                $account->setAvatar($contact->getAvatar());

                $this->saveAccountData($account);

                return true;
            }
        }

        return false;
    }

    public function validatePublicKey(string $publicKey) {
        return $this->getAccount()->getPublicKey() == $publicKey;
    }

    /**
     * @return bool
     */
    public function isViewMode(): bool {
        return $this->view_mode;
    }

    public function switchUSDRates(bool $value) {
        /** @var Account $account */
        $account = $this->findAccount();
        $account->setRatesInUsd($value);

        $this->saveAccountData($account);
    }

    private function saveAccountData(Account $account): void
    {
        $this->getDbClient()->getEntityManager()->persist($account);
        $this->getDbClient()->getEntityManager()->flush();

        $this->account = $account;
        $this->accountId = $account->getId();
        $this->updateSession();
    }

    public function saveBinanceSubAccountId(string $binanceSubAccountId): void
    {
        $account = $this->findAccount();

        $account->setBinanceSubAccountId($binanceSubAccountId);

        $this->saveAccountData($account);
    }

    public function saveBinanceSubAccountAPIData(string $apiKey, string $apiSecret): void
    {
        $account = $this->findAccount();

        $account->setBinanceApiKey($apiKey);
        $account->setBinanceApiSecret($apiSecret);

        $this->saveAccountData($account);
    }

    public function saveRequiredProfileFields(string $firstname, string $lastname): void
    {
        $account = $this->findAccount();

        $account->setFirstName($firstname);
        $account->setLastName($lastname);

        $this->saveAccountData($account);
    }


    public function isRateUSD() {
        return $this->isLogged() && $this->getAccount()->isRatesInUsd();
    }

    // Function to get the client ip address
    public static function CLIENT_IP() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';

        return $ipaddress;
    }

    public static function USER_AGENT() {
        return getenv('HTTP_USER_AGENT');
    }

    public static function SECRET_KEY() {
        return getenv('APP_SECRET');
    }

    public function getVersion() {
        return $this->version;
    }
}
