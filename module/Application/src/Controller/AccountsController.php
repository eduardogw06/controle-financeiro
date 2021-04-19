<?php

namespace Application\Controller;

use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Expression;
use Application\Model\People;
use Application\Model\Account;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Zend\Db\ResultSet\ResultSet;
use Zend\Mvc\Controller\AbstractActionController;

class AccountsController extends AbstractActionController{
    private $db;

    public function __construct($db){
        $this->db = $db;
    }

    public function checkBalanceAction(){
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

    public function checkStatementAction(){
        $person = $this->params()->fromRoute('id', 0);

        $people = new People($this->db);
        $dataPerson = $people->list($person);

        $account = new Account($this->db, $person);
        $balance = $account->getBalance();
        $statement = $account->getStatement();
        

        return new ViewModel([
            'data' => $statement,
            'person' => $dataPerson,
            'balance' => $balance
        ]);
    }

    public function getBalanceAction(){
        $request = $this->getRequest();
        
        if($request->isPost()){
            $params = $this->params()->fromPost();
            if(!empty($params['person'])){
                $people = new People($this->db);
                $dataPerson = $people->list($params['person']);

                if(!empty($dataPerson)){
                    $account = new Account($this->db, $params['person']);
                    $balance = $account->getBalance();

                    return new JsonModel([
                        'response' => true,
                        'person' => $dataPerson,
                        'balance' => $balance,
                        'lastQuery' => date("Y-m-d H:i:s")
                    ]);
                }else{
                    return new JsonModel([
                        'response' => false,
                        'message' => 'Nenhuma pessoa foi encontrada para o ID informado.'
                    ]);
                }
            }
        }
        
        return new JsonModel([
            'response' => false,
            'message' => 'Requisição inválida.'
        ]);
    }

    public function getStatementAction(){
        $request = $this->getRequest();
        
        if($request->isPost()){
            $params = $this->params()->fromPost();

            if(!empty($params['person'])){
                $people = new People($this->db);
                $dataPerson = $people->list($params['person']);

                if(!empty($dataPerson)){
                    $account = new Account($this->db, $params['person']);
                    $statement = $account->getStatement();

                    return new JsonModel([
                        'response' => true,
                        'person' => $dataPerson,
                        'statement' => $statement,
                        'lastQuery' => date("Y-m-d H:i:s")
                    ]);
                }else{
                    return new JsonModel([
                        'response' => false,
                        'message' => 'Nenhuma pessoa foi encontrada para o ID informado.'
                    ]);
                }
            }
        }
        
        return new JsonModel([
            'response' => false,
            'message' => 'Requisição inválida.'
        ]);
    }



}