<?php

namespace App\Command;

use App\Entity\Integrity\Analytics\Exchange;
use App\Entity\Integrity\Analytics\Instrument;
use App\Lib\RESTService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportInstrumentsCommand extends Command
{
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
            ->setName('analytics:import-instrument')

            // the short description shown while running "php bin/console list"
            ->setDescription('Imports latest instruments list.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to get latest list of trading instruments...')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            'Importing instruments list...',
        ]);

        $currentExchanges = $this->getCurrentExchangesValues();

        foreach ($currentExchanges as $exchangeId) {

            $exchange = $this->objectManager->getRepository(Exchange::class)
                ->findOneById($exchangeId);

            $currentInstruments = $this->getCurrentInstrumentsValues($exchange);
            $actualInstruments = $this->RESTService->getInstrumentsList($exchangeId);

            foreach ($actualInstruments['data']['pairs'] as $key => $name) {

                if (!in_array($key, $currentInstruments)) {

                    $instrument = new Instrument();
                    $instrument->setExchange($exchange)
                        ->setName($name['name'])
                        ->setValue($key);

                    $this->objectManager->persist($instrument);
                }
            }

            $this->objectManager->flush();
        }

        // outputs a message followed by a "\n"
        $output->writeln('Done!');
    }

    private function getCurrentInstrumentsValues(Exchange $exchange)
    {
        $valuesArray = [];
        $currentInstruments = $this->objectManager->getRepository(Instrument::class)
            ->findByExchange($exchange->getId());

        /** @var Instrument $currentInstrument */
        foreach ($currentInstruments as $currentInstrument) {

            $valuesArray[$currentInstrument->getId()] = $currentInstrument->getValue();
        }

        return $valuesArray;
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