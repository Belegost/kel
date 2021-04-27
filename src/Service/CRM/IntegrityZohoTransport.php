<?php

namespace App\Service\CRM;

use CristianPontes\ZohoCRMClient\Transport\Transport;
use Buzz\Browser;
use Nyholm\Psr7\Response;
use Buzz\Message\FormRequestBuilder as FormUpload;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Transport implemented using the Buzz library to do HTTP calls to Zoho
 */
class IntegrityZohoTransport implements Transport, LoggerAwareInterface {
    const FORMAT_XML = 'xml';
    const FORMAT_JSON = 'json';

    private $browser;
    private $baseUrl;
    private $format;
    private $logger;
    /** @var Response */
    private $response;

    public function __construct(Browser $browser, $baseUrl, $format = self::FORMAT_XML) {
        $this->browser = $browser;
        $this->baseUrl = $baseUrl;
        $this->format = $format;
        $this->logger = new NullLogger();
    }

    /**
     * Sets a logger instance on the object
     *
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger) {
        $this->logger = $logger;
    }

    /**
     * @param string $module
     * @param string $method
     * @param array $paramList
     * @return string
     * @throws \HttpException
     */
    public function call($module, $method, array $paramList) {
        $url = sprintf('%s/%s/%s/%s', $this->baseUrl, $this->format, $module, $method);
        $headers = [];
        $requestBody = http_build_query($paramList, '', '&');

        $this->logger->info(sprintf(
            '[cristianpontes/zoho_crm_client_php] request: call "%s" with params %s',
            $module . '/' . $method,
            $requestBody
        ));

        // Checking for multipart request
        $multipart = false;
        foreach ($paramList as $param) {
            if ($param instanceof FormUpload) {
                $multipart = true;
                break;
            }
        }

        if ($multipart) {
            /** @var \Buzz\Message\MessageInterface $response */
            $this->response = $this->browser->submit($url, $paramList, 'POST', $headers);
        } else {
            /** @var Response $response */
            $this->response = $this->browser->post($url, $headers, $requestBody);
        }

        $responseContent = $this->response->getContent();
        if ($this->response->getStatusCode() !== 200) {
            $this->logger->error(sprintf(
                '[cristianpontes/zoho_crm_client_php] fault "%s" for request "%s" with params %s',
                $responseContent,
                $module . '/' . $method,
                $requestBody
            ));
            throw new \HttpException(
                $responseContent, $this->response->getStatusCode()
            );
        }

        $this->logger->info(sprintf(
            '[cristianpontes/zoho_crm_client_php] response: %s',
            $responseContent
        ));

        return $responseContent;
    }

    /**
     * @return Response
     */
    public function getResponse(): Response {
        return $this->response;
    }
}