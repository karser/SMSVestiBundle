<?php
namespace Karser\SMSVestiBundle\Command;

use Karser\SMSBundle\Command\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BalanceCommand extends BaseCommand
{
    protected function configure()
    {
        $this->setName('smsvesti:balance');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $sv = $this->getContainer()->get('karser.handler.sms_vesti');
        $balance = $sv->getBalance();
        $this->writeBalance($balance);
    }
}
