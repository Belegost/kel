<?php
/**
 * Created by PhpStorm.
 * User: Grand
 * Date: 22.06.2018
 * Time: 00:46
 */

namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

class BitGoClient {
    protected $API_ENDPOINT;
    protected $API_BLOCKCHAIN;
    protected $ACCESS_TOKEN;

    /** @var Client */
    private $guzzleClient;

    public function __construct(array $options) {
        foreach ($options as $name => $value) {
            if (property_exists($this, $name)) {
                $this->{$name} = $value;
            }
        }

        if (isset($this->ACCESS_TOKEN)) {
            $this->guzzleClient = new Client([
                'headers' => ['Authorization' => 'Bearer ' . $this->ACCESS_TOKEN]
            ]);
        }
    }

    protected function call(Request $request, array $options = []): array {
        try {
            /** @var Response $response */
            $response = $this->guzzleClient->send($request, $options);
            return json_decode($response->getBody(), JSON_OBJECT_AS_ARRAY) ?: [];
        } catch (\Throwable $t) {
            return [
                "status" => "501 Unexpected exception",
                "error" => $t->getMessage()
            ];
        }
    }

    protected function httpGet(string $uri, array $queryParams = [], array $bodyParams = []) {
        $request = new Request('GET', $uri);
        return $this->call($request, [
            'query' => $queryParams,
            'form_params' => $bodyParams
        ]);
    }

    protected function httpPost(string $uri, array $queryParams = [], array $bodyParams = []) {
        $request = new Request('POST', $uri);
        return $this->call($request, [
            'query' => $queryParams,
            'form_params' => $bodyParams
        ]);
    }

    protected function httpPut(string $uri, array $queryParams = [], array $bodyParams = []) {
        $request = new Request('PUT', $uri);
        return $this->call($request, [
            'query' => $queryParams,
            'form_params' => $bodyParams
        ]);
    }

    protected function httpDelete(string $uri, array $queryParams = [], array $bodyParams = []) {
        $request = new Request('DELETE', $uri);
        return $this->call($request, [
            'query' => $queryParams,
            'form_params' => $bodyParams
        ]);
    }

    public function makeEndpointUri(string $method) {
        return sprintf('%s/%s', trim($this->API_ENDPOINT, ' /'), $method);
    }

    public function makeBlockChainUri(string $method) {
        return sprintf('%s/%s', trim($this->API_BLOCKCHAIN, ' /'), $method);
    }

    public function listWallets(string $coin): array {
        return $this->httpGet($this->makeEndpointUri("{$coin}/wallet"));
    }

    public function createAddress(string $coin, string $id, array $data = []): ?string {
        $bodyParams = array_merge([
            'label' => null,
            'chain' => null,
            'allowMigrated' => null,
            'gasPrice' => null,

        ], $data);
        $bodyParams = array_filter($bodyParams, function ($value) {
            return !is_null($value);
        });

        $result = $this->httpPost(
            $this->makeEndpointUri("{$coin}/wallet/{$id}/address"),
            ['id' => $id],
            $bodyParams
        );

        return isset($result['address']) ? $result['address'] : null;
    }

    public function getAddressDetails(string $address): array {
        return $this->httpGet($this->makeBlockChainUri("address/{$address}"), ['address' => $address]);
    }
}