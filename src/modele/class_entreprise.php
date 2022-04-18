<?php

class Entreprise{
    private $db;
    private $insert;
    private $selectById;

    public function __construct($db){
        $this->db=$db;
        $this->insert = $db->prepare("insert into entreprise(libelle) values(:libelle)");
        $this->selectById = $db->prepare("SELECT * FROM entreprise WHERE id_entreprise = :id_entreprise");
    }

    public function insert($libelle) { // Étape 3
        $r = true;
        $this->insert->execute(array(':libelle'=>$libelle));
        if ($this->insert->errorCode() != 0) {
            print_r($this->insert->errorInfo());
            $r = false;
        } return $r;
    }

    public function selectById($id_entreprise){
        $this->selectById->execute(array(':id_entreprise'=>$id_entreprise));
        if ($this->selectById->errorCode()!=0){
            print_r($this->selectById->errorInfo());
        }
        return $this->selectById->fetch();
    }
}