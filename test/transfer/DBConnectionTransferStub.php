<?php namespace Operation;

use AccountInformationException;
use DBConnection;

class DBConnectionTransferStub extends DBConnection {

    public static function callStub(): String {
        return "Hi I'm Database Stub.";
    }

    public static function saveTransaction(string $accNo, int $updatedBalance): bool {
        return true;
    }

    public static function getAccountInfo(string $accNo): array {

        if ($accNo != '9999900001' && $accNo != '9999900002') {
            throw new AccountInformationException("Account number : {$accNo} not found.");
        }

        $data = array(
            'accNo' => $accNo, 
            'accName' => 'Test Stub Dep 01', 
            'accBalance' => 1000000,
            'accWaterCharge' => 543,
            'accElectricCharge' => 500,
            'accPhoneCharge' => 340
        );

        return $data;
    }
}
