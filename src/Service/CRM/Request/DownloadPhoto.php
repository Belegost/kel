<?php

namespace App\Service\CRM\Request;

use CristianPontes\ZohoCRMClient\Request\AbstractRequest;
use Buzz\Message\Response;

/**
 * @author: igor.popravka
 * Date: 16.02.2018
 * Time: 11:55
 */
class DownloadPhoto extends AbstractRequest {
    protected function configureRequest() {
        $this->request
            ->setMethod('downloadPhoto');
    }

    /**
     * Set rhe record Id to delete
     *
     * @param $id
     * @return DownloadPhoto
     */
    public function id($id) {
        $this->request->setParam('id', $id);
        return $this;
    }

    /**
     * @return array
     */
    public function request() {
        $content = $this->request
            ->request();
        /** @var Response $response */
        $response = $this->request
            ->getTransport()
            ->getResponse();

        return [
            'content' => $content,
            'content-type' => $response->getHeader('Content-Type')
        ];
    }
}