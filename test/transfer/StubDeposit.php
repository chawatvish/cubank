<?php namespace Stub;

use Operation\DepositService;

class StubDeposit extends DepositService
{
    public function deposit($accNo, $amount): array
    {
        if ($accNo == '9999900002') {
            return array("accNo" => $accNo, "accName" => "TestAccountName", "accBalance" => $amount + 20);
        } else {
            return array("isError" => true, "message" => "Account number : " . $accNo . " not found.");
        }
    }
}