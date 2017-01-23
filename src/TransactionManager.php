<?php

class TransactionManager
{
    private $rateHistory = [];

    private $rateHistoryChanges = [
        1,
    ];

    private $wallet = [
        'USD' => 500,
        'EUR' => 500,
    ];

    public function suggestAction($rate, $analyzeMode)
    {

        $analyzeMode = (int)$analyzeMode;

        if (count($this->rateHistory) > 1) {
            $lastRate = array_slice($this->rateHistory, -1)[0];
            $this->rateHistoryChanges[] = $rate / $lastRate;
        }

        $this->rateHistory[] = $rate;

        if (count($this->rateHistoryChanges) < $analyzeMode) {
            return null;
        }

        $lastRates = array_slice($this->rateHistoryChanges, (-1 * $analyzeMode), $analyzeMode, true);


        /**
         * If last N rates EUR = x USD are increasing that means EUR values more USD
         * so we want to buy more EURs in order to keep value
         */
        $increasing = true;
        foreach ($lastRates as $historyRate) {
            if ($historyRate < 1) {
                $increasing = false;
                break;
            }
        }

        if ($increasing) {
            $amount = 100;
            if ($this->wallet['USD'] < $amount) {
                return null;
            }
            $action = new BuyAction('USD', 'EUR', $amount, $rate);

            return $action;
        }

        // If last N rates are decreasing, suggest selling EURs i.e. buy USDs
        $decreasing = true;
        foreach ($lastRates as $historyRate) {
            if ($historyRate > 1) {
                $decreasing = false;
                break;
            }
        }


        if ($decreasing) {
            $amount = 100;
            if ($this->wallet['EUR'] < $amount) {
                return null;
            }
            $action = new BuyAction('EUR', 'USD', $amount, (1 / $rate));

            return $action;
        }

        return null;

    }

    public function registerAction(BuyAction $action)
    {
        $from = $action->getFromCurrency();
        $to = $action->getToCurrency();
        $value = $action->getValue();
        $rate = $action->getRate();

        $cost = $value * $rate;

        if ($cost > $this->wallet[$from]) {
            return null;
        }

        $this->wallet[$to] += $value;
        $this->wallet[$from] -= $cost;
    }

    /**
     * @return array
     */
    public function getWallet()
    {
        return $this->wallet;
    }

    public function getTotalBudgetInEur($rate)
    {
        return $this->wallet['EUR'] + $this->wallet['USD'] * $rate;
    }

}