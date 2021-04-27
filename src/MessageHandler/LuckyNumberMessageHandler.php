<?php

namespace App\MessageHandler;

use App\Message\LuckyNumberMessage;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class LuckyNumberMessageHandler extends MessageHandlerAbstract
{
    public function __invoke(LuckyNumberMessage $message)
    {
        echo "Lucky number is: {$message->getNumber()}.\n";
    }
}
