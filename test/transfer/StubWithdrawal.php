<?php namespace Stub;

class StubWithdrawal extends Withdrawal
{
    private $accNo;

    public function __construct(string $accNo)
    {
        $this->accNo = $accNo;
    }

    public function withdraw(string $amount): array
    {
        if ($this->accNo == '9999999999') {
            return array("accNo" => $this->accNo, "accName" => "TestAccountName", "accBalance" => $amount - 20);
        } else {
            return array("isError" => true, "message" => "Account number : " . $this->accNo . " not found.");
        }
    }
}
