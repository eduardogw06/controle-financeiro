<?php

namespace Application\Model;

class Transaction{
    private $db;
    private $from;
    private $to;
    private $value;

    public function __construct($db, $data){
        $this->db = $db;
        
        $this->setFrom($data['from'] ?? null);
        $this->setTo($data['to'] ?? null);
        $this->setValue($data['value'] ?? null);
    }
    

    public function getFrom(){
        return $this->from;
    }

    public function setFrom($from){
        $this->from = $this->validatePerson($from);
    }

    public function getTo(){
        return $this->to;
    }

    public function setTo($to){
        $this->to = $this->validatePerson($to);

    }

    private function validatePerson($person){
        if(!empty($person)){
            $people = new People($this->db);
            $dataPeople = $people->list($person);
            
            if(!empty($dataPeople)){
                return $dataPeople['id'];
            }
        }
        return null;
    }


    public function getValue(){
        return $this->value;
    }

    public function setValue($value){
        $this->value = number_format($value, 2, ".", "");
    }
}