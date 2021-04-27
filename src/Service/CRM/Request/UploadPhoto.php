<?php

namespace App\Service\CRM\Request;

use CristianPontes\ZohoCRMClient\Request\AbstractRequest;
use Buzz\Message\Form\FormUpload;
use CristianPontes\ZohoCRMClient\Response\MutationResult;

/**
 * @author: igor.popravka
 * Date: 16.02.2018
 * Time: 11:55
 */
class UploadPhoto extends AbstractRequest {
    protected function configureRequest() {
        $this->request
            ->setMethod('uploadPhoto');
    }

    /**
     * Set rhe record Id to delete
     *
     * @param $id
     * @return UploadPhoto
     */
    public function id($id) {
        $this->request->setParam('id', $id);
        return $this;
    }

    /**
     * Pass the file input stream to a record
     *
     * @param $path - this must be the full path of the file. i.e: /home/path/to/file.extension
     * @return UploadPhoto
     */
    public function uploadFromPath($path) {
        $file = new FormUpload($path);
        $this->request->setParam('content', $file);
        return $this;
    }

    /**
     * @return MutationResult
     */
    public function request() {
        return $this->request
            ->request();
    }
}