<?php

class Projet{
    private $db;
    private $insert;
    private $selectChefByEntreprise;
    private $selectByUser;

    public function __construct($db){
        $this->db=$db;
        $this->insert = $db->prepare("insert into projet(libelle, id_entreprise) values(:libelle, :id_entreprise)");
        $this->selectChefByEntreprise = $db->prepare("SELECT DISTINCT u.* FROM utilisateur u inner join entreprise e on e.id_entreprise = :idEntreprise inner join role r on u.id_role = 3");
        $this->selectByUser = $db->prepare("SELECT v.id_utilisateur, v.id_projet, p.libelle FROM projet_utilisateur v INNER JOIN utilisateur u ON u.id_utilisateur = v.id_utilisateur INNER JOIN projet p ON p.id_projet = v.id_projet WHERE v.id_utilisateur = :id_user");
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
        return $this->selectChefByEntreprise->fetch();
    }

    public function selectByUser($id_user){
        $this->selectByUser->execute(array(':id_user'=>$id_user));
        if ($this->selectByUser->errorCode()!=0){
            print_r($this->selectByUser->errorInfo());
        }
        return $this->selectByUser->fetchAll();
    }
}