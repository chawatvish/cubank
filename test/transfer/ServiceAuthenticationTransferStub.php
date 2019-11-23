<?php namespace Stub;

require_once __DIR__ . '/DBConnectionTransferStub.php';

use ServiceAuthentication;
use Operation\DBConnectionTransferStub;

class ServiceAuthenticationTransferStub extends ServiceAuthentication {

    public static function accountAuthenticationProvider(string $accNo): array {
        return DBConnectionTransferStub::getAccountInfo($accNo);
    }
}