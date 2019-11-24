<?php

require_once __DIR__ . "./../../src/transfer/transfer.php";

use Operation\Transfer;
use PHPUnit\Framework\TestCase;

class TransferPRDTest extends TestCase
{
    private $transferService;

    protected function _before() {
        $this->transferService = new Transfer();
    }

    protected function _after() {
        $this->transferService = null;
    }

    public function testTC001()
    {   
        $this->_before();
        $result = $this->transferService->doTransfer('999990000A','9999900002', '10');
        $this->assertTrue($result['isError']);
        $this->assertEquals("Source number isn't correct", $result["message"]);
        $this->_after();
    }

    public function testTC002()
    {
        $this->_before();
        $result = $this->transferService->doTransfer('99999', '9999900002', '10');
        $this->assertTrue($result['isError']);
        $this->assertEquals("Source number isn't correct", $result["message"]);
        $this->_after();
    }

    public function testTC003()
    {
        $this->_before();
        $srcNumber = '1234567890';
        $result = $this->transferService->doTransfer($srcNumber, '9999900002', '10');
        $this->assertTrue($result['isError']);
        $this->assertEquals("Account number : {$srcNumber} not found.", $result["message"]);
        $this->_after();
    }
    
    public function testTC004()
    {
        $this->_before();
        $result = $this->transferService->doTransfer('9999900001','999990000B', '10');
        $this->assertTrue($result['isError']);
        $this->assertEquals("Destination number isn't correct", $result["message"]);
        $this->_after();
    }

    public function testTC005()
    {
        $this->_before();
        $result = $this->transferService->doTransfer('9999900001','99999', '10');
        $this->assertTrue($result['isError']);
        $this->assertEquals("Destination number isn't correct", $result["message"]);
        $this->_after();
    }

    public function testTC006()
    {
        $this->_before();
        $destNumber = '1234567890';
        $result = $this->transferService->doTransfer('9999900001',$destNumber, '10');
        $this->assertTrue($result['isError']);
        $this->assertEquals("Account number : {$destNumber} not found.", $result["message"]);
        $this->_after();
    }

    public function testTC007()
    {
        $this->_before();
        $result = $this->transferService->doTransfer('9999900001','9999900002', 'ABC');
        $this->assertTrue($result['isError']);
        $this->assertEquals("Transfer amount isn't correct", $result["message"]);
        $this->_after();
    }

    public function testTC008()
    {
        $this->_before();
        $result = $this->transferService->doTransfer('9999900001','9999900002', '-10');
        $this->assertTrue($result['isError']);
        $this->assertEquals("Transfer amount isn't correct", $result["message"]);
        $this->_after();
    }

    public function testTC009()
    {
        $this->_before();
        $result = $this->transferService->doTransfer('9999900001','9999900002', '10');
        $this->assertFalse($result['isError']);
        $this->_after();
    }
}