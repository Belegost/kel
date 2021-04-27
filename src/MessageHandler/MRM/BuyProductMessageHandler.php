<?php

namespace App\MessageHandler\MRM;

use App\Message\MRM\BuyProductMessage;
use App\MessageHandler\MessageHandlerAbstract;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class BuyProductMessageHandler extends MessageHandlerAbstract
{
    use MRMMessageHandler;

    public function __invoke(BuyProductMessage $message)
    {
        try {
            $result = $this->getMrmClient()->buyProduct(
                $message->getAccountId(),
                $message->getProductId(),
                $message->getQuantity()
            );

            if (isset($result['status']) && $result['status'] === 'success') {
                return $result['data'];
            }
        } catch (\Exception $e) {
            dd($e);
        }
    }
}
