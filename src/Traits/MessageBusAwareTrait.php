<?php


namespace App\Traits;


use Symfony\Component\Messenger\MessageBusInterface;

trait MessageBusAwareTrait
{

    protected MessageBusInterface $bus;

    /**
     * @param MessageBusInterface $bus
     */
    public function setBus(MessageBusInterface $bus): void
    {
        $this->bus = $bus;
    }


}