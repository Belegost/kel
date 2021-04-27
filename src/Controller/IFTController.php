<?php

namespace App\Controller;

use App\Entity\Integrity\Account;
use App\Form\Data\SettingsData;
use App\Form\Data\SignUpData;
use App\Form\Data\UploadFileData;
use App\Form\Google2FAType;
use App\Form\ResetPasswordType;
use App\Form\SignInType;
use App\Form\UploadFileType;
use App\Lib\DBClient;
use App\Lib\XMLHttpRequest;
use App\Model\Google2FASettings;
use App\Service\Alerts;
use App\Service\Auth;
use App\Service\BitGoWallet;
use App\Service\Messenger\MRM as MRMMessenger;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\ServiceAwareTrait;
use App\Traits\SiteControllerAwareTrait;

/**
 * @author: igor.popravka
 * Date: 28.02.2018
 * Time: 15:04
 */
class IFTController extends AbstractController {
    use ServiceAwareTrait;
    use SiteControllerAwareTrait;

    /**
     * @var Auth
     */
    protected $auth;
    /**
     * @var Alerts
     */
    protected $alerts;

    protected $bitGoWallet;

    /** @var DBClient */
    protected $dbClient;

    /** @var MRMMessenger */
    protected MRMMessenger $messenger;

    private $env = [];

    private $render = [];

    public function __construct(
        Auth $auth,
        Alerts $alerts,
        BitGoWallet $bitGoWallet,
        LoggerInterface $logger,
        DBClient $dbClient,
        MRMMessenger $messenger
    ) {
        $this->auth = $auth;
        $this->alerts = $alerts;
        $this->bitGoWallet = $bitGoWallet;
        $this->dbClient = $dbClient;
        $this->setLogger($logger);
        $this->messenger = $messenger;

    }

    public function addEnvVar(string $name, $val): self {
        $this->env[$name] = $val;
        return $this;
    }

    public function addRenderVar(string $name, $val): self {
        $this->render[$name] = $val;
        return $this;
    }

    /**
     * @return Auth
     */
    public function getAuth() {
        return $this->auth;
    }

    /**
     * @return Alerts
     */
    protected function getAlerts(): Alerts {
        return $this->alerts;
    }

    /**
     * @return BitGoWallet
     */
    protected function getBitGoWallet(): BitGoWallet {
        return $this->bitGoWallet;
    }

    protected function getDbClient(): DBClient
    {
        return $this->dbClient;
    }

    protected function addAlert($type, $message, array $context = []) {
        $this->getAlerts()->add($type, $message, $context);
    }

    protected function makeRenderData(string $router, array $bind = []) {
        $data = [];

        if (!$this->getAuth()->isLogged()) {
            $form_signin = $this->createForm(SignInType::class);
            $data['form_signin'] = $form_signin->createView();

            $form_reset_password = $this->createForm(ResetPasswordType::class);
            $data['form_reset_password'] = $form_reset_password->createView();
            $data['avatar_url'] = $this->generateAvatarUrl();

            if($this->getAuth()->isGoogle2FAStarted()) {
                $google2FAForm = $this->createForm(Google2FAType::class);
                $data['google2FAForm'] = $google2FAForm->createView();
                $data['google2FASettings'] = $this->get('app.service.model')->factory(
                    Google2FASettings::class,
                    ['accountId' => $this->getAuth()->getAccount()->getId()]
                );
            }
        } else {
            $accessRoutes = [
                'route_user_dashboard',
                'route_user_settings',
                'route_deposit',
                'route_myproducts',
                'route_portfolio',
                'route_withdrawal',
                'route_documents',
                'route_convert',
            ];

            if (in_array($router, $accessRoutes)) {
                $data = array_merge(
                    $data,
                    ['totalInvested' => $this->getAuth()->getBinanceSubAccount()->getTotalInvested()]
                );

                $data['totalBalance'] = round($this->getAuth()->getBinanceSubAccount()->getCurrentBalance(true), 2);
                $data['currentBalance'] = $this->getAuth()->getBinanceSubAccount()->getCurrentBalance();
                $data['nonInvestedFunds'] = $this->getAuth()->getBinanceSubAccount()->getNonInvested();
                $data['totalInvestedFunds'] = $this->getAuth()->getBinanceSubAccount()->getInvestedFunds();

                $dailyChange = $this->getAuth()->getBinanceSubAccount()->getEquityDayChange(2);
                if (!empty($dailyChange)) {
                    $data['dailyGainEquity'] = $dailyChange[0] - $dailyChange[1];
                    $data['dailyGainDirect'] = $data['dailyGainEquity'] < 0 ? 'down' : 'up';
                }

                $this->addEnvVar('isCurrentRateUSD', true);

            }
            $data['avatar_url'] = $this->generateAvatarUrl($this->getAuth()->getAccount()->getAvatar());
        }

        switch ($router) {
            case 'route_home':
                $data['head_title'] = 'Integrity | Trust Fund';
                $data['body_class'] = 'homepage';
                break;
            case 'route_analytics_all':
                $data['head_title'] = 'Analytics | All accounts';
                $data['body_class'] = 'analytics';
                break;
            case 'route_analytics_index':
                $data['head_title'] = 'Analytics | Index page';
                $data['body_class'] = 'analytics';
                break;
            case 'route_analytics_current':
                $data['head_title'] = 'Analytics | Current account';
                $data['body_class'] = 'analytics';
                break;
            case 'route_user_dashboard':
                $data['head_title'] = 'Dashboard | Integrity';
                $data['body_class'] = 'dashboard';
                break;
            case 'route_signup':
                $data['head_title'] = 'User registration | Integrity';
                break;
            case 'route_user_settings':
                $data['head_title'] = 'User settings | Integrity';
                break;
            case 'route_product_list':
                $data['head_title'] = 'Integrity | Products';
                $data['body_class'] = 'productlist';
                break;
            case 'route_product_item':
                $data['head_title'] = 'Integrity | Products';
                $data['body_class'] = 'productpage';
                break;
            case 'route_aboutus':
                $data['head_title'] = 'Integrity | Trust Fund';
                $data['body_class'] = 'aboutus';
                $data['header_class'] = 'header-white';
                break;
            case 'route_contacts':
                $data['head_title'] = 'Integrity | Trust Fund';
                $data['body_class'] = 'contactus';
                $data['header_class'] = 'header-white';
                break;
            case 'route_password_change':
                $data['head_title'] = 'Reset Password | Integrity';
                break;
            case 'route_faq':
                $data['head_title'] = 'FAQ | Integrity';
                $data['body_class'] = 'faq';
                break;
            case 'route_deposit':
                $data['head_title'] = 'Make deposit | Integrity';
                $data['body_class'] = 'deposit-cf';
                break;
            case 'route_crypto':
                $data['head_title'] = 'Crypto Trust | Integrity';
                $data['body_class'] = 'crypto';
                break;
            case 'route_cryptofeed':
                $data['head_title'] = 'Crypto Feed | Integrity';
                $data['body_class'] = 'newsfeed';
                break;
            case 'route_documents':
                $data['head_title'] = 'FAQ | Integrity';
                $data['body_class'] = 'documents';
                break;
            case 'route_myproducts':
                $data['head_title'] = 'My Products | Integrity';
                $data['body_class'] = 'dashboard';
                break;
            case 'route_portfolio':
                $data['head_title'] = 'Build portfolio | Integrity';
                $data['body_class'] = 'dashboard';
                break;
            case 'route_full_pricing':
                $data['head_title'] = 'Integrity | Full Pricing Tables';
                $data['body_class'] = 'full-pricing';
                $data['header_class'] = 'header-white';
                break;
            case 'route_pricing':
                $data['head_title'] = 'Integrity | Pricing Tables';
                $data['body_class'] = 'pricing';
                break;
            case 'route_rates':
                $data['head_title'] = 'Integrity | Rates';
                $data['body_class'] = 'rates';
                break;
            case 'route_product_item_classic':
                $data['head_title'] = 'Classic Trust | Integrity';
                $data['body_class'] = 'productpage';
                break;
            case 'route_product_item_confident':
                $data['head_title'] = 'Confident Trust | Integrity';
                $data['body_class'] = 'productpage';
                break;
            case 'route_product_item_conservative':
                $data['head_title'] = 'Conservative Trust | Integrity';
                $data['body_class'] = 'productpage';
                break;
            case 'route_product_item_individual':
                $data['head_title'] = 'Individual Portfolio Managed | Integrity';
                $data['body_class'] = 'productpage';
                break;
            case 'route_withdrawal':
                $data['head_title'] = 'Withdrawal | Integrity';
                $data['body_class'] = 'withdrawal';
                break;
            case 'route_newsfeed':
                $data['head_title'] = 'News Feed | Integrity';
                $data['body_class'] = 'newsfeed';
                break;
            case 'route_integrityfeed':
                $data['head_title'] = 'Integrity News | Integrity';
                $data['body_class'] = 'newsfeed';
                break;
            case 'route_privacy':
                $data['head_title'] = 'Integrity | Trust Fund';
                $data['body_class'] = 'terms';
                $data['header_class'] = 'header-white';
                break;
        }

        return array_merge($data, $bind, $this->render, ['env' => $this->env]);
    }

    protected function getPublicDir($name = '') {
        $publicDir = $this->getParameter('public_dir');
        return isset($publicDir[$name]) ? $publicDir[$name] : $publicDir;
    }

    protected function generateAvatarUrl(string $file_name = null) {
        if (isset($file_name)) {
            if (file_exists($this->getPublicDir('avatar') . "/$file_name")) {
                return "upload/avatar/$file_name";
            } else if (file_exists($this->getParameter('kernel.project_dir') . "/public{$file_name}")) {
                return $file_name;
            }
        }
        return "images/dashboard-user-nophoto.png";
    }

    protected function getContacts($name = '') {
        $contacts = $this->getParameter('integrity_contacts');
        return isset($contacts[$name]) ? $contacts[$name] : $contacts;
    }

    protected function createJSONRError(string $message, array $data = [], $code = 0) {
        return new JsonResponse([
            'status' => 'error',
            'code' => $code,
            'message' => $message,
            'data' => $data
        ]);
    }

    protected function createJSONRBadRequest() {
        return new JsonResponse([
            'status' => 'error',
            'message' => "Can not process the request",
            'data' => []
        ], 400);
    }

    protected function createJSONRSuccess(string $message = "", array $data = []) {
        return new JsonResponse([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], 200);
    }

    protected function handleAvatarFile($data) {
        /**
         * @var SignUpData|SettingsData $data
         * @var Account|null $account
         */
        $account = $this->getAuth()->getAccount();

        if ($this->getAuth()->isLogged() && !empty($account->getAvatar()) && is_null($data->getAvatar())) {
            $photo = "{$this->getPublicDir('avatar')}/{$account->getAvatar()}";
            if (file_exists($photo)) {
                @unlink($photo);
            }
        } else if (($uploadFile = $data->getUploadFile()) instanceof UploadedFile) {
            /** @var UploadedFile $file */
            $fileName = sprintf("%s.%s", md5(uniqid()), $uploadFile->guessExtension());
            $filePath = "{$this->getPublicDir('avatar')}/{$fileName}";

            @copy($uploadFile->getRealPath(), $filePath);
            @unlink($uploadFile->getRealPath());

            if (file_exists($filePath)) {
                $data->setAvatar($fileName);
            }
        }
    }

    /**
     * @Route("/upload-avatar", name="route_upload_avatar")
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function uploadAvatar(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $form = $this->createForm(UploadFileType::class);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var UploadFileData $uploadFileData */
                $uploadFileData = $form->getData();

                if ($uploadFileData->getName()) {
                    @unlink("{$this->getPublicDir('avatar')}/{$uploadFileData->getName()}");
                }

                if (($uploadFile = $uploadFileData->getData()) instanceof UploadedFile) {
                    /** @var UploadedFile $file */
                    $fileName = sprintf("%s.%s", md5(uniqid()), $uploadFile->guessExtension());
                    $filePath = "{$this->getPublicDir('avatar')}/{$fileName}";

                    @copy($uploadFile->getRealPath(), $filePath);
                    @unlink($uploadFile->getRealPath());

                    return $this->createJSONRSuccess('Avatar file was uploaded successfully', [
                        'fileName' => $fileName,
                        'fileUrl' => $this->generateAvatarUrl($fileName)
                    ]);
                }
            }

            return $this->createJSONRError('Invalid Avatar Field', [
                'avatarErrors' => $this->formErrors2Array($form)
            ]);
        }
        return $this->createJSONRBadRequest();
    }

    protected function formErrors2Array(FormInterface $form) {
        $errors = [];
        foreach ($this->generateFormErrors($form) as $message) {
            $errors[] = $message;
        }
        return $errors;
    }

    private function generateFormErrors(FormInterface $form) {
        foreach ($form->getErrors() as $error) {
            if ($message = trim($error->getMessage())) {
                yield $message;
            }
        }
    }

    protected function createXMLHttpRequest(string $requestType): XMLHttpRequest {
        if (!class_exists($requestType)) {
            throw new \InvalidArgumentException("Class {$requestType} does not exist");
        }

        if (!(($requestTypeObject = new $requestType()) instanceof XMLHttpRequest)) {
            throw new \InvalidArgumentException("Class {$requestType} should implement App\\Lib\\XMLHttpRequest");
        }

        return $requestTypeObject;
    }
}
