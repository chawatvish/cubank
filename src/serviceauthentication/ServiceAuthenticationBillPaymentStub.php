<?php 
namespace Operation;
use AccountInformationException;
class ServiceAuthenticationBillPaymentStub {
    public static function accountAuthenticationProvider(string $accNo): array {
        //$response = 
        /* if($accNo !== "0000000000"){
            throw new AccountInformationException("Account number : {$accNo} not found.");
        } */  
        //return array("accBalance" => 10000,"accNo" => "1234567893","accName" => "Mr.Abc Dfg");
		//return array('accNo' => '0000000000', 'accName' => 'Mic Paul', 'accBalance' => 600000, 'message' => 'No charge','accWaterCharge' => 0);
    
	
	if($accNo == "0000000000"){
			//data tc001
			return array('accNo' => '0000000000', 'accName' => 'Donald Duck', 'accBalance' => 10000, 'accWaterCharge' => 0 ,'accElectricCharge' => 0 , 'accPhoneCharge' => 0,'message' => 'No charge');
	}
	if($accNo == "1111111111"){
		//data tc002 - tc004
			return array('accNo' => '1111111111', 'accName' => 'John Ed', 'accBalance' => 10000, 'accWaterCharge' => 10001 ,'accElectricCharge' => 10001 , 'accPhoneCharge' => 10001,'message' => 'No charge');
	}
	if($accNo == "2222222222"){
		//data tc005
			return array('accNo' => '2222222222', 'accName' => 'Mic Paul', 'accBalance' => 10000, 'accWaterCharge' => 5000 ,'accElectricCharge' => 0 , 'accPhoneCharge' => 0);
	}
	if($accNo == "3333333333"){
		//data tc006
			return array('accNo' => '3333333333', 'accName' => 'Ella Galle', 'accBalance' => 10000, 'accWaterCharge' => 0 ,'accElectricCharge' => 5000 , 'accPhoneCharge' => 0);
	}
	if($accNo == "4444444444"){
		 //data tc007
			return array('accNo' => '4444444444', 'accName' => 'Micky Mouse', 'accBalance' => 10000, 'accWaterCharge' => 0 ,'accElectricCharge' => 0 , 'accPhoneCharge' => 5000);
	}
	

  
  
  
 
	
	
	
	
	}
}