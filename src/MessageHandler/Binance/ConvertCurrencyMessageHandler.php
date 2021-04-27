<?php

namespace App\MessageHandler\Binance;

use App\Message\Binance\ConvertCurrencyMessage;
use App\MessageHandler\MessageHandlerAbstract;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class ConvertCurrencyMessageHandler extends MessageHandlerAbstract
{
    use BinanceMessageHandler;

    public function __invoke(ConvertCurrencyMessage $message)
    {
        try {
            $result = $this->getMrmClient()->binanceConvertCurrency(
                $message->getApiKey(),
                $message->getApiSecret(),
                $message->getFrom(),
                $message->getTo(),
                $message->getAmount()
            );
            if (isset($result['status']) && $result['status'] === 'success') {
                dump($result);
                return $result['data'];
            }
        } catch (\Throwable $e) {
            dd($e);
        }
    }
}
