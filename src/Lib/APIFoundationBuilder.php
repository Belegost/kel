<?php
/**
 * Created by PhpStorm.
 * User: Grand
 * Date: 19.05.2018
 * Time: 18:45
 */

namespace App\Lib;

use App\Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Psr\Log\LoggerInterface;

abstract class APIFoundationBuilder {
    /** @var LoggerInterface */
    protected $logger;
    /** @var Client */
    protected $guzzle;

    public function __construct(LoggerInterface $logger) {
        $this->logger = $logger;

        if (!empty($this->getHost())) {
            $this->guzzle = new Client([
                'base_uri' => trim($this->getHost(), '/') . '/',
                'verify' => false,
            ]);
        } else {
            throw new \InvalidArgumentException('API host is not defined.');
        }
    }

    abstract protected function getHost(): ?string;

    public function post(string $method, array $data = []): array {
        try {
            $request = new Request('POST', $method);
            /** @var Response $response */
            $response = $this->guzzle->send($request, [
                'form_params' => $data,
                'verify' => getenv('APP_ENV') !== 'dev',
            ]);

            return json_decode($response->getBody(), JSON_OBJECT_AS_ARRAY) ?: [];
        } catch (\Throwable $t) {
            $message = "API HTTP-Post error: [{$t->getCode()}] {$t->getMessage()}.";
            $this->logger->critical($message, $t->getTrace());
//            throw new Exception($t->getMessage());
        }

        return [];
    }
}
