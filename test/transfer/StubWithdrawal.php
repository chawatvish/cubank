<?php namespace Stub;

class StubWithdrawal
{
    public static function doWithdrawal(string $accNo, string $amount): array
    {
        if ($accNo == '3333333001') {
            return array("accNo" => $accNo, "accName" => "TestAccountName", "accBalance" => $amount - 20);
        } else {
            return array("isError" => true, "message" => "Account number : " . $accNo . " not found.");
        }
    }
}
