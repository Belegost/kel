<?php


namespace App\Message\Binance;


class GetAccountInformationMessage
{

    private string $apiKey;

    private string $apiSecret;

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     * @return GetAccountInformationMessage
     */
    public function setApiKey(string $apiKey): GetAccountInformationMessage
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    /**
     * @return string
     */
    public function getApiSecret(): string
    {
        return $this->apiSecret;
    }

    /**
     * @param string $apiSecret
     * @return GetAccountInformationMessage
     */
    public function setApiSecret(string $apiSecret): GetAccountInformationMessage
    {
        $this->apiSecret = $apiSecret;
        return $this;
    }


}