<?php

class Pert{
    private $db;
    private $selectTachePreced;
    private $selectTaches;

    public function __construct($db){
        $this->db=$db;
        $this->selectTachePreced = $db->prepare("select * from tache_precedente");
        $this->selectTaches = $db->prepare("select * from tache");
    }

    public function selectTachePreced() { 
        $r = true;
            $this->selectTachePreced->execute();
            if ($this->selectTachePreced->errorCode()!=0){
                print_r($this->selectTachePreced->errorInfo());}
                return $this->selectTachePreced->fetchAll();
        }
    
    public function selectTaches() { 
        $r = true;
            $this->selectTaches->execute();
            if ($this->selectTaches->errorCode()!=0){
                print_r($this->selectTaches->errorInfo());}
                return $this->selectTaches->fetchAll();
        }
    }


