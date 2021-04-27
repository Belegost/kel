<?php

namespace App\Command;

use App\Entity\Integrity\Analytics\Capitalization;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateCapCommand extends Command
{
    protected static $defaultName = 'analytics:update-cap';

    private $objectManager;

    public function __construct(EntityManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('analytics:update-cap')

            // the short description shown while running "php bin/console list"
            ->setDescription('Get latest crypto cap values.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to get latest values of crypto cap...')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            'Updating crypto cap...',
        ]);

        $json = file_get_contents('https://graphs2.coinmarketcap.com/global/dominance/');
        $data = json_decode($json, true);
        $i = 1;

        foreach ($data as $asset => $datum) {
            $asset = $this->objectManager->getRepository('Integrity:Analytics\Asset')->find($i);
            $currentDate = (new \DateTime())->setTime(0, 0);
                $info = end($datum);

                $cap = new Capitalization();
                $cap->setAsset($asset)
                    ->setValue($info[0])
                    ->setPercent($info[1])
                    ->setCreatedDate($currentDate);

                $this->objectManager->persist($cap);
                $this->objectManager->flush();

            $i++;
        }

        // outputs a message followed by a "\n"
        $output->writeln('Done!');
    }
}