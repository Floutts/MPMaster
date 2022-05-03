<?php

class Anomalie{
    private $db;
    private $insert;
    private $selectNumberAnomalieByProjet;
    private $selectAnomalieByProjet;

    public function __construct($db){
        $this->db=$db;
        $this->insert = $db->prepare("INSERT INTO anomalie(id_projet,num_anomalie,emplacement,etat,scenario,date_anomalie,auteur) values(:id_projet,:num_anomalie,:emplacement,:etat,:scenario,:date_anomalie,:auteur)");
        $this->selectNumberAnomalieByProjet = $db->prepare("SELECT count(*) as nbAnomalie FROM anomalie WHERE id_projet=:id_projet");
        $this->selectAnomalieByProjet = $db->prepare("SELECT * FROM anomalie WHERE id_projet=:id_projet");
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

    public function selectAnomalieByProjet($id_projet){
        $this->selectAnomalieByProjet->execute(array(':id_projet'=>$id_projet));
        if ($this->selectAnomalieByProjet->errorCode()!=0){
            print_r($this->selectAnomalieByProjet->errorInfo());
        }
        return $this->selectAnomalieByProjet->fetchAll();
    }
}
