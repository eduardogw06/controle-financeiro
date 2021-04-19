<?php

namespace Application\Controller;

use Application\Model\People;
use Application\Model\Account;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Application\Model\Transaction;
use Application\Model\TransactionMovements;
use Zend\Mvc\Controller\AbstractActionController;

class TransactionsController extends AbstractActionController{
    private $db;

    public function __construct($db){
        $this->db = $db;
    }

    public function addBalanceAction(){
        $person = $this->params()->fromRoute('id', 0);

        $people = new People($this->db);
        $dataPerson = $people->list($person);

        $account = new Account($this->db, $person);
        $balance = $account->getBalance(); 
        
        return new ViewModel([
            'person' => $dataPerson,
            'balance' => $balance
        ]);
    }

    public function debitBalanceAction(){
        $person = $this->params()->fromRoute('id', 0);

        $people = new People($this->db);
        $dataPerson = $people->list($person);

        $account = new Account($this->db, $person);
        $balance = $account->getBalance(); 
        
        return new ViewModel([
            'person' => $dataPerson,
            'balance' => $balance
        ]);
    }

    public function transferBalanceAction(){
        $person = $this->params()->fromRoute('id', 0);

        $people = new People($this->db);
        $dataPerson = $people->list($person);
        $dataPeople = $people->list();

        $account = new Account($this->db, $person);
        $balance = $account->getBalance(); 
        
        return new ViewModel([
            'people' => $dataPeople,
            'person' => $dataPerson,
            'balance' => $balance
        ]);
    }

    public function creditAction(){
        $request = $this->getRequest();
        
        if($request->isPost()){
            $postFields = $this->params()->fromPost();
            
            $transaction = new Transaction($this->db, $postFields);
            $movement = new TransactionMovements($this->db);

            $response = $movement->credit($transaction);

            return new JsonModel($response);
        }
    }

    public function debitAction(){
        $request = $this->getRequest();
        
        if($request->isPost()){
            $postFields = $this->params()->fromPost();
            
            $transaction = new Transaction($this->db, $postFields);
            $movement = new TransactionMovements($this->db);

            $response = $movement->debit($transaction);

            return new JsonModel($response);
        }
    }

    public function transferAction(){
        $request = $this->getRequest();
        
        if($request->isPost()){
            $postFields = $this->params()->fromPost();

            $movement = new TransactionMovements($this->db);
            $response = $movement->transfer($postFields);

            $response['message'] = ($response['response'] ? "Saldo transferido com sucesso." : $response['message']);

            return new JsonModel($response);
        }
    }
}