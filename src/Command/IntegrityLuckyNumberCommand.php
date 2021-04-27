<?php

namespace App\Command;

use App\Message\LuckyNumberMessage;
use App\Traits\MessageBusAwareTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class IntegrityLuckyNumberCommand extends Command
{
    use MessageBusAwareTrait;

    protected static $defaultName = 'integrity:lucky-number';
    protected static $defaultDescription = 'Add a short description for your command';


    protected function configure()
    {
        $this
            ->setDescription(self::$defaultDescription)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $number = random_int(1, 100000);

        $message = new LuckyNumberMessage();
        $message->setNumber($number);

        $this->bus->dispatch($message);

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return 0;
    }
}
