<?php

require_once __DIR__ . "./../../src/transfer/transfer.php";
require_once __DIR__ . "/DBConnectionTransferStub.php";
require_once __DIR__ . "/ServiceAuthenticationTransferStub.php";
require_once __DIR__ . "/StubDeposit.php";
require_once __DIR__ . "/StubWithdrawal.php";

use Stub\ServiceAuthenticationTransferStub;
use PHPUnit\Framework\TestCase;
use Operation\Transfer;
use Stub\StubDeposit;
use Stub\StubWithdrawal;
use Operation\DBConnectionTransferStub;

class TransferTest extends TestCase
{
    private $stubDB, $stubServiceAuthen, $stubDeposit, $stubWithdrawal, $transferService;

    protected function _before() {
        $this->stubDB = new DBConnectionTransferStub();
        $this->stubServiceAuthen = new ServiceAuthenticationTransferStub();
        $this->stubDeposit = new StubDeposit();
        $this->stubWithdrawal = new StubWithdrawal($this->stubServiceAuthen, $this->stubDB);
        $this->transferService = new Transfer($this->stubServiceAuthen, $this->stubDeposit, $this->stubWithdrawal);
    }

    protected function _after() {
        $this->stubDB = null;
        $this->stubServiceAuthen = null;
        $this->stubDeposit = null;
        $this->stubWithdrawal = null;
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