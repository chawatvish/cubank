<?php

require_once __DIR__ . "./../../src/transfer/transfer.php";

use Operation\Transfer;
use PHPUnit\Framework\TestCase;

class TransferTest extends TestCase
{
    private $transferService;

    protected function _before() {
        $this->transferService = new Transfer();
    }

    public function testTC001()
    {   
        $this->_before();
        $result = $this->transferService->doTransfer('9999999999','333333300g', '500');
        $this->assertTrue($result['isError']);
        $this->assertEquals("หมายเลขบัญชีไม่ถูกต้อง", $result["message"]);
    }

    public function testTC002()
    {
        $this->_before();
        $result = $this->transferService->doTransfer('9999999999', '123456789', '5000');
        $this->assertTrue($result['isError']);
        $this->assertEquals("หมายเลขบัญชีไม่ถูกต้อง", $result["message"]);
    }

    public function testTC003()
    {
        $this->_before();
        $result = $this->transferService->doTransfer('9999999999', '123456789', '5000');
        $this->assertTrue($result['isError']);
        $this->assertEquals("หมายเลขบัญชีไม่ถูกต้อง", $result["message"]);
    }
    
    public function testTC004()
    {
        $this->_before();
        $result = $this->transferService->doTransfer('9999999999','9999900001', '500000000');
        $this->assertTrue($result['isError']);
        $this->assertEquals("ยอดเงินไม่เพียงพอ", $result["message"]);
    }
}