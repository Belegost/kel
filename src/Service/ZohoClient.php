<?php

namespace App\Service;

use App\Service\CRM\Request\Factory;
use Zoho\CRM\Common\FactoryInterface;
use Zoho\CRM\Common\HttpClientInterface;

class ZohoClient extends \Zoho\CRM\ZohoClient
{

    /**
     * URL for call request in zoho.eu.
     *
     * @var string
     */
    const BASE_URI_EU = 'https://crm.zoho.eu';

    /**
     * Base Token URI.
     *
     * @var string
     */
    const TOKEN_URI = 'https://accounts.zoho.eu/oauth/v2/token';

    /**
     * ZohoClient constructor.
     *
     * @param string $grantToken
     * @param null $zohoClientId
     * @param null $zohoClientSecret
     * @param null $zohoRedirectUri
     * @param HttpClientInterface|null $client
     * @param FactoryInterface|null $factory
     */
    public function __construct(
        $grantToken = '',
        $zohoClientId = null,
        $zohoClientSecret = null,
        $zohoRedirectUri = null,
        HttpClientInterface $client = null,
        FactoryInterface $factory = null
    ) {
        $factory = $factory ?? new Factory();

        parent::__construct($grantToken, $zohoClientId, $zohoClientSecret, $zohoRedirectUri, $client, $factory );
    }

    /**
     * Generate Access Token by Grant Token.
     *
     * @return void
     */
    public function generateAccessTokenByRefreshToken(): void
    {
        $authRefreshArray = [
            'refresh_token' => $this->authRefreshToken,
            'client_id' => $this->zohoClientId,
            'client_secret' => $this->zohoClientSecret,
            'grant_type' => self::GRANT_TYPE_REFRESH
        ];

        //Use Guzzle client to make call
        $res = $this->client->post(static::TOKEN_URI, ['query' => $authRefreshArray, 'verify' => true]);
        $auth = json_decode($res->getBody(), true);

        $this->authAccessToken = array_key_exists('access_token', $auth) ? $auth['access_token'] : $this->authAccessToken;
    }

    /**
     * Select EU Domain.
     *
     * @param bool isEU
     * @param mixed $eu
     */
    public function setEuDomain($eu = true)
    {
        $this->baseUri = $eu ? static::BASE_URI_EU : static::BASE_URI;
    }

    public function setBaseUri(string $url): void
    {
        $this->baseUri = $url;
    }

}
