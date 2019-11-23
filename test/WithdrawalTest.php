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
        $withdraw = new Withdrawal(new ServiceAuthenticationStub(), new DBConnectionStub());
        $response = $withdraw->withdraw('1234567890', '2000');
        $this->assertEquals("1234567890", $response["accNo"]);
        $this->assertEquals("Mr.Abc Dfg", $response["accName"]);
        $this->assertEquals(8000, $response["accBalance"]);
        $this->assertFalse($response["isError"]);
    }

    public function testWithdrawAccNumberNotFound() {
        $withdraw = new Withdrawal(new ServiceAuthenticationStub(), new DBConnectionStub());
        $response = $withdraw->withdraw('5678912345', '2000');
        $this->assertEquals("Account number : 5678912345 not found.", $response["message"]);
        $this->assertTrue($response["isError"]);
    }

    public function testWithdrawAccNumberIsNotANumber() {
        $withdraw = new Withdrawal(new ServiceAuthenticationStub(), new DBConnectionStub());
        $response = $withdraw->withdraw('abcdefghij', '2000');
        $this->assertEquals("หมายเลขบัญชีจะต้องเป็นตัวเลขเท่านั้น", $response["message"]);
        $this->assertTrue($response["isError"]);
    }

    public function testWithdrawWithdrawAmountIsNotANumber() {
        $withdraw = new Withdrawal(new ServiceAuthenticationStub(), new DBConnectionStub());
        $response = $withdraw->withdraw('1234567890', 'fifty');
        $this->assertEquals("จำนวนเงินต้องเป็นตัวเลขเท่านั้น", $response["message"]);
        $this->assertTrue($response["isError"]);
    }

    public function testWithdrawAccNumberIsLessThan10Digits() {
        $withdraw = new Withdrawal(new ServiceAuthenticationStub(), new DBConnectionStub());
        $response = $withdraw->withdraw('12345', '2000');
        $this->assertEquals("หมายเลขบัญชีต้องเป็นตัวเลข 10 หลัก", $response["message"]);
        $this->assertTrue($response["isError"]);
    }

    public function testWithdrawAccNumberIsMoreThan10Digits() {
        $withdraw = new Withdrawal(new ServiceAuthenticationStub(), new DBConnectionStub());
        $response = $withdraw->withdraw('1234567891011', '2000');
        $this->assertEquals("หมายเลขบัญชีต้องเป็นตัวเลข 10 หลัก", $response["message"]);
        $this->assertTrue($response["isError"]);
    }

    public function testWithdrawNotEnoughMoney() {
        $withdraw = new Withdrawal(new ServiceAuthenticationStub(), new DBConnectionStub());
        $response = $withdraw->withdraw('1234567890', '12000');
        $this->assertEquals("ยอดเงินไม่พอ", $response["message"]);
        $this->assertTrue($response["isError"]);
    }

    public function testWithdrawAmountIsZero() {
        $withdraw = new Withdrawal(new ServiceAuthenticationStub(), new DBConnectionStub());
        $response = $withdraw->withdraw('1234567890', '0');
        $this->assertEquals("จำนวนเงินที่ต้องการถอนต้องเป็นตัวเลขจำนวนเต็มที่มีค่ามากกว่า 0 เท่านั้น", $response["message"]);
        $this->assertTrue($response["isError"]);
    }

    public function testWithdrawAmountIsNotInteger() {
        $withdraw = new Withdrawal(new ServiceAuthenticationStub(), new DBConnectionStub());
        $response = $withdraw->withdraw('1234567890', '1.5');
        $this->assertEquals("จำนวนเงินที่ต้องการถอนต้องเป็นตัวเลขจำนวนเต็มที่มีค่ามากกว่า 0 เท่านั้น", $response["message"]);
        $this->assertTrue($response["isError"]);
    }

    public function testWithdrawAmountIsLessThanZero() {
        $withdraw = new Withdrawal(new ServiceAuthenticationStub(), new DBConnectionStub());
        $response = $withdraw->withdraw('1234567890', '-1');
        $this->assertEquals("จำนวนเงินที่ต้องการถอนต้องเป็นตัวเลขจำนวนเต็มที่มีค่ามากกว่า 0 เท่านั้น", $response["message"]);
        $this->assertTrue($response["isError"]);
    }
}