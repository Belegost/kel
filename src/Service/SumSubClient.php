<?php


namespace App\Service;


use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;

class SumSubClient
{
    private ?string $baseUrl;

    private ?string $apiKey;

    private ?string $apiSecret;

    private ?HttpClient $httpClient;

    public function __construct(string $baseUrl, string $apiKey, string $apiSecret)
    {
        $this->baseUrl = $baseUrl;
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
    }

    public function setHttpClient(HttpClient $client): void
    {
        $this->httpClient = $client;
    }

    private function createSignature($ts, $httpMethod, $url, $httpBody): string
    {
        return hash_hmac('sha256', $ts . strtoupper($httpMethod) . $url . $httpBody, $this->apiSecret);
    }

    private function sendHttpRequest(Request $request, string $url): ResponseInterface
    {
        $ts = round(time());

        $request = $request
            ->withHeader('X-App-Token', $this->apiKey)
            ->withHeader('X-App-Access-Sig', $this->createSignature($ts, $request->getMethod(), $url, $request->getBody()))
            ->withHeader('X-App-Access-Ts', $ts);

        try {
            $response = $this->httpClient->send($request);
            if ( $response->getStatusCode() !== 200 && $response->getStatusCode() !== 201 ) {
                dd($response->getBody());
            }
        } catch (GuzzleException $e) {
            dd($e);
        }

        return $response;
    }

    public function getAccessToken(string $externalId)
    {
        $externalId = urlencode($externalId);
        $url = "/resources/accessTokens?userId={$externalId}";
        $request = new Request('POST', $this->baseUrl . $url);

        return json_decode($this->sendHttpRequest($request, $url)->getBody(), true)['token'];
    }
}
