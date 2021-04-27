<?php

namespace App\Service\CRM\Request;

use Zoho\CRM\Request\Response as ZohoCRMResponse;

class Response extends ZohoCRMResponse
{
    /**
     * Parse response for functions using POST or PUT method.
     *
     * @return void
     */
    protected function parseResponsePostRecords() : void
    {
        $data = current($this->responseData['data']);

        if (isset($data['Status'])) {
            $this->status = $data['Status'];
        }

        if (isset($data['Details']) && is_array($data['Details'])) {
            $this->recordId = array_key_exists('id', $data['Details']) ? $data['Details']['id'] : null;
        }
    }
}
