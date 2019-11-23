<?php 
namespace Operation;

require_once __DIR__.'./../src/serviceauthentication/DBConnection.php';
require_once __DIR__.'./../src/serviceauthentication/serviceauthentication.php';
require_once __DIR__.'./../src/serviceauthentication/DBConnectionStub.php';
require_once __DIR__.'./../src/serviceauthentication/ServiceAuthenticationBillPaymentStub.php';
require_once __DIR__.'./../src/billpayment/billpayment.php';

use PHPUnit\Framework\TestCase;
use Operation\BillPayment;

class BillPaymentTest extends TestCase {
    function test001_billpayment() {
		//ดึงค่าบัญชีที่ไม่มียอดค้างชำระค่าน้ำ
		$BillPayment = new BillPayment('0000000000');
		$BillPayment->setTestAuthStub(false);
        $BillPayment->setTestTxStub(false);
		$response = $BillPayment->getbill(0);
		
        $this->assertEquals("คุณไม่มียอดค้างชำระ",$response["message"]);
		
		//ดึงค่าบัญชีที่ไม่มียอดค้างชำระค่าไฟ
		$BillPayment = new BillPayment('0000000000');
		$BillPayment->setTestAuthStub(false);
        $BillPayment->setTestTxStub(false);
        $response = $BillPayment->getBill(1);
        $this->assertEquals("คุณไม่มียอดค้างชำระ", $response["message"]);

		//ดึงค่าบัญชีที่ไม่มียอดค้างชำระค่าโทรศัพท์
        $BillPayment = new BillPayment('0000000000');
		$BillPayment->setTestAuthStub(false);
        $BillPayment->setTestTxStub(false);
        $response = $BillPayment->getBill(2);
        $this->assertEquals("คุณไม่มียอดค้างชำระ", $response["message"]);
	}

	 function test002_billpayment() {
		  
		//ดึงค่าบัญชีที่ยอดเงินในบัญชี น้อยกว่าค่าน้ำ
        $BillPayment = new BillPayment('1111111111');
		$BillPayment->setTestAuthStub(false);
        $BillPayment->setTestTxStub(false);
        $response = $BillPayment->pay(0);
        $this->assertEquals("ยอดเงินในบัญชีไม่เพียงพอ", $response["message"]);
    }
	//TC-003 ทดสอบกรณี ยอดเงินในบัญชี น้อยกว่าค่าไฟ
	  function test003_billpayment() {
		  
		//ดึงค่าบัญชีที่ยอดเงินในบัญชี น้อยกว่าค่าไฟ
        $BillPayment = new BillPayment('1111111111');
		$BillPayment->setTestAuthStub(false);
        $BillPayment->setTestTxStub(false);
        $response = $BillPayment->pay(1);
        $this->assertEquals("ยอดเงินในบัญชีไม่เพียงพอ", $response["message"]);
    }

	//TC-004 ทดสอบกรณี ยอดเงินในบัญชี น้อยกว่าค่าโทรศัพท์
	  function test004_billpayment() {
		  
		//ดึงค่าบัญชีที่ยอดเงินในบัญชี น้อยกว่าค่าโทรศัพท์
        $BillPayment = new BillPayment('1111111111');
		$BillPayment->setTestAuthStub(false);
        $BillPayment->setTestTxStub(false);
        $response = $BillPayment->pay(2);
        $this->assertEquals("ยอดเงินในบัญชีไม่เพียงพอ", $response["message"]);
    }
	
	//TC-005 ทดสอบกรณี ชำระค่าน้ำเสร็จสมบูรณ์
	  function test005_billpayment() {
		  
		//ดึงค่าบัญชีที่ยอดเงินในบัญชี น้อยกว่าค่าโทรศัพท์
        $BillPayment = new BillPayment('2222222222');
		$BillPayment->setTestAuthStub(false);
        $BillPayment->setTestTxStub(false);
        $response = $BillPayment->pay(0);
        $this->assertEquals("คุณไม่มียอดค้างชำระ", $response["message"]);
    }
	
	//TC-006 ทดสอบกรณี ชำระค่าไฟเสร็จสมบูรณ์
	  function test006_billpayment() {
		  
		//ดึงค่าบัญชีที่ยอดเงินในบัญชี น้อยกว่าค่าโทรศัพท์
        $BillPayment = new BillPayment('3333333333');
		$BillPayment->setTestAuthStub(false);
        $BillPayment->setTestTxStub(false);
        $response = $BillPayment->pay(1);
        $this->assertEquals("คุณไม่มียอดค้างชำระ", $response["message"]);
    }

	//TC-007 ทดสอบกรณี ชำระค่าโทรศัพท์เสร็จสมบูรณ์
	  function test007_billpayment() {
		  
		//ดึงค่าบัญชีที่ยอดเงินในบัญชี น้อยกว่าค่าโทรศัพท์
        $BillPayment = new BillPayment('4444444444');
		$BillPayment->setTestAuthStub(false);
        $BillPayment->setTestTxStub(false);
        $response = $BillPayment->pay(2);
        $this->assertEquals("คุณไม่มียอดค้างชำระ", $response["message"]);
    }
	
   
}