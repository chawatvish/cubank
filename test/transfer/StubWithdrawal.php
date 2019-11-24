<?php namespace Stub;

use Operation\Withdrawal;

class StubWithdrawal extends Withdrawal
{
    public static function callStub(): String {
        return "Hi I'm Withdrawal Stub.";
    }

    public function withdraw($accNo, $amount): array
    {
        if ($accNo == '9999900001') {
            return array("accNo" => $accNo, "accName" => "TestAccountName", "accBalance" => $amount - 20);
        } else {
            return array("isError" => true, "message" => "Account number : " . $accNo . " not found.");
        }
    }
}
