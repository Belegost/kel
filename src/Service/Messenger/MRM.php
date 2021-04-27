<?php

namespace App\Service\Messenger;

use App\Message\Binance\ConvertCurrencyMessage;
use App\Message\Binance\CreateSubAccountMessage;
use App\Message\Binance\GetAccountInformationMessage;
use App\Message\Binance\GetDepositAddressMessage;
use App\Message\Binance\GetDepositHistoryMessage;
use App\Message\MRM\GetClientBalanceMessage;
use App\Traits\MessageBusAwareTrait;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class MRM
{
    use MessageBusAwareTrait;

    public function createSubAccount(): array
    {
        $envelope = $this->bus->dispatch(new CreateSubAccountMessage());

        return $this->getResult($envelope);
    }

    public function getDepositAddress(string $apiKey, string $apiSecret): array
    {
        $message = new GetDepositAddressMessage();
        $message->setApiKey($apiKey)
            ->setApiSecret($apiSecret);

        $envelope = $this->bus->dispatch($message);

        return $this->getResult($envelope);
    }

    public function getDepositHistory(string $subAccountId): array
    {
        $message = new GetDepositHistoryMessage();
        $message->setSubAccountId($subAccountId);

        $envelope = $this->bus->dispatch($message);

        return $this->getResult($envelope);
    }

    public function getAccountInformation(string $apiKey, string $apiSecret): array
    {
        $message = new GetAccountInformationMessage();
        $message->setApiKey($apiKey)
            ->setApiSecret($apiSecret);

        $envelope = $this->bus->dispatch($message);

        return $this->getResult($envelope);
    }

    public function getClientBalances(string $subAccountId): array
    {
        $message = new GetClientBalanceMessage();
        $message->setSubAccountId($subAccountId);

        $envelope = $this->bus->dispatch($message);

        return $this->getResult($envelope);
    }

    public function convertCurrency(string $apiKey, string $apiSecret, string $from, string $to, float $amount): void
    {
        $message = new ConvertCurrencyMessage();
        $message->setApiKey($apiKey);
        $message->setApiSecret($apiSecret);
        $message->setFrom($from);
        $message->setTo($to);
        $message->setAmount($amount);

        $this->bus->dispatch($message);
    }


    protected function getResult(Envelope $envelope)
    {
        /** @var HandledStamp $handledStamp */
        $handledStamp = $envelope->last(HandledStamp::class);

        return $handledStamp->getResult();
    }
}
