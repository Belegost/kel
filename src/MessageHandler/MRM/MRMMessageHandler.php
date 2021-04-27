<?php


namespace App\MessageHandler\MRM;

use App\Service\MRMClient;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Trait BinanceMessageHandler
 *
 * @property-read ContainerInterface $container
 */
trait MRMMessageHandler
{
    /**
     * @return MRMClient
     */
    public function getMrmClient(): MRMClient
    {
        return $this->container->get('app.service.mrm_client');
    }
}
