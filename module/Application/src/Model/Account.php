<?php

namespace Application\Model;

use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;
use \Zend\Db\Sql\Expression;
use Zend\Db\ResultSet\ResultSet;

class Account{
    const DEBIT = 1;
    const CREDIT = 2;

    private $db;

    public function __construct($db, $person){
        $this->db = $db;
        $this->person = $person;
    }

    public function getBalance(){
        if(!empty($this->person)){
            $sql = new Sql($this->db);

            $select = $sql->select();
            $select->from('transactions');
            
            $where = new Where();
        $where->nest()
            ->nest()
                ->equalTo('from', $this->person)
                ->equalTo('type', $this::DEBIT)
            ->unnest()
            ->or
            ->nest()
                ->equalTo('to', $this->person)
                ->equalTo('type', $this::CREDIT)
            ->unnest()
        ->unnest();
        $select->where($where);
            
            $stm = $sql->prepareStatementForSqlObject($select);
            $res = $stm->execute();

            $resultSet = new ResultSet;
            $resultSet->initialize($res);

            $balance = $resultSet->toArray();
            
            return $this->calculateBalance($balance, $this->person);
        }
    }

    public function calculateBalance($balance, $person){
        $total = 0;
        
        if(!empty($balance)){
            foreach($balance as $movement){                
                if($movement['from'] == $this->person){
                    $total -= $movement['value'];
                }
            
                if($movement['to'] == $this->person){
                    $total += $movement['value'];
                } 
            }
        }
        return $total;
    }

    public function getStatement(){
        $sql = new Sql($this->db);

        $select = $sql->select();
        $select->from(array('t' => 'transactions'));
        $select->join(array('p' => 'people'), 'p.id = t.from', array('from_cpf' => 'cpf', 'from_name' => 'name'), $select::JOIN_LEFT);
        $select->join(array('p1' => 'people'), 'p1.id = t.to', array('to_cpf' => 'cpf', 'to_name' => 'name'), $select::JOIN_LEFT);
        $select->join(array('tt' => 'transaction_types'), 'tt.id = t.type', array('type_id' => 'id', 'type' => 'name'));

        $select->columns([
            'transaction_id' => new Expression('p.id'),
            'date',
            'value'
           
        ]);

        $where = new Where();
        $where->nest()
            ->nest()
                ->equalTo('t.from', $this->person)
                ->equalTo('t.type', $this::DEBIT)
            ->unnest()
            ->or
            ->nest()
                ->equalTo('t.to', $this->person)
                ->equalTo('t.type', $this::CREDIT)
            ->unnest()
        ->unnest();
        $select->where($where);
        $stm = $sql->prepareStatementForSqlObject($select);
        $res = $stm->execute();

        $resultSet = new ResultSet;
        $resultSet->initialize($res);
        
        $data = $resultSet->toArray();
        
        return $data;
    }
}
