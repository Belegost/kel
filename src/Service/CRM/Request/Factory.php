<?php

namespace App\Service\CRM\Request;

use Zoho\CRM\Common\FactoryInterface;

class Factory implements FactoryInterface
{
    /**
     * Create a response
     *
     * @param array $responseData
     * @param string $module
     * @param string $method
     * @return void
     */
    public function createResponse(array $responseData, string $module, string $method) : Response
    {
        return new Response($responseData, $module, $method);
    }
}
