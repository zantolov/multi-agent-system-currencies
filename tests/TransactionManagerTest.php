<?php

class TransactionManagerTest extends PHPUnit_Framework_TestCase
{
    public function testActionRegistration()
    {
        $manager = new TransactionManager();

        $wallet = [
            'USD' => 5000,
            'EUR' => 5000,
        ];

        $this->assertEquals($wallet, $manager->getWallet());

        $rate = 1.06318;
        $action = new BuyAction('EUR', 'USD', 100, 1 / $rate);
        $manager->registerAction($action);

        $this->assertNotEquals($wallet, $manager->getWallet());

        $wallet['USD'] += 100;
        $wallet['EUR'] -= 100 * (1 / $rate);

        $this->assertEquals($wallet, $manager->getWallet());

        $action = new BuyAction('USD', 'EUR', 100, $rate);
        $manager->registerAction($action);

        $this->assertNotEquals($wallet, $manager->getWallet());

        $wallet['EUR'] += 100;
        $wallet['USD'] -= 100 * $rate;

        $this->assertEquals($wallet, $manager->getWallet());


    }

    public function testSuggestion()
    {

    }

}
