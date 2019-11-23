<?php namespace Operation;

require_once __DIR__ . '../../serviceauthentication/serviceauthentication.php';
require_once __DIR__ . '../../serviceauthentication/DBConnection.php';
require_once 'serviceAuthenticationStub.php'; // stub
require_once 'dbConnectorStub.php'; // stub

use AccountInformationException;
use DBConnection;
use dbConnectorStub;
use serviceauthentication; // stub
use serviceAuthenticationStub;

class DepositService
{

    private $isTestAuthStub = false;
    private $isTestTxStub = false;

    public function setTestAuthStub(bool $isTestAuthStub)
    {
        $this->isTestAuthStub = $isTestAuthStub;
    }

    public function setTestTxStub(bool $isTestTxStub)
    {
        $this->isTestTxStub = $isTestTxStub;
    }

    // main service
    public function deposit($accNo, $amount): array
    {
        $result = array("isError" => true);

        // validate 1. Amount must be numeric
        if (!preg_match('/^[0-9]*$/', $amount) || $amount == '') {
            $result["message"] = "Amount must be numeric!";

        } else if (strlen($accNo) != 10) { // validate 2. Account No. must have have 10 digit
            $result["message"] = "Account No. must have have 10 digit!";

        } else if (!preg_match('/^[0-9]*$/', $accNo)) { // validate 3. Account no. must be numeric
            $result["message"] = "Account no. must be numeric!";

        } else { // validate 4. Account no. must have existing
            try {
                // Service Authentication
                if ($this->isTestAuthStub) {
                    $accountInfo = ServiceAuthenticationStub::accountAuthenticationProvider($accNo);
                } else {
                    $accountInfo = ServiceAuthentication::accountAuthenticationProvider($accNo);
                }

                // Deposit
                $accNo = $accountInfo["accNo"];
                $currentBal = $accountInfo["accBalance"] + $amount;
                if ($this->isTestTxStub) {
                    dbConnectorStub::saveTransaction($accNo, $currentBal);
                } else {
                    DBConnection::saveTransaction($accNo, $currentBal);
                }

                // Assign output
                $result["accNo"] = $accNo;
                $result["accName"] = $accountInfo["accName"];
                $result["accBalance"] = $currentBal;
                $result["isError"] = false;
                $result["message"] = "";
            } catch (AccountInformationException $e) {
                $result["message"] = $e->getMessage();
            }
        }
        return $result;
    }
}
