<?php

namespace App\MessageHandler\MRM;

use App\Message\MRM\CloseProductMessage;
use App\MessageHandler\MessageHandlerAbstract;

class CloseProductMessageHandler extends MessageHandlerAbstract
{
    use MRMMessageHandler;

    public function __invoke(CloseProductMessage $message)
    {
        try {
            $result = $this->getMrmClient()->closeProduct(
                $message->getClientProductId()
            );

            if (isset($result['status']) && $result['status'] === 'success') {
                return $result['data'];
            }
        } catch (\Exception $e) {
            dd($e);
        }
    }
}
