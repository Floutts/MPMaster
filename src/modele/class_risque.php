<?php

class Risque{
    private $db;
    private $insert;
    private $selectRisqueByProjet;
    private $selectTypeRisque;
    private $delete;


    public function __construct($db){
        $this->db=$db;
        $this->insert = $db->prepare("INSERT INTO projet_type_risque(id_projet,id_type_risque,probabilite,severite,cout_reduc_risque,moyen_detection,mesure_correction) values(:id_projet,:id_type_risque,:probabilite,:severite,:cout_reduc_risque,:moyen_detection,:mesure_correction)");
        $this->selectRisqueByProjet = $db->prepare("SELECT projet_type_risque.*, type_risque.libelle as libelleRisque, type_risque.id_classe_risque, classe_risque.libelle as libelleCRisque FROM projet_type_risque, type_risque, classe_risque where projet_type_risque.id_projet = :idProjet AND type_risque.id_type_risque = projet_type_risque.id_type_risque AND type_risque.id_classe_risque = classe_risque.id_classe_risque ");
        $this->selectTypeRisque = $db->prepare("SELECT * FROM type_risque");
        $this->delete = $db->prepare("DELETE FROM projet_type_risque WHERE id_projet = :idProjet AND id_type_risque = :idRisque");
    }

    public function insert($id_projet,$id_type_risque,$probabilite,$severite,$cout_reduc_risque,$moyen_detection,$mesure_correction) {
        $r = true;
        $this->insert->execute(array(':id_projet'=>$id_projet,':id_type_risque'=>$id_type_risque,':probabilite'=>$probabilite,':severite'=>$severite,':cout_reduc_risque'=>$cout_reduc_risque,':moyen_detection'=>$moyen_detection, ':mesure_correction'=>$mesure_correction));
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

    public function selectTypeRisque(){
        $this->selectTypeRisque->execute();
        if ($this->selectTypeRisque->errorCode()!=0){
            print_r($this->selectTypeRisque->errorInfo());
        }
        return $this->selectTypeRisque->fetchAll();
    }
    public function delete($idProjet, $idRisque){
        $r = true;
        $this->delete->execute(array(':idProjet'=>$idProjet, ':idRisque'=>$idRisque));
        if ($this->delete->errorCode()!=0){
            print_r($this->delete->errorInfo());
            $r=false;
        }
        return $r;
    }
}