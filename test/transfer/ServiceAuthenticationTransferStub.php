<?php namespace Stub;

require_once __DIR__ . './../DBConnectionStub.php';

use ServiceAuthentication;
use Operation\DBConnectionStub;

class ServiceAuthenticationTransferStub extends ServiceAuthentication {

    public static function accountAuthenticationProvider(string $accNo): array {
        return DBConnectionStub::getAccountInfo($accNo);
    }
}