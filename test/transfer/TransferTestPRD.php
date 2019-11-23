<?php

include_once __DIR__ . "/../src/transfer/Transfer.php";

use Operation\Transfer;
use PHPUnit\Framework\TestCase;

class TransferTest extends TestCase
{
    public function testTC001()
    {
        $transfer = new Transfer('3333333001', 'TEST 001');
        $result = $transfer->doTransfer('333333300g', 5000);
        $this->assertTrue($result['isError']);
        $this->assertEquals("หมายเลขบัญชีไม่ถูกต้อง", $result["message"]);
    }

    public function testTC002()
    {
        $transfer = new Transfer('3333333001', 'TEST 001');
        $result = $transfer->doTransfer('123456789', 5000);
        $this->assertTrue($result['isError']);
        $this->assertEquals("หมายเลขบัญชีไม่ถูกต้อง", $result["message"]);
    }

    public function testTC003()
    {
        $transfer = new Transfer('3333333001', 'TEST 001');
        $result = $transfer->doTransfer('123456789', 5000);
        $this->assertTrue($result['isError']);
        $this->assertEquals("หมายเลขบัญชีไม่ถูกต้อง", $result["message"]);
    }
    
    public function testTC004()
    {
        $transfer = new Transfer('3333333001', 'TEST 001');
        $result = $transfer->doTransfer('3333333005', 50000);
        $this->assertTrue($result['isError']);
        $this->assertEquals("ยอดเงินไม่เพียงพอ", $result["message"]);
    }
}