<?php 
namespace Operation;
class DBConnectionStub {
    public static function saveTransaction(string $accNo, int $updatedBalance): bool {
        return true;
    }
	
	public static function updateBill(string $accNo, int $updatedBalance, int $type): bool{
        return true;
	}
}