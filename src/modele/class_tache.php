<?php

class Tache{
    private $db;
    private $insert;

    public function __construct($db){
        $this->db=$db;
        $this->insert = $db->prepare("insert into tache(libelle,duree) values(:libelle,:duree)");
    }

    public function insert($libelle,$duree) { // Étape 3
        $r = true;
        $this->insert->execute(array(':libelle'=>$libelle,':duree'=>$duree));
        if ($this->insert->errorCode() != 0) {
            print_r($this->insert->errorInfo());
            $r = false;
        } return $r;
    }

}