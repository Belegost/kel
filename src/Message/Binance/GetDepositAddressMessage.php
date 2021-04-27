<?php


namespace App\Message\Binance;


class GetDepositAddressMessage
{
    private string $apiKey;

    private string $apiSecret;

    /**
     * @param string $apiKey
     * @return $this
     */
    public function setApiKey(string $apiKey): GetDepositAddressMessage
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiSecret
     * @return $this
     */
    public function setApiSecret(string $apiSecret): GetDepositAddressMessage
    {
        $this->apiSecret = $apiSecret;
        return $this;
    }

    /**
     * @return string
     */
    public function getApiSecret(): string
    {
        return $this->apiSecret;
    }

}