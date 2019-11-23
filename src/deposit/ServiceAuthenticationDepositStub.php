<?php
require_once 'dbConnectorStub.php';

class ServiceAuthenticationDepositStub {

    public static function accountAuthenticationProvider(string $accNo): array {
        return dbConnectorStub::getAccountInfo($accNo);
    }
}

?>