<?php namespace Operation;

require_once __DIR__.'./../src/withdraw/Withdrawal.php';
require_once 'serviceauthenticationStub.php';
require_once 'DBConnectionStub.php';

use PHPUnit\Framework\TestCase;
use Operation\ServiceAuthenticationStub;
use Operation\DBConnectionStub;
use Operation\Withdrawal;

class WithdrawalTest extends TestCase {
    public function testWithdrawSuccessfully() {
        $withdraw = new Withdrawal('1234567890', new ServiceAuthenticationStub(), new DBConnectionStub());
        $response = $withdraw->withdraw('2000');
        $this->assertEquals("1234567890", $response["accNo"]);
        $this->assertEquals("Mr.Abc Dfg", $response["accName"]);
        $this->assertEquals(8000, $response["accBalance"]);
        $this->assertFalse($response["isError"]);
    }

    public function testWithdrawAccNumberNotFound() {
        $withdraw = new Withdrawal('5678912345', new ServiceAuthenticationStub(), new DBConnectionStub());
        $response = $withdraw->withdraw('2000');
        $this->assertEquals("Account number : 5678912345 not found.", $response["message"]);
        $this->assertTrue($response["isError"]);
    }

    public function testWithdrawAccNumberIsNotANumber() {
        $withdraw = new Withdrawal('abcdefghij', new ServiceAuthenticationStub(), new DBConnectionStub());
        $response = $withdraw->withdraw('2000');
        $this->assertEquals("Account no. must be numeric!", $response["message"]);
        $this->assertTrue($response["isError"]);
    }

    public function testWithdrawWithdrawAmountIsNotANumber() {
        $withdraw = new Withdrawal('1234567890', new ServiceAuthenticationStub(), new DBConnectionStub());
        $response = $withdraw->withdraw('fifty');
        $this->assertEquals("Amount must be numeric!", $response["message"]);
        $this->assertTrue($response["isError"]);
    }

    public function testWithdrawAccNumberIsLessThan10Digits() {
        $withdraw = new Withdrawal('12345', new ServiceAuthenticationStub(), new DBConnectionStub());
        $response = $withdraw->withdraw('2000');
        $this->assertEquals("Account no. must have 10 digit!", $response["message"]);
        $this->assertTrue($response["isError"]);
    }

    public function testWithdrawAccNumberIsMoreThan10Digits() {
        $withdraw = new Withdrawal('1234567891011', new ServiceAuthenticationStub(), new DBConnectionStub());
        $response = $withdraw->withdraw('2000');
        $this->assertEquals("Account no. must have 10 digit!", $response["message"]);
        $this->assertTrue($response["isError"]);
    }

    public function testWithdrawNotEnoughMoney() {
        $withdraw = new Withdrawal('1234567890', new ServiceAuthenticationStub(), new DBConnectionStub());
        $response = $withdraw->withdraw('12000');
        $this->assertEquals("ยอดเงินไม่พอ", $response["message"]);
        $this->assertTrue($response["isError"]);
    }

    public function testWithdrawAmountIsZero() {
        $withdraw = new Withdrawal('1234567890', new ServiceAuthenticationStub(), new DBConnectionStub());
        $response = $withdraw->withdraw('0');
        $this->assertEquals("จำนวนเงินที่ต้องการถอนต้องเป็นตัวเลขจำนวนเต็มที่มีค่ามากกว่า 0 เท่านั้น", $response["message"]);
        $this->assertTrue($response["isError"]);
    }

    public function testWithdrawAmountIsNotInteger() {
        $withdraw = new Withdrawal('1234567890', new ServiceAuthenticationStub(), new DBConnectionStub());
        $response = $withdraw->withdraw('1.5');
        $this->assertEquals("จำนวนเงินที่ต้องการถอนต้องเป็นตัวเลขจำนวนเต็มที่มีค่ามากกว่า 0 เท่านั้น", $response["message"]);
        $this->assertTrue($response["isError"]);
    }

    public function testWithdrawAmountIsLessThanZero() {
        $withdraw = new Withdrawal('1234567890', new ServiceAuthenticationStub(), new DBConnectionStub());
        $response = $withdraw->withdraw('-1');
        $this->assertEquals("จำนวนเงินที่ต้องการถอนต้องเป็นตัวเลขจำนวนเต็มที่มีค่ามากกว่า 0 เท่านั้น", $response["message"]);
        $this->assertTrue($response["isError"]);
    }
}