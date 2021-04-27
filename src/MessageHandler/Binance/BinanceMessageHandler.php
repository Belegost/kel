<?php

namespace App\MessageHandler\Binance;

use App\Service\MRMClient;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Trait BinanceMessageHandler
 *
 * @property-read ContainerInterface $container
 */
trait BinanceMessageHandler
{
    /**
     * @return MRMClient
     */
    public function getMrmClient(): MRMClient
    {
        return $this->container->get('app.service.mrm_client');
    }
}