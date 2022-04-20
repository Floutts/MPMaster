<?php

class Tache{
    private $db;
    private $insert;
    private $insertUtilisateurInTache;
    private $selectTachesByUser;
    private $selectTachesByProjet;

    public function __construct($db){
        $this->db=$db;
        $this->insert = $db->prepare("insert into tache(projet_id,libelle,duree) values(:projet,:libelle,:duree)");
        $this->insertUtilisateurInTache = $db->prepare("insert into tache_utilisateur(id_utilisateur, id_tache) values(:id_utilisateur, :id_tache)");
        $this->selectTachesByUser = $db->prepare("select t.libelle from tache t inner join tache_utilisateur tu on t.id_tache = tu.id_tache where tu.id_utilisateur=:idUtilisateur");
        $this->selectTachesByProjet = $db->prepare("select t.libelle from tache t where t.projet_id=:idProjet");
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

    public function selectTachesByUser($idUtilisateur){
        $this->selectTachesByUser->execute(array(':idUtilisateur'=>$idUtilisateur));
        if ($this->selectTachesByUser->errorCode()!=0){
            print_r($this->selectTachesByUser->errorInfo());
        }
        return $this->selectTachesByUser->fetchAll();
    }

    public function selectTachesByProjet($idProjet){
        $this->selectTachesByProjet->execute(array(':idProjet'=>$idProjet));
        if ($this->selectTachesByProjet->errorCode()!=0){
            print_r($this->selectTachesByProjet->errorInfo());
        }
        return $this->selectTachesByProjet->fetchAll();
    }


}