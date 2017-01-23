<?php


class BuyAction
{
    private $fromCurrency;

    private $toCurrency;

    private $value;
    private $rate;

    /**
     * BuyAction constructor.
     * @param $fromCurrency
     * @param $toCurrency
     * @param $value
     */
    public function __construct($fromCurrency, $toCurrency, $value, $rate)
    {
        $this->fromCurrency = $fromCurrency;
        $this->toCurrency = $toCurrency;
        $this->value = $value;
        $this->rate = $rate;
    }

    /**
     * @return mixed
     */
    public function getFromCurrency()
    {
        return $this->fromCurrency;
    }

    /**
     * @return mixed
     */
    public function getToCurrency()
    {
        return $this->toCurrency;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return mixed
     */
    public function getRate()
    {
        return $this->rate;
    }


}
