<?php

namespace Command;

use CurrencyRateProvider;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use TransactionManager;

class AnalyzeAgentCommand extends AbstractAgentCommand
{
    /** @var  CurrencyRateProvider */
    private $rateProvider;

    /** @var  TransactionManager */
    private $transactionManager;

    private $transactionIndex = 0;

    private $chartData = [];

    protected function configure()
    {
        $this->setName('agent:analyze');
        $this->addOption('mode', 'm', InputOption::VALUE_OPTIONAL, 'Criteria', 3);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->rateProvider = new CurrencyRateProvider();
        $this->transactionManager = new TransactionManager();

        $analyzeMode = $input->getOption('mode');

        $continue = true;
        while ($continue) {
            $output->writeln('waiting ...');
            $continue = $this->analyze($output, $analyzeMode);
            $output->writeln('');
            sleep(1);
        }

        file_put_contents(__DIR__ . "/../../chartData.json", json_encode($this->chartData));

    }

    private function saveRateAndBudget($rate)
    {
        $this->chartData[] = $this->transactionManager->getTotalBudgetInEur($rate);
    }

    private function analyze(OutputInterface $output, $analyzeMode = 3)
    {
        $rate = $this->rateProvider->getRatesAndUpdate();

        if (is_null($rate)) {
            return false;
        }

        $this->saveRateAndBudget($rate);

        $suggestedAction = $this->transactionManager->suggestAction($rate, $analyzeMode);

        $output->writeln($this->getBudget($rate));

        if (empty($suggestedAction)) {
            $output->writeln('No action ...');

            return true;
        }

        $output->writeln('Requesting action ...');
        $result = $this->getClient()->doNormal(FinanceAgentCommand::ACTION_BUY, serialize($suggestedAction));
        $this->transactionManager->registerAction($suggestedAction);

        $output->writeln($this->getBudget($rate));


        return true;
    }

    private function getBudget($rate)
    {
        $budget = $this->transactionManager->getTotalBudgetInEur($rate);
        $wallet = $this->transactionManager->getWallet();

        return sprintf('%s: Budget in EUR: %s at rate %s. %s EUR, %s USD',
            ++$this->transactionIndex,
            $budget,
            $rate,
            $wallet['EUR'],
            $wallet['USD']
        );
    }

}