<?php namespace Operation;

require_once __DIR__ . './../serviceauthentication/DBConnection.php';
require_once __DIR__ . './../serviceauthentication/serviceauthentication.php';
require_once __DIR__ . './../serviceauthentication/AccountInformationException.php';

use AccountInformationException;
use DBConnection;
use ServiceAuthentication;

class Withdrawal
{
    private $session;
    private $serviceAuthentication;
    private $dbConnection;

    public function __construct(string $session, $serviceAuthentication, $dbConnection)
    {
        $this->session = $session;
        $this->serviceAuthentication = $serviceAuthentication;
        $this->dbConnection = $dbConnection;
    }

    public function withdraw($withDrawamount): array
    {
        try {
        
            $response = array("isError" => true);
            if (!preg_match('/^[0-9]*$/', $this->session)) {
                $response["message"] = "Account no. must be numeric!";
            } elseif (strlen($this->session) != 10) {
                $response["message"] = "Account no. must have 10 digit!";
            } else {
                $number = floatval($withDrawamount);
                if(!is_numeric($withDrawamount)){
                    $response["message"] = "Amount must be numeric!";
                } elseif(!$this->checkWithdrawalNumberIsPositiveInteger($number)){
                    $response["message"] = "จำนวนเงินที่ต้องการถอนต้องเป็นตัวเลขจำนวนเต็มที่มีค่ามากกว่า 0 เท่านั้น";
                } else{
                    $result = $this->serviceAuthentication::accountAuthenticationProvider($this->session);
                    $number = intval($withDrawamount);
                    $accBalance = intval($result["accBalance"]);
                    if ($number > $accBalance) {
                        $response["message"] = "ยอดเงินไม่พอ";
                    } else {
                        $updatedBalance = $accBalance - $number;
                        $this->dbConnection::saveTransaction($this->session, $updatedBalance);
                        $response["accNo"] = $result["accNo"];
                        $response["accName"] = $result["accName"];
                        $response["accBalance"] = $updatedBalance;
                        $response["isError"] = false;
                    }
                }
          
            }
        } catch (AccountInformationException $e) {
            $response["message"] = $e->getMessage();
        } catch (Exception $e) {
            $response["message"] = "Unknown error occurs in Authentication";
        } catch (Error $e) {
            $response["message"] = "Unknown error occurs in Authentication";
        }

        return $response;
    }

    private function checkWithdrawalNumberIsPositiveInteger(float $input): bool {
        if (is_int($input) || $input == intval($input)) {
            return intval($input) > 0;
        } else {
            return false;
        }
    }

}
