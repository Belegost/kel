<?php

namespace App\MessageHandler\Binance;

use App\MessageHandler\MessageHandlerAbstract;
use App\Message\Binance\GetDepositHistoryMessage;

class GetDepositHistoryMessageHandler extends MessageHandlerAbstract
{
    use BinanceMessageHandler;

    public function __invoke(GetDepositHistoryMessage $message)
    {
        try {
            $result = $this->getMrmClient()->binanceGetDepositHistory(
                $message->getSubAccountId()
            );
            if (isset($result['status']) && $result['status'] == 'success') {
                return $result['data'];
            }
        } catch (\Throwable $e) {
            dd($e);
        }
        return [];
    }
}