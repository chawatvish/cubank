<?php
class dbConnectorStub {

    private $accNo;
    private $accName;
    private $accBalance;
    private $accWaterCharge;
    private $accElectricCharge;
    private $accPhoneCharge;

    public static function getAccountInfo(string $accNo): array {

        if ($accNo !== '9999999999') {
			throw new AccountInformationException("Account number : {$accNo} not found.");
        }
        
        $data = array(
            'accNo' => '9999999999', 
            'accName' => 'Test Stub Dep 01', 
            'accBalance' => 1000000,
            'accWaterCharge' => 543,
            'accElectricCharge' => 500,
            'accPhoneCharge' => 340
        );

        return $data;
    }

    public static function saveTransaction(string $accNo, int $updatedBalance): bool {
        return true;
    }

    public function setBalance($updatedBalance) {
        $this->$accBalance = $updatedBalance;
    }
   
}
?>