<?php

namespace Application\Model;

use Exception;
use Zend\Db\Sql\Sql;
use Application\Model\Transaction;

class TransactionMovements{
    const DEBIT = 1;
    const CREDIT = 2;

    private $db;

    public function __construct($db){
        $this->db = $db;
    }


    public function credit(Transaction $transaction){
        try {
            $sql = new Sql($this->db);

            $insert = $sql->insert();
            $insert->into("transactions");
            $insert->columns(array(
                "type",
                "from",
                "to",
                "value",
                "date",
            ));
            $insert->values(array(
                "type" => $this::CREDIT,
                "to" => $transaction->getTo(),
                "from" => $transaction->getFrom(),
                "value" => $transaction->getValue(),
                "date" => date("Y-m-d H:i:s"),
                
            ));
            $stm = $sql->prepareStatementForSqlObject($insert);
            $res = $stm->execute();
        }catch(\Exception $ex){
            return $this->dbFailure($ex);
        }

        return [
            'response' => true,
            'message' => "Saldo creditado com sucesso."
        ];
    }

    public function debit(Transaction $transaction){
        if($this->validateDebit($transaction)){
            try {
                $sql = new Sql($this->db);
    
                $insert = $sql->insert();
                $insert->into("transactions");
                $insert->columns(array(
                    "type",
                    "from",
                    "to",
                    "value",
                    "date",
                ));
                $insert->values(array(
                    "type" => $this::DEBIT,
                    "to" => $transaction->getTo(),
                    "from" => $transaction->getFrom(),
                    "value" => $transaction->getValue(),
                    "date" => date("Y-m-d H:i:s"),
                    
                ));
                $stm = $sql->prepareStatementForSqlObject($insert);
                $res = $stm->execute();
                    
            }catch(\Exception $ex){
                return $this->dbFailure($ex);
            }

            return [
                'response' => true,
                'message' => "Saldo debitado com sucesso."
            ];
        }else{
            return [
                'response' => false,
                'message' => "Saldo insuficiente para essa transação." 
            ];
        }  
    }

    public function validateDebit(Transaction $transaction){
        $account = new Account($this->db, $transaction->getFrom());
        $balance = $account->getBalance();
        
        if($balance < $transaction->getValue()){
            return false;
        }
        return true;
    }

    public function transfer($transfer){
        $transaction = [
            'from' => $transfer['from'],
            'to' => $transfer['to'],
            'value' => $transfer['value']
        ];

        $debit = new Transaction($this->db, $transaction);
        $credit = new Transaction($this->db, $transaction);
        
        $validateDebit = $this->debit($debit);
        
        if($validateDebit['response']){
            $response = $this->credit($credit);

            return $response;
        }

        return $validateDebit;
    }

    public function dbFailure(Exception $ex){
        return [
            'response' => false,
            'message' => "Ocorreu um erro de conexão com o banco de dados, favor contatar o administrador.",
            'error' => $ex->getMessage()
        ];
    }
}