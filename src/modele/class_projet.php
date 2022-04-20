<?php

class Projet{
    private $db;
    private $insert;
    private $insertUtilisateurInProjet;
    private $selectChefByEntreprise;
    private $selectProjetById;
    private $selectByUser;
    private $selectByEntreprise;

    public function __construct($db){
        $this->db=$db;
        $this->insert = $db->prepare("insert into projet(libelle, id_entreprise, id_chef_projet) values(:libelle, :id_entreprise, :id_chef_projet)");
        $this->insertUtilisateurInProjet = $db->prepare("insert into projet_utilisateur(id_utilisateur, id_projet) values(:id_utilisateur, :id_projet)");
        $this->selectChefByEntreprise = $db->prepare("SELECT DISTINCT u.* FROM utilisateur u inner join entreprise e on e.id_entreprise = :idEntreprise inner join role r on u.id_role = 3");
        $this->selectProjetById = $db->prepare("SELECT * FROM projet where id_chef_projet = :idUtilisateur");
        $this->selectByUser = $db->prepare("SELECT v.id_utilisateur, v.id_projet, p.libelle FROM projet_utilisateur v INNER JOIN utilisateur u ON u.id_utilisateur = v.id_utilisateur INNER JOIN projet p ON p.id_projet = v.id_projet WHERE v.id_utilisateur = :id_user");
        $this->selectByUser = $db->prepare("SELECT v.id_utilisateur, v.id_projet, p.libelle FROM projet_utilisateur v INNER JOIN utilisateur u ON u.id_utilisateur = v.id_utilisateur INNER JOIN projet p ON p.id_projet = v.id_projet WHERE v.id_utilisateur = :id_user");
        $this->selectByEntreprise = $db->prepare("SELECT id_projet, libelle FROM projet WHERE id_entreprise = :id_entreprise");
    }

    public function insert($libelle, $idEntreprise, $id_chef_projet) {
        $r = true;
        $this->insert->execute(array(':libelle'=>$libelle, ':id_entreprise'=>$idEntreprise,  ':id_chef_projet'=>$id_chef_projet));
        if ($this->insert->errorCode() != 0) {
            print_r($this->insert->errorInfo());
            $r = false;
        } return $r;
    }

    public function insertUtilisateurInProjet($id_utilisateur, $id_projet) {
        $r = true;
        $this->insertUtilisateurInProjet->execute(array(':id_utilisateur'=>$id_utilisateur, ':id_projet'=>$id_projet));
        if ($this->insertUtilisateurInProjet->errorCode() != 0) {
            print_r($this->insertUtilisateurInProjet->errorInfo());
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


    public function selectByUser($id_user){
        $this->selectByUser->execute(array(':id_user'=>$id_user));
        if ($this->selectByUser->errorCode()!=0){
            print_r($this->selectByUser->errorInfo());
        }
        return $this->selectByUser->fetchAll();
    }

    public function selectByEntreprise($id_entreprise){
        $this->selectByEntreprise->execute(array(':id_entreprise'=>$id_entreprise));
        if ($this->selectByEntreprise->errorCode()!=0){
            print_r($this->selectByEntreprise->errorInfo());
        }
        return $this->selectByEntreprise->fetchAll();
    }
}