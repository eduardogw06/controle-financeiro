<?php

namespace Application\Model;

use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;

class People{
    private $db;
    private $name;
    private $cpf;

    public function __construct($db, $person = null){
        $this->db = $db;

        if(!empty($person)){
            $this->setName($person['name'] ?? '');
            $this->setCpf($person['cpf'] ?? '');
        }
    }

    public function getName(){
        return $this->name;
    }

    public function setName($name){
        $this->name = $name;
    }

    public function getCpf(){
        return $this->cpf;
    }

    public function setCpf($cpf){
        $this->cpf = $cpf;
    }

    public function save(){
        try {
            $sql = new Sql($this->db);

            $insert = $sql->insert();
            $insert->into("people");
            $insert->columns(array(
                "name",
                "cpf",
            ));
            $insert->values([
                "name" => $this->getName(),
                "cpf" => $this->getCpf(),
            ]);
            $stm = $sql->prepareStatementForSqlObject($insert);
            $res = $stm->execute();
            
        }catch(\Exception $ex){
            return [
                'response' => false,
                'message' => "Ocorreu um erro de conexão com o banco de dados, favor contatar o administrador.",
                'error' => $ex->getMessage()
            ];
        }

        return [
            'response' => true,
            'message' => "Cadastro efetuado com sucesso."
        ];
    }

    public function list($id = null){
        $sql = new Sql($this->db);

        $select = $sql->select();
        $select->from('people');
        if(!empty($id)){
            $select->where(array('id' => $id ));
        }
        
        $stm = $sql->prepareStatementForSqlObject($select);
        $res = $stm->execute();

        $resultSet = new ResultSet;
        $resultSet->initialize($res);

        $data = $resultSet->toArray();

        if(!empty($data)){
            return (!empty($id)) ? $data[0] : $data; 
        } 
    }
}