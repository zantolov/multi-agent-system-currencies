<?php

namespace Command;

use GearmanClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GearmanTestClient extends AbstractAgentCommand
{
    protected function configure()
    {
        $this->setName('gearman:client');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        sleep(5);
        $client = $this->getClient();

        $output->writeln('Sending data to queue');
        $client->doBackground('test', date('d.m.Y H:i:s'));
    }
}