<?php

class CurrencyRateProvider
{
    private $rates = [];
    private $index = 0;

    /**
     * CurrencyRateProvider constructor.
     */
    public function __construct()
    {
        $rates = require __DIR__ . '/rates.php';
        $rates = array_reverse($rates);
        $rates = array_values($rates);
        $this->rates = $rates;
    }


    public function getRates()
    {
        // if we reached end of list, return null
        if ($this->index >= count($this->rates) - 1) {
            return null;
        }

        $index = $this->index % count($this->rates);

        return $this->rates[$index];
    }

    public function updateRates()
    {
        $this->index++;
    }

    public function getRatesAndUpdate()
    {
        $rates = $this->getRates();
        $this->updateRates();

        return $rates;
    }

}