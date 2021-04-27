<?php
/**
 * Created by PhpStorm.
 * User: Grand
 * Date: 01.03.2018
 * Time: 14:09
 */

namespace App\Service\CRM\Request;

use CristianPontes\ZohoCRMClient\Request\AbstractRequest;
use CristianPontes\ZohoCRMClient\Response\MutationResult;

class DeletePhoto extends AbstractRequest {
    protected function configureRequest() {
        $this->request
            ->setMethod('deletePhoto');
    }

    /**
     * Set rhe record Id to delete
     *
     * @param $id
     * @return DeletePhoto
     */
    public function id($id) {
        $this->request->setParam('id', $id);
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