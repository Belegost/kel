<?php

/**
 * @author: igor.popravka
 * Date: 14.06.2018
 * Time: 11:57
 */

namespace App\Command;

use App\Service\BitGoWallet;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Wallet extends Command {
    private $bitGoWallet;

    public function __construct(BitGoWallet $bitGoWallet) {
        $this->bitGoWallet = $bitGoWallet;
        parent::__construct();
    }

    protected function configure() {
        $this->setName('wallet:list')
            ->setDescription('Get list with full wallets info')
            ->setHelp('This command allows you to get information about available  wallets');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $list = $this->bitGoWallet->getWalletsList();
        if (!empty($list)) {
            foreach ($list as $wallet) {
                $output->writeln("[Wallet] <{$wallet['label']}>");
                $output->writeln([
                    "\tId:\t\t{$wallet['id']}",
                    "\tPermissions:\t{$wallet['permissions']}",
                    sprintf("\tLegacy:\t\t%s", $wallet['legacyWallet'] ? 'true' : 'false'),
                ]);
                $output->writeln("[Wallet]");
            }
        } else {
            $output->writeln(['Empty list']);
        }
    }
}
