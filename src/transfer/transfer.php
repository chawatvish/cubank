<?php namespace Operation;

require_once __DIR__ . './../serviceauthentication/AccountInformationException.php';
require_once __DIR__ . './../serviceauthentication/serviceauthentication.php';
require_once __DIR__ . './../serviceauthentication/DBConnection.php';
require_once __DIR__ . './../withdraw/Withdrawal.php';
require_once __DIR__ . './../deposit/DepositService.php';

use AccountInformationException;
use Exception;
use ServiceAuthentication;
use DBConnection;
use Operation\DepositService;
use Operation\Withdrawal;

class Transfer
{
    private $service, $depositService, $withdrawalService;
    public function __construct(
        ServiceAuthentication $service = null,
        DepositService $depositService = null,
        Withdrawal $withdrawalService = null) {
        if ($service == null) {
            $this->service = new ServiceAuthentication();
        } else {
            $this->service = $service;
        }
        if ($depositService == null) {
            $this->depositService = new DepositService();
        } else {
            $this->depositService = $depositService;
        }
        if ($withdrawalService == null) {
            $this->withdrawalService = new Withdrawal(new ServiceAuthentication(), new DBConnection());
        } else {
            $this->withdrawalService = $withdrawalService;
        }
    }
    public function doTransfer(string $srcNumber, string $targetNumber, string $amount): array
    {
        $response = array("isError" => true);
        $srcBal = 0;
        if (strlen($targetNumber) != 10 || !is_numeric($targetNumber)) {
            $response["message"] = "หมายเลขบัญชีไม่ถูกต้อง";
            return $response;
        }

        if (!is_int($amount) && $amount < 0) {
            $response["message"] = "จำนวนเงินไม่ถูกต้อง";
            return $response;
        }

        try {
            $result = $this->service::accountAuthenticationProvider($srcNumber);
            $srcBal = $result["accBalance"];
            if ($srcBal < $amount) {
                $response["message"] = "ยอดเงินไม่เพียงพอ";
                return $response;
            }

            $withdrawalResult = $this->withdrawalService->withdraw($srcNumber, $amount);
            if ($withdrawalResult["isError"] == true) {
                $response["message"] = $withdrawalResult["message"];
                return $response;
            }
            $depositResult = $this->depositService->deposit($targetNumber ,$amount);
            if ($depositResult["isError"] == true) {
                $response["message"] = $depositResult["message"];
                return $response;
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
