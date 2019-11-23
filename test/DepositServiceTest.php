<?php

require_once __DIR__.'./../src/deposit/DepositService.php';

use PHPUnit\Framework\TestCase;
use Operation\DepositService;

final class DepositServiceTest extends TestCase
{
    public function testDepositWithStub()
    {
        $inputs = $this->inputDataProvider();
        for ($x = 0;$x < sizeof($inputs);$x++) {
            $expected = $inputs[$x][0];
            $accNum = $inputs[$x][1];
            $amount = $inputs[$x][2];
            $depositHandler = new DepositService();
            $depositHandler->setTestAuthStub(true);
            $depositHandler->setTestTxStub(true);
            $output = $depositHandler->deposit($accNum,$amount);
            $this->assertEquals($expected, $output['message']);
        }
    }

    public function inputDataProvider()
    {
        return[
            //normal
            ['','9999999999','50'],
            ['','9999999999','5100'],
            //amount not number
            ['Amount must be numeric!','9999999999',''],
            ['Amount must be numeric!','9999999999','Million'],

            //accNo not complete
            ['Account No. must have have 10 digit!','000000001','50'],
            ['Account No. must have have 10 digit!','99999999999','5100'],

            //accNo not a number
            ['Account no. must be numeric!','myaccountt','50'],

           //accNo not found
            ['Account number : 0000000002 not found.','0000000002','50']
        ];
    }
}
