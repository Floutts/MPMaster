<?php

class Anomalie{
    private $db;
    private $insert;
    private $selectNumberAnomalieByProjet;
    private $selectAnomalieByProjet;
    private $modifyStatut;
    private $selectByEtat;

    public function __construct($db){
        $this->db=$db;
        $this->insert = $db->prepare("INSERT INTO anomalie(id_projet,num_anomalie,emplacement,etat,scenario,date_anomalie,auteur) values(:id_projet,:num_anomalie,:emplacement,:etat,:scenario,:date_anomalie,:auteur)");
        $this->selectNumberAnomalieByProjet = $db->prepare("SELECT count(*) as nbAnomalie FROM anomalie WHERE id_projet=:id_projet");
        $this->selectAnomalieByProjet = $db->prepare("SELECT * FROM anomalie WHERE id_projet=:id_projet");
        $this->modifyStatut = $db->prepare("UPDATE anomalie SET etat = :statut WHERE id = :id");
        $this->selectByEtat = $db->prepare("SELECT COUNT(*) as nb FROM anomalie WHERE etat = :num_etat AND id_projet = :id_projet");
    }
    
    public function modifyStatut($id, $statut) {
        $r = true;
        $this->modifyStatut->execute(array(':id'=> $id, ':statut'=>$statut));
        if ($this->modifyStatut->errorCode() != 0) {
            print_r($this->modifyStatut->errorInfo());
            $r = false;
        } return $r;
    }
    public function insert($id_projet,$num_anomalie,$emplacement,$etat,$scenario,$date_anomalie,$auteur) {
        $r = true;
        $this->insert->execute(array(':id_projet'=>$id_projet,':num_anomalie'=>$num_anomalie,':emplacement'=>$emplacement,':etat'=>$etat,':scenario'=>$scenario,':date_anomalie'=>$date_anomalie, ':auteur'=>$auteur));
        if ($this->insert->errorCode() != 0) {
            print_r($this->insert->errorInfo());
            $r = false;
        } return $r;
    }

    
    public function selectNumberAnomalieByProjet($id_projet){
        $this->selectNumberAnomalieByProjet->execute(array(':id_projet'=>$id_projet));
        if ($this->selectNumberAnomalieByProjet->errorCode()!=0){
            print_r($this->selectNumberAnomalieByProjet->errorInfo());
        }
        return $this->selectNumberAnomalieByProjet->fetch();
    }

    public function selectByEtat($num_etat,$id_projet){
        $this->selectByEtat->execute(array(':num_etat'=>$num_etat,":id_projet"=>$id_projet));
        if ($this->selectByEtat->errorCode()!=0){
            print_r($this->selectByEtat->errorInfo());
        }
        return $this->selectByEtat->fetch();
    }

    public function selectAnomalieByProjet($id_projet){
        $this->selectAnomalieByProjet->execute(array(':id_projet'=>$id_projet));
        if ($this->selectAnomalieByProjet->errorCode()!=0){
            print_r($this->selectAnomalieByProjet->errorInfo());
        }
        return $this->selectAnomalieByProjet->fetchAll();
    }
}
