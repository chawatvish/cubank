<?php namespace Operation;

require_once __DIR__ . './../serviceauthentication/DBConnection.php';
require_once __DIR__ . './../serviceauthentication/serviceauthentication.php';
require_once __DIR__ . './../serviceauthentication/AccountInformationException.php';

use AccountInformationException;
use DBConnection;
use ServiceAuthentication;

class Withdrawal
{
    private $serviceAuthentication;
    private $dbConnection;

    public function __construct($serviceAuthentication, $dbConnection)
    {
        $this->serviceAuthentication = $serviceAuthentication;
        $this->dbConnection = $dbConnection;
    }

    public function withdraw($accNo, $withDrawamount): array
    {
        try {

            $response = array("isError" => true);
            if (!preg_match('/^[0-9]*$/', $accNo)) {
                $response["message"] = "หมายเลขบัญชีจะต้องเป็นตัวเลขเท่านั้น";
            } elseif (strlen($accNo) != 10) {
                $response["message"] = "หมายเลขบัญชีต้องเป็นตัวเลข 10 หลัก";
            } else {
                $number = floatval($withDrawamount);
                if (!is_numeric($withDrawamount)) {
                    $response["message"] = "จำนวนเงินต้องเป็นตัวเลขเท่านั้น";
                } elseif (!$this->checkWithdrawalNumberIsPositiveInteger($number)) {
                    $response["message"] = "จำนวนเงินที่ต้องการถอนต้องเป็นตัวเลขจำนวนเต็มที่มีค่ามากกว่า 0 เท่านั้น";
                } else {
                    $result = $this->serviceAuthentication::accountAuthenticationProvider($accNo);
                    $number = intval($withDrawamount);
                    $accBalance = intval($result["accBalance"]);
                    if ($number > $accBalance) {
                        $response["message"] = "ยอดเงินไม่พอ";
                    } else {
                        $updatedBalance = $accBalance - $number;
                        $this->dbConnection::saveTransaction($accNo, $updatedBalance);
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

    private function checkWithdrawalNumberIsPositiveInteger(float $input): bool
    {
        if (is_int($input) || $input == intval($input)) {
            return intval($input) > 0;
        } else {
            return false;
        }
    }

}
