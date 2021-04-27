<?php

namespace App\Command;

use App\Entity\Integrity\Analytics\Exchange;
use App\Lib\RESTService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportExchangesCommand extends Command
{
    protected static $defaultName = 'analytics:import-exchange';

    private $RESTService;

    private $objectManager;

    public function __construct(RESTService $RESTService, EntityManagerInterface $objectManager)
    {
        $this->RESTService = $RESTService;
        $this->objectManager = $objectManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('analytics:import-exchange')

            // the short description shown while running "php bin/console list"
            ->setDescription('Imports latest exchanges list.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to get latest list of exchanges...')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            'Importing exchanges list...',
        ]);

        $actualExchanges = $this->RESTService->getExchangesList();
        $currentExchanges = $this->getCurrentExchangesValues();

        foreach ($actualExchanges["data"]["exchanges"] as $key => $name) {

            if (!in_array($key, $currentExchanges)) {

                $exchange = new Exchange();
                $exchange->setName($name)
                    ->setValue($key);

                $this->objectManager->persist($exchange);
            }
        }

        $this->objectManager->flush();

        // outputs a message followed by a "\n"
        $output->writeln('Done!');
    }

    private function getCurrentExchangesValues()
    {
        $currentExchanges = $this->objectManager->getRepository(Exchange::class)->findAll();
        $valuesArray = [];

        /** @var Exchange $currentExchange */
        foreach ($currentExchanges as $currentExchange) {

            $valuesArray[$currentExchange->getId()] = $currentExchange->getValue();
        }

        return $valuesArray;
    }
}