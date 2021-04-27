<?php
/**
 * Created by PhpStorm.
 * User: Grand
 * Date: 19.05.2018
 * Time: 01:00
 */

namespace App\Service;

use App\Lib\APIFoundationBuilder;
use Psr\Log\LoggerInterface;

class MRMClient extends APIFoundationBuilder {
    private $mrm_host;
    private $mrm_token;
    private $app_code;

    public function __construct(LoggerInterface $logger) {
        $this->mrm_host = $_ENV['MRM_HOST'];
        $this->mrm_token = $_ENV['MRM_TOKEN'];
        $this->app_code = 'integrity';

        parent::__construct($logger);
    }

    protected function getHost(): ?string {
        return $this->mrm_host;
    }

    public function createRevision(string $clientId, string $documentLink, string $documentType) {
//        var_dump($clientId);
//        var_dump($documentLink);
//        var_dump($documentType);
//        die();
//        return $this->post('revision/create', [
//            'document_link' => $documentLink,
//            'document_type' => $documentType,
//            'client_id' => $clientId,
//            'mrm_token' => $this->mrm_token,
//            'app_code' => $this->app_code
//        ]);
    }


    /**
     * @param string $clientId
     *
     * @return array
     * - status
     * - data:
     * -- actual_balance
     * -- invested_funds
     */
    public function getMoneyMovementList(string $clientId) {
        return $this->post('client-money-movements/get-list', [
            'client_id' => $clientId,
            'mrm_token' => $this->mrm_token,
            'app_code' => $this->app_code
        ]);
    }

    /**
     * @param int $accountId
     *
     * @return array
     */
    public function loadClientProductList(int $accountId):array
    {
        return $this->post('client/products/get-list', [
            'account_id' => $accountId,
            'mrm_token' => $this->mrm_token,
            'app_code' => $this->app_code
        ]);
    }

    public function loadProductsList(): array
    {
        return $this->post('products/get-list', [
            'mrm_token' => $this->mrm_token,
            'app_code' => $this->app_code
        ]);
    }

    public function loadProductPeriodsList(): array
    {
        return $this->post('product/periods/get-list', [
            'mrm_token' => $this->mrm_token,
            'app_code' => $this->app_code
        ]);
    }

    public function loadProductTypesList(): array
    {
        return $this->post('product/types/get-list', [
            'mrm_token' => $this->mrm_token,
            'app_code' => $this->app_code
        ]);
    }

    public function binanceCreateSubAccount(): array
    {
        return $this->post('binance/create-subaccount', [
            'mrm_token' => $this->mrm_token,
            'app_code' => $this->app_code,
        ]);
    }

    public function binanceGetDepositAddress(string $apiKey, string $apiSecret): array
    {
        return $this->post('binance/get-deposit-address', [
            'api_key' => $apiKey,
            'api_secret' => $apiSecret,
            'mrm_token' => $this->mrm_token,
            'app_code' => $this->app_code,
        ]);
    }

    public function binanceGetDepositHistory(string $subAccountId): array
    {
        return $this->post('binance/get-deposit-history', [
            'sub_account_id' => $subAccountId,
            'mrm_token' => $this->mrm_token,
            'app_code' => $this->app_code,
        ]);
    }

    public function binanceGetAccountInformation(string $apiKey, string $apiSecret): array
    {
        return $this->post('binance/get-account-information', [
            'api_key' => $apiKey,
            'api_secret' => $apiSecret,
            'mrm_token' => $this->mrm_token,
            'app_code' => $this->app_code,
        ]);
    }

    public function binanceConvertCurrency(string $apiKey, string $apiSecret, string $from, string $to, float $amount): array
    {
        return $this->post('binance/convert-currency', [
            'api_key' => $apiKey,
            'api_secret' => $apiSecret,
            'from' => $from,
            'to' => $to,
            'amount' => $amount,
            'mrm_token' => $this->mrm_token,
            'app_code' => $this->app_code,
        ]);
    }

    public function createIndividualProduct(int $accountId, array $assets = []): array
    {
        return $this->post('product/create-individual-product', [
            'account_id' => $accountId,
            'assets' => $assets,
            'mrm_token' => $this->mrm_token,
            'app_code' => $this->app_code,
        ]);
    }

    public function buyProduct(int $accountId, int $productId, float $quantity): array
    {
        return $this->post('buy/product', [
            'account_id' => $accountId,
            'product_id' => $productId,
            'quantity' => $quantity,
            'mrm_token' => $this->mrm_token,
            'app_code' => $this->app_code,
        ]);
    }

    public function closeProduct(int $clientProductId): array
    {
        return $this->post('product/close', [
            'product_id' => $clientProductId,
            'mrm_token' => $this->mrm_token,
            'app_code' => $this->app_code,
        ]);
    }

    public function getClientBalances(string $subAccountId): array
    {
        return $this->post('client/get-balance', [
            'sub_account_id' => $subAccountId,
            'mrm_token' => $this->mrm_token,
            'app_code' => $this->app_code,
        ]);
    }

}
