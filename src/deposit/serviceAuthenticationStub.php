<?php
require_once 'dbConnectorStub.php';

class ServiceAuthenticationStub {

    public static function accountAuthenticationProvider(string $accNo): array {
        return dbConnectorStub::getAccountInfo($accNo);
    }
}

?>