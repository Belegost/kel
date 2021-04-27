<?php

namespace App\MessageHandler\Binance;

use App\MessageHandler\MessageHandlerAbstract;
use App\Message\Binance\GetDepositAddressMessage;

class GetDepositAddressMessageHandler extends MessageHandlerAbstract
{
    use BinanceMessageHandler;

    public function __invoke(GetDepositAddressMessage $message)
    {
        try {
            $result = $this->getMrmClient()->binanceGetDepositAddress(
                $message->getApiKey(),
                $message->getApiSecret()
            );
            if ( isset($result['status']) && $result['status'] == 'success' ) {
                return $result['data'];
            }
        } catch (\Throwable $e) {
            dd($e);
        }
        return [];
    }
}