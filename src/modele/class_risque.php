<?php

class Risque{
    private $db;
    private $insert;
    private $selectRisqueByProjet;


    public function __construct($db){
        $this->db=$db;
        $this->insert = $db->prepare("insert into projet(libelle, id_entreprise) values(:libelle, :id_entreprise)");
        $this->selectRisqueByProjet = $db->prepare("SELECT projet_type_risque.*, type_risque.libelle as libelleRisque, type_risque.id_classe_risque, classe_risque.libelle as libelleCRisque FROM projet_type_risque, type_risque, classe_risque where projet_type_risque.id_projet = :idProjet AND type_risque.id_type_risque = projet_type_risque.id_type_risque AND type_risque.id_classe_risque = classe_risque.id_classe_risque ");
        }

    public function insert($libelle, $idEntreprise) {
        $r = true;
        $this->insert->execute(array(':libelle'=>$libelle, ':id_entreprise'=>$idEntreprise));
        if ($this->insert->errorCode() != 0) {
            print_r($this->insert->errorInfo());
            $r = false;
        } return $r;
    }

    public function selectRisqueByProjet($idProjet){
        $this->selectRisqueByProjet->execute(array(':idProjet'=>$idProjet));
        if ($this->selectRisqueByProjet->errorCode()!=0){
            print_r($this->selectRisqueByProjet->errorInfo());
        }
        return $this->selectRisqueByProjet->fetchAll();
    }
}