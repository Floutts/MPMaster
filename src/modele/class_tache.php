<?php

class Tache{
    private $db;
    private $insert;
    private $insertUtilisateurInTache;

    public function __construct($db){
        $this->db=$db;
        $this->insert = $db->prepare("insert into tache(projet_id,libelle,duree) values(:projet,:libelle,:duree)");
        $this->insertUtilisateurInTache = $db->prepare("insert into tache_utilisateur(id_utilisateur, id_tache) values(:id_utilisateur, :id_tache)");

    }

    public function insert($projet,$libelle,$duree) { // Étape 3
        $r = true;
        $this->insert->execute(array(':projet'=>$projet,':libelle'=>$libelle,':duree'=>$duree));
        if ($this->insert->errorCode() != 0) {
            print_r($this->insert->errorInfo());
            $r = false;
        } return $r;
    }

    public function insertUtilisateurInTache($id_utilisateur,$id_tache) { // Étape 3
        $r = true;
        $this->insertUtilisateurInTache->execute(array(':id_utilisateur'=>$id_utilisateur,':id_tache'=>$id_tache));
        if ($this->insertUtilisateurInTache->errorCode() != 0) {
            print_r($this->insertUtilisateurInTache->errorInfo());
            $r = false;
        } return $r;
    }


}