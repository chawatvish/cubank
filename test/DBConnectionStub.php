<?php namespace Operation;
class DBConnectionStub {
    public static function saveTransaction(string $accNo, int $updatedBalance): bool {
        return true;
    }
}
