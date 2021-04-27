<?php
/**
 * Created by PhpStorm.
 * User: Grand
 * Date: 11.05.2018
 * Time: 00:45
 */

namespace App\Service\CRM;

use CristianPontes\ZohoCRMClient\Transport\AbstractTransportDecorator;
use CristianPontes\ZohoCRMClient\Transport\Transport;
use Buzz\Message\Response;

/**
 * IntegrityDataTransportDecorator
 * @property IntegrityZohoTransport transport
 */
class IntegrityDataTransportDecorator extends AbstractTransportDecorator {
    private $authToken;

    function __construct($authToken, Transport $transport) {
        $this->authToken = $authToken;
        parent::__construct($transport);
    }

    /**
     * @param string $module
     * @param string $method
     * @param array $paramList
     * @return string
     * @throws \HttpException
     */
    public function call($module, $method, array $paramList) {
        $paramList['authtoken'] = $this->authToken;
        $paramList['scope'] = 'crmapi';

        return $this->transport->call($module, $method, $paramList);
    }

    public function getResponse(): Response {
        return $this->transport->getResponse();
    }
}
