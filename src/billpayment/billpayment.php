<?php
namespace Operation;

require_once __DIR__ . './../serviceauthentication/serviceauthentication.php';

use DBConnection;
use Exception;
use ServiceAuthentication;

class BillPayment
{

    private $session;

    public function __construct(string $session)
    {
        $this->session = $session;

    }

    public function getBill(int $billType): array
    {

        $response = array("isError" => true);
        try {

            $result = ServiceAuthentication::accountAuthenticationProvider($this->session);
            $response["accNo"] = $result["accNo"];
            $response["accName"] = $result["accName"];
            if ($billType == 0) {

                $response["accWaterCharge"] = $result["accWaterCharge"];
                $response["isError"] = false;
                if ($response["accWaterCharge"] == 0) {
                    $response["message"] = "คุณไม่มียอดค้างชำระ";
                    $response["isError"] = true;
                }
            } elseif ($billType == 1) {

                $response["accElectricCharge"] = $result["accElectricCharge"];
                $response["isError"] = false;
                if ($response["accElectricCharge"] == 0) {
                    $response["message"] = "คุณไม่มียอดค้างชำระ";
                    $response["isError"] = true;
                }
            } elseif ($billType == 2) {

                $response["accPhoneCharge"] = $result["accPhoneCharge"];
                $response["isError"] = false;
                if ($response["accPhoneCharge"] == 0) {
                    $response["message"] = "คุณไม่มียอดค้างชำระ";
                    $response["isError"] = true;
                }
            } else {

                $response["message"] = "ไม่มีรายการชำระเงินดังกล่าวในระบบ!";
            }

        } catch (Exception $e) {
            $response["message"] = $e->getMessage();
        }

        return $response;

    }

    public function pay(int $billType): array
    {

        $response = array("isError" => true);
        $result = ServiceAuthentication::accountAuthenticationProvider($this->session);
        $response["accNo"] = $result["accNo"];
        $response["accName"] = $result["accName"];
        $balance = $result["accBalance"];

        if ($billType == 0) {
            $charge = $result["accWaterCharge"];
        } elseif ($billType == 1) {
            $charge = $result["accElectricCharge"];
        } elseif ($billType == 2) {
            $charge = $result["accPhoneCharge"];
        }

        if ($balance > $charge) {
            $newBalance = $balance - $charge;
            $newCharge = 0;
            DBConnection::saveTransaction($this->session, $newBalance);
            DBConnection::updateBill($this->session, $newCharge, $billType);

            $response["accWaterCharge"] = $newCharge;
            $response["accElectricCharge"] = $newCharge;
            $response["accPhoneCharge"] = $newCharge;
            $response["accBalance"] = $newBalance;
            $response["message"] = "คุณไม่มียอดค้างชำระ";
            $response["isError"] = false;
        } else {
            $response["message"] = "ยอดเงินในบัญชีไม่เพียงพอ";
        }
        return $response;

    }

}
