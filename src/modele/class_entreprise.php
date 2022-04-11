<?php

class Entreprise{
    private $db;
    private $insert;

    public function __construct($db){
        $this->db=$db;
        $this->insert = $db->prepare("insert into entreprise(libelle) values(:libelle)");
    }

    public function insert($libelle) { // Étape 3
        $r = true;
        $this->insert->execute(array(':libelle'=>$libelle));
        if ($this->insert->errorCode() != 0) {
            print_r($this->insert->errorInfo());
            $r = false;
        } return $r;
    }

}