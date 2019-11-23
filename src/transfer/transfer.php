<?php namespace Operation;

require_once __DIR__ . './../serviceauthentication/AccountInformationException.php';
require_once __DIR__ . './../serviceauthentication/ServiceAuthentication.php';
use AccountInformationException;
use Exception;
use ServiceAuthentication;
use Stub\StubDeposit;
use Stub\StubWithdrawal;

class Transfer
{
    private $srcNumber, $srcName;
    private $service, $depositService, $withdrawalService;
    public function __construct(string $srcNumber,
        string $srcName,
        ServiceAuthentication $service = null,
        StubDeposit $depositService = null,
        StubWithdrawal $withdrawalService = null) {
        $this->srcNumber = $srcNumber;
        $this->srcName = $srcName;
        if ($service == null) {
            $this->service = new ServiceAuthentication();
        } else {
            $this->service = $service;
        }
        if ($depositService == null) {
            // $this->service = new Deub();
        } else {
            $this->depositService = $depositService;
        }
        if ($withdrawalService == null) {
            // $this->withdrawalService = $withdrawalService;
        } else {
            $this->withdrawalService = $withdrawalService;
        }
    }
    public function doTransfer(string $targetNumber, string $amount)
    {
        $response = array("isError" => true);
        $srcBal = 0;
        if (strlen($targetNumber) != 10 || !is_numeric($targetNumber)) {
            $response["message"] = "หมายเลขบัญชีไม่ถูกต้อง";
            return $response;
        }
        try {
            $this->service::accountAuthenticationProvider($targetNumber);

            $result = $this->service::accountAuthenticationProvider($this->srcNumber);
            $srcBal = $result["accBalance"];
            if ($srcBal < $amount) {
                $response["isError"] = true;
                throw new AccountInformationException("ยอดเงินไม่เพียงพอ");
            }
            $withdrawalResult = $this->withdrawalService::doWithdrawal($this->srcNumber, $amount);
            if ($withdrawalResult["isError"] == true) {
                throw new Exception($withdrawalResult["message"]);
            }
            $depositResult = $this->depositService::doDeposit($targetNumber, $amount);
            if ($depositResult["isError"] == true) {
                throw new Exception($withdrawalResult["message"]);
            }
            $srcBalAfter = $srcBal - $amount;
            $response["accNo"] = $this->srcNumber;
            $response["accName"] = $this->srcName;
            $response["accBalance"] = $srcBalAfter;
            $response["isError"] = false;
        } catch (Exception $e) {
            $response["message"] = $e->getMessage();
        }
        return $response;
    }
}
