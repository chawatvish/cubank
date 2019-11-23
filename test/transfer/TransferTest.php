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
    private $stubDB, $serviceAuthen, $stubDeposit, $stubWithdrawal, $transferService;

    protected function _before() {
        $this->stubDB = new DBConnectionTransferStub();
        $this->serviceAuthen = new ServiceAuthenticationTransferStub();
        $this->stubDeposit = new StubDeposit();
        $this->stubWithdrawal = new StubWithdrawal($this->serviceAuthen, $this->stubDB);
        $this->transferService = new Transfer($this->serviceAuthen, $this->stubDeposit, $this->stubWithdrawal);
    } 

    public function testTC001()
    {   
        $this->_before();
        $result = $this->transferService->doTransfer('9999999999','333333300g', '5000');
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
        $result = $this->transferService->doTransfer('9999999999','3333333005', '500000000');
        $this->assertTrue($result['isError']);
        $this->assertEquals("ยอดเงินไม่เพียงพอ", $result["message"]);
    }
}