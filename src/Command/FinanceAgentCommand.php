<?php

namespace Command;

use BuyAction;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FinanceAgentCommand extends AbstractAgentCommand
{
    const ACTION_BUY = 'buy';

    protected function configure()
    {
        $this->setName('agent:finance');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Starting finance agent');

        $worker = $this->getWorker();
        $client = $this->getClient();

        $worker->addFunction(self::ACTION_BUY, function ($job) use ($output, $client) {

            /** @var BuyAction $action */
            $action = unserialize($job->workload());

            $output->writeln('Buy action requested');
            $message = sprintf(
                'Buying %s %s for %s %s',
                $action->getValue(),
                $action->getToCurrency(),
                $action->getValue() * $action->getRate(),
                $action->getFromCurrency()
            );
            $output->writeln($message);

            // Go to some API and buy currencies

            $message = [
                'message' => 'Transaction finished',
                'data'    => $message,
            ];

            $client->doBackground(NotificationAgentCommand::ACTION_NOTIFY, json_encode($message));

        });

        while ($worker->work()) {
        }
    }


}