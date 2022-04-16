<?php

class Tache{
    private $db;
    private $insert;

    public function __construct($db){
        $this->db=$db;
        $this->insert = $db->prepare("insert into tache(projet_id,libelle,duree) values(:projet,:libelle,:duree)");
    }

    public function insert($projet,$libelle,$duree) { // Étape 3
        $r = true;
        $this->insert->execute(array(':projet'=>$projet,':libelle'=>$libelle,':duree'=>$duree));
        if ($this->insert->errorCode() != 0) {
            print_r($this->insert->errorInfo());
            $r = false;
        } return $r;
    }

}