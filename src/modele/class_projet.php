<?php

class Projet{
    private $db;
    private $insert;
    private $selectChefByEntreprise;
    private $selectProjetById;

    public function __construct($db){
        $this->db=$db;
        $this->insert = $db->prepare("insert into projet(libelle, id_entreprise) values(:libelle, :id_entreprise)");
        $this->selectChefByEntreprise = $db->prepare("SELECT DISTINCT u.* FROM utilisateur u inner join entreprise e on e.id_entreprise = :idEntreprise inner join role r on u.id_role = 3");
        $this->selectProjetById = $db->prepare("SELECT * FROM projet where id_chef_projet = :idUtilisateur");

    }

    public function insert($libelle, $idEntreprise) {
        $r = true;
        $this->insert->execute(array(':libelle'=>$libelle, ':id_entreprise'=>$idEntreprise));
        if ($this->insert->errorCode() != 0) {
            print_r($this->insert->errorInfo());
            $r = false;
        } return $r;
    }

    
    public function selectChefByEntreprise($idEntreprise){
        $this->selectChefByEntreprise->execute(array(':idEntreprise'=>$idEntreprise));
        if ($this->selectChefByEntreprise->errorCode()!=0){
            print_r($this->selectChefByEntreprise->errorInfo());
        }
        return $this->selectChefByEntreprise->fetchAll();
    }

    public function selectProjetById($idUtilisateur){
        $this->selectProjetById->execute(array(':idUtilisateur'=>$idUtilisateur));
        if ($this->selectProjetById->errorCode()!=0){
            print_r($this->selectProjetById->errorInfo());
        }
        return $this->selectProjetById->fetchAll();
    }


}