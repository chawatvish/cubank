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

        if (strlen($srcNumber) != 10 || !is_numeric($srcNumber)) {
            $response["message"] = "Source number isn't correct";
            return $response;
        }

        if (strlen($targetNumber) != 10 || !is_numeric($targetNumber)) {
            $response["message"] = "Destination number isn't correct";
            return $response;
        }

        if (!is_numeric($amount) || $amount < 0) {
            $response["message"] = "Transfer amount isn't correct";
            return $response;
        }

        try {
            $result = $this->service::accountAuthenticationProvider($targetNumber);
            $result = $this->service::accountAuthenticationProvider($srcNumber);

            $srcName = $result["accName"];
            $srcBal = $result["accBalance"];
            if ($srcBal < $amount) {
                $response["message"] = "ยอดเงินไม่เพียงพอ";
                return $response;
            }
            $withdrawalResult = $this->withdrawalService->withdraw($srcNumber, $amount);
            $isWithdrawalError = isset($withdrawalResult["isError"])  ? $withdrawalResult["isError"] : false;
            if ($isWithdrawalError) {
                $response["message"] = $withdrawalResult["message"];
                return $response;
            }

            $depositResult = $this->depositService->deposit($targetNumber ,$amount);
            $isDepositError = isset($depositResult["isError"])  ? $depositResult["isError"] : false;
            if ($isDepositError) {
                $response["message"] = $depositResult["message"];
                return $response;
            }

            $srcBalAfter = $srcBal - $amount;
            $response["accNo"] = $srcNumber;
            $response["accName"] = $srcName;
            $response["accBalance"] = $srcBalAfter;
            $response["isError"] = false;
        } catch (Exception $e) {
            $response["message"] = $e->getMessage();
        }
        
        return $response;
    }
}
