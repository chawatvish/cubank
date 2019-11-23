<?php

require_once "DepositService.php";
use Operation\DepositService;

class MainDriver {
    private $accNo;
    private $amount;
    private $isTestAuthStub;
    private $isTestTxStub;

    function MainDriver() {
        $this->accNo = "9999999999";
        $this->amount = 300;
        $this->isTestAuthStub = true;
        $this->isTestTxStub = true;
    }

    public function test() {
        echo "--------------------<br/>";
        echo "::: Input :::<br/>";
        echo "Bank Account No. :".preg_replace('/(\d{1})\-?(\d{3})\-?(\d{3})\-?(\d{3})/', '$1-$2-$3-$4', $this->accNo)."<br/>";
        echo "Amount : ".$this->amount."<br/><br/>";
        echo "----On Processing---<br/><br/>";

        // create an object
        $deposit = new DepositService();
        $deposit->setTestAuthStub(true);
        $deposit->setTestTxStub(true);

        echo "::: Output :::<br/>";
        $result = json_encode($deposit->deposit($this->accNo,$this->amount));        
        $result = json_decode($result, true);

        if($result['isError']) {
            echo "Status : ".$result['message']."<br/>";  
            echo "----Failed---";
        } else { // no error
            echo "Bank Account No. :".preg_replace('/(\d{1})\-?(\d{3})\-?(\d{3})\-?(\d{3})/', '$1-$2-$3-$4', $result['accountNumber'])."<br/>";
            echo "Bank Account Name : ".$result['accountName']."<br/>";
            echo "Balance : ".$result['accountBalance']."<br/>";
            echo "Bill Amount : ".$result['billAmount']."<br/>";
            echo "Status : Success ^^ <br/>";
        }
        echo "--------------------<br/>";
    }
}
echo "Deposit Service Start<br/>";

$result = new MainDriver();
$result->test();

echo "Finish<br/>";

?>
