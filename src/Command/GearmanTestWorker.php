<?php

namespace Command;

use GearmanWorker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GearmanTestWorker extends AbstractAgentCommand
{
    protected function configure()
    {
        $this->setName('gearman:test');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $worker = $this->getWorker();

        $output->writeln('Starting gearman worker');

        $worker->addFunction("test", function ($job) use ($output) {

            $output->writeln('Job recieved');

            var_dump($job->workload());
        });

        while ($worker->work()) {
        }

    }
}