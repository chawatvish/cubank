<?php namespace Stub;

use Operation\Withdrawal;

class StubWithdrawal extends Withdrawal
{
    public function withdraw($accNo, $withDrawamount): array
    {
        if ($accNo == '9999999999') {
            return array("accNo" => $this->accNo, "accName" => "TestAccountName", "accBalance" => $withDrawamount - 20);
        } else {
            return array("isError" => true, "message" => "Account number : " . $this->accNo . " not found.");
        }
    }
}
