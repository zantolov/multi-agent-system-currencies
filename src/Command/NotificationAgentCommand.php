<?php

namespace Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class NotificationAgentCommand extends AbstractAgentCommand
{

    const ACTION_NOTIFY = 'notify';

    protected function configure()
    {
        $this->setName('agent:notification');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Starting notification agent');

        $worker = $this->getWorker();

        $worker->addFunction(self::ACTION_NOTIFY, function ($job) use ($output) {
            $payload = json_decode($job->workload(), true);

            $output->writeln('');
            $output->writeln(sprintf('New notification: %s', json_encode($payload)));
            $output->writeln('');
        });

        while ($worker->work()) {
        }
    }

}