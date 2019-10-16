<?php


namespace App\Tests\Controller;

use App\Controller\CurrencyController;
use PHPUnit\Framework\TestCase;

class CurrencyTest extends TestCase
{
    public function testAdd()
    {
        $currency = new CurrencyController();
        $amount = $currency->convert("EUR", 1000);

        // assert that your calculator added the numbers correctly!
        $this->assertEquals(1102.6, $amount);
    }
}