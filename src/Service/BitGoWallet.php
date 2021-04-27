<?php
/**
 * Created by PhpStorm.
 * User: Grand
 * Date: 14.03.2018
 * Time: 17:50
 */

namespace App\Service;

use App\Entity\Integrity\Wallet;
use App\Lib\DBClient;
use BaconQrCode\Renderer\Image\Png;
use BaconQrCode\Writer;
use Symfony\Component\HttpKernel\KernelInterface;
use App\ServiceAwareTrait;

class BitGoWallet {
    use ServiceAwareTrait;

    /** @var string */
    private $id;
    /** @var string */
    private $coin;
    /** @var string */
    private $account_id;
    /** @var string */
    private $qrcode_file_name;
    /** @var string */
    private $qrcode_file_path;
    /** @var string */
    private $qrcode_file_uri;
    /** @var BitGoClient */
    private $BitGoClient;
    /** @var DBClient */
    private $DBClient;
    /** @var MRMClient */
    private $MRMClient;

    public function __construct(Auth $auth, DBClient $DBClient, MRMClient $MRMClient, KernelInterface $kernel) {
        $this->account_id = $auth->getAccount() ? $auth->getAccount()->getZohoContactId() : null;

        $this->qrcode_file_name = sprintf('%s_qr.png', $this->account_id);
        $this->qrcode_file_path = sprintf('%s/public/qr/%s', $kernel->getProjectDir(), $this->qrcode_file_name);
        $this->qrcode_file_uri = sprintf('/qr/%s', $this->qrcode_file_name);

        $this->id = getenv('BITGO_WALLET_ID');
        $this->coin = getenv('BITGO_WALLET_COIN');

        $this->BitGoClient = new BitGoClient([
            'API_ENDPOINT' => getenv('BITGO_API_ENDPOINT'),
            'API_BLOCKCHAIN' => getenv('BITGO_API_BLOCKCHAIN'),
            'ACCESS_TOKEN' => getenv('BITGO_ACCESS_TOKEN')
        ]);

        $this->DBClient = $DBClient;
        $this->MRMClient = $MRMClient;
    }

    /**
     * @return string
     */
    public function getId(): string {
        return $this->id ?? '';
    }

    /**
     * @return string
     */
    public function getCoin(): string {
        return $this->coin;
    }

    /**
     * @return string
     */
    protected function getAccountId(): string {
        return $this->account_id ?? '';
    }

    /**
     * @return string
     */
    public function getQrcodeFileName(): string {
        return $this->qrcode_file_name ?? '';
    }

    /**
     * @return string
     */
    public function getQrcodeFilePath(): string {
        return $this->qrcode_file_path ?? '';
    }

    /**
     * @return string
     */
    public function getQrcodeFileUri(): string {
        return $this->qrcode_file_uri ?? '';
    }

    /**
     * @return BitGoClient
     */
    protected function getBitGoClient(): BitGoClient {
        return $this->BitGoClient;
    }

    /**
     * @return DBClient
     */
    protected function getDBClient(): DBClient {
        return $this->DBClient;
    }

    /**
     * @return MRMClient
     */
    protected function getMRMClient(): MRMClient {
        return $this->MRMClient;
    }

    public function getFullInfo(string $walletId): array {
        foreach ($this->getWalletsList() as $w) {
            if (!isset($w['error']) && $walletId === $w['id']) {
                return $w;
            }
        }
        return [];
    }

    /**
     * @return Wallet|null
     */
    public function &getClientWallet(): ?Wallet {
        $wallet = $this->getDBClient()
            ->getWalletRepository()
            ->findDepositWallet($this->getAccountId(), $this->getId());

        if (!($wallet instanceof Wallet)) {
            $info = $this->getFullInfo($this->getId());

            $wallet = (new Wallet())
                ->setAccountId($this->getAccountId())
                ->setName($info['label'])
                ->setType(Wallet::TYPE_CREDIT_WALLET)
                ->setStatus(Wallet::STATUS_ACTIVE)
                ->setAddress($this->getId());

            $this->getDBClient()->flushEntityObject($wallet);
        }

        return $wallet;
    }

    public function generateDepositAddress(): ?string {
        if (($wallet = $this->getClientWallet()) instanceof Wallet) {
            $address = $this->getBitGoClient()->createAddress($this->getCoin(), $this->getId());

            if (isset($address)) {
                $wallet->addOperation([
                    'creationTS' => (new \DateTime())->getTimestamp(),
                    'depositAddress' => $address,
                    'operationId' => null
                ]);
                $this->getDBClient()->flushEntityObject($wallet);

                $this->generateQRCodeFile($address);

                return $address;
            }
        }
        return null;
    }

    public function generateQRCodeFile(string $address): BitGoWallet {
        @unlink($this->getQRCodeFilePath());

        $png = new Png();
        $png->setHeight(124);
        $png->setWidth(124);
        $png->setMargin(0);

        $writer = new Writer($png);
        $writer->writeFile($address, $this->getQRCodeFilePath());
        return $this;
    }

    public function refreshDepositOperations(): BitGoWallet {
        if (($wallet = $this->getClientWallet()) instanceof Wallet) {
            $operations = $wallet->getOperations();

            foreach ($operations as &$operation) {
                if (!isset($operation['operationId'])) {
                    $result = $this->getBitGoClient()->getAddressDetails($operation['depositAddress']);

                    if (!empty($result['confirmedBalance'])) {
                        $balance = floatval($result['confirmedBalance']) / 100000000;

                        try {
                            $response = $this->getMRMClient()->addDeposit(
                                $this->getAccountId(),
                                $balance,
                                $operation['creationTS'],
                                $operation['depositAddress']
                            );

                            if (isset($response['status']) && $response['status'] == 'success') {
                                $operation['operationId'] = intval($response['data']['operationId']);
                            }
                        } catch (\Throwable $t) {
                            //todo: write error to log
                            continue;
                        }
                    }
                }
            }

            $this->getDBClient()->getWalletRepository()->updateOperations($wallet->getId(), $operations);
        }

        return $this;
    }

    public function getMoneyMovementInfo(string $currency = 'BTC'): array {
        try {
            $result = $this->getMRMClient()->getMoneyMovementInfo($this->getAccountId(), $currency);
            if (isset($result['status']) && $result['status'] == 'success') {
                return $result['data'];
            } else {
                $message = "Get money movement error: [{$result['code']}] {$result['message']}";
                $this->getLogger()->error($message, $result['data']);
            }
        } catch (\Throwable $t) {
            $message = "Get money movement error: [{$t->getCode()}] {$t->getMessage()}";
            $this->getLogger()->error($message, $t->getTrace());
        }
        return [];
    }

    public function getMoneyMovementList(): array {
        try {
            $result = $this->getMRMClient()->getMoneyMovementList($this->getAccountId());

            if (!empty($result)) {
                if (isset($result['status']) && $result['status'] == 'success') {
                    return $result['data'];
                } else {
                    $message = "Get money movements list error: [{$result['code']}] {$result['message']}";
                    $this->getLogger()->error($message, $result['data']);
                }
            } else {
                $this->getLogger()->warning("Failed to get money movements list.");
            }
        } catch (\Throwable $t) {
            $message = "Get money movement error: [{$t->getCode()}] {$t->getMessage()}";
            $this->getLogger()->error($message, $t->getTrace());
        }
        return [];
    }

    public function getWalletsList(): array {
        try {
            $result = $this->getBitGoClient()->listWallets($this->getCoin());

            if (isset($result['wallets'])) {
                return $result['wallets'];
            } else if (isset($result['error'])) {
                $message = "<{$result['status']}> {$result['error']}";
                throw new \HttpRequestException($message);
            }
        } catch (\Throwable $t) {
            $message = "Failed get BitGo wallets list: [{$t->getCode()}] {$t->getMessage()}.";
            $this->getLogger()->error($message, $t->getTrace());
        }

        return [];
    }
}