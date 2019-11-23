<?php namespace Operation;

require_once(__DIR__.'../../serviceauthentication/serviceauthentication.php');
require_once(__DIR__.'../../serviceauthentication/DBConnection.php');
require_once 'serviceAuthenticationStub.php'; // stub
require_once 'dbConnectorStub.php'; // stub

use AccountInformationException;
use serviceauthentication;
use DBConnection;
use serviceAuthenticationStub; // stub
use dbConnectorStub; // stub
use phpDocumentor\Reflection\Types\Boolean;

class DepositService{

    private $accNo;
    
    private $isTestAuthStub = false;
    private $isTestTxStub = false;

    public function __construct(string $accNo){
        $this->accNo = $accNo;
    }

    public function setTestAuthStub(bool $isTestAuthStub)
    {
        $this->isTestAuthStub = $isTestAuthStub;
    }

    public function setTestTxStub(bool $isTestTxStub)
    {
        $this->isTestTxStub = $isTestTxStub;
    }
    
    // main service
    public function deposit($amount): array {
        $result = array("isError" => true);

        // validate 1. Amount must be numeric
        if(!preg_match('/^[0-9]*$/', $amount) || $amount == ''){
            $result["errorMessage"] = "Amount must be numeric!";
            
        } else if (strlen($this->accNo) != 10){ // validate 2. Account No. must have have 10 digit
            $result["errorMessage"] = "Account No. must have have 10 digit!";
            
        } else if (!preg_match('/^[0-9]*$/', $this->accNo)){ // validate 3. Account no. must be numeric
            $result["errorMessage"] = "Account no. must be numeric!";

        } else { // validate 4. Account no. must have existing            
            try{
                
                // Service Authentication
                if($this->isTestAuthStub) {
                    $accountInfo = ServiceAuthenticationStub::accountAuthenticationProvider($this->accNo);
                } else {
                    $accountInfo = ServiceAuthentication::accountAuthenticationProvider($this->accNo);
                }

                // Deposit
                $accNo = $accountInfo["accNo"];
                $currentBal = $accountInfo["accBalance"] + $amount;
                if($this->isTestTxStub) {
                    dbConnectorStub::saveTransaction($accNo, $currentBal);
                } else {
                    DBConnection::saveTransaction($accNo, $currentBal);
                }

                // Assign output
                $result["accountNumber"] = $accNo;
                $result["accountName"] = $accountInfo["accName"];
                $result["accountBalance"] = $currentBal;
                $billAmount = $accountInfo["accWaterCharge"] +  $accountInfo["accElectricCharge"] +  $accountInfo["accPhoneCharge"];
                $result["billAmount"] = $billAmount;
                $result["isError"] = false;
                $result["errorMessage"] = "";
            }
            catch(AccountInformationException $e){
                $result["errorMessage"] = $e->getMessage();
            }
        }
        return $result;
    }
 }