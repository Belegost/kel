<?php


namespace App\MessageHandler\Binance;


use App\MessageHandler\MessageHandlerAbstract;
use App\Message\Binance\GetAccountInformationMessage;

class GetAccountInformationMessageHandler extends MessageHandlerAbstract
{
    use BinanceMessageHandler;

    public function __invoke(GetAccountInformationMessage $message)
    {
        try {
            $result = $this->getMrmClient()->binanceGetAccountInformation(
                $message->getApiKey(),
                $message->getApiSecret()
            );
            if (isset($result['status']) && $result['status'] == 'success') {
                return $result['data'];
            }
        } catch (\Throwable $e) {
            dd($e);
        }
    }
}