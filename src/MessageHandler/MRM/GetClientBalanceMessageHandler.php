<?php

namespace App\MessageHandler\MRM;

use App\Message\MRM\GetClientBalanceMessage;
use App\MessageHandler\MessageHandlerAbstract;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class GetClientBalanceMessageHandler extends MessageHandlerAbstract
{
    use MRMMessageHandler;

    public function __invoke(GetClientBalanceMessage $message)
    {
        try {
            $result = $this->getMrmClient()->getClientBalances(
                $message->getSubAccountId()
            );

            if (isset($result['status']) && $result['status'] === 'success') {
                return $result['data'];
            }
        } catch (\Exception $e) {
            dd($e);
        }

        return false;
    }
}
