<?php namespace Stub;

require_once "../../src/deposit/DepositService.php";

use Operation\DepositService;

class StubDeposit extends DepositService
{
    private $accNo;

    public function __construct(string $accNo)
    {
        $this->accNo = $accNo;
    }

    public function doDeposit(string $amount): array
    {
        if ($this->accNo == '9999999999') {
            return array("accNo" => $this->accNo, "accName" => "TestAccountName", "accBalance" => $amount + 20);
        } else {
            return array("isError" => true, "message" => "Account number : " . $this->accNo . " not found.");
        }
    }
}
