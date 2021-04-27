<?php

namespace App\MessageHandler\Binance;

use App\MessageHandler\MessageHandlerAbstract;
use App\Message\Binance\CreateSubAccountMessage;

class CreateSubAccountMessageHandler extends MessageHandlerAbstract
{
    use BinanceMessageHandler;

    public function __invoke(CreateSubAccountMessage $message)
    {
        try {
            $result = $this->getMrmClient()->binanceCreateSubAccount();
            if ( isset($result['status']) && $result['status'] == 'success' ) {
                return $result['data'];
            }
        } catch (\Throwable $e) {
            dd($e);
        }
    }
}