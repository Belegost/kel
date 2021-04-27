<?php

namespace App\MessageHandler\Zoho;

use App\Service\CRM\IntegrityZohoClient;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Trait HandlerTrait
 *
 * @property-read ContainerInterface $container
 */
trait ZohoHandlerAwareTrait
{
    /**
     * Returns Zoho Client instance
     *
     * @return IntegrityZohoClient
     */
    protected function getZohoClient()
    {
        return $this->container->get('app.service.zoho_client');
    }

    /**
     * Returns Router instance
     *
     * @return RouterInterface
     */
    protected function getRouter()
    {
        return $this->container->get('router');
    }
}