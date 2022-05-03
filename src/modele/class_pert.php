<?php

class Pert{
    private $db;
    private $selectTachePreced;
    private $selectTaches;
    private $selectTachesPrecedByTache;
    private $selectTachesSuivantesByTache;

    public function __construct($db){
        $this->db=$db;
        $this->selectTachePreced = $db->prepare("select * from tache_precedente");
        $this->selectTaches = $db->prepare("SELECT * FROM tache WHERE projet_id = :idProjet");
        $this->selectTachesSuivantesByTache = $db->prepare("select * from tache_precedente where id_tache_precedente = :id_tache ");
    }

    public function selectTachePreced() { 
        $r = true;
            $this->selectTachePreced->execute();
            if ($this->selectTachePreced->errorCode()!=0){
                print_r($this->selectTachePreced->errorInfo());}
                return $this->selectTachePreced->fetchAll();
    }
    
    public function selectTaches($idprojet) { 
        $r = true;
            $this->selectTaches->execute(array(":idProjet"=>$idprojet));
            if ($this->selectTaches->errorCode()!=0){
                print_r($this->selectTaches->errorInfo());}
                return $this->selectTaches->fetchAll();
    }

    public function selectTachesSuivantesByTache($id_tache) { 
        $r = true;
            $this->selectTachesSuivantesByTache->execute(array(":id_tache"=>$id_tache));
            if ($this->selectTachesSuivantesByTache->errorCode()!=0){
                print_r($this->selectTachesSuivantesByTache->errorInfo());}
                return $this->selectTachesSuivantesByTache->fetchAll();
    }


    public function selectTachesPrecedByTache($db,$id_tache,$not_in) { 
        $this->selectTachesPrecedByTache = $db->prepare('select * from tache_precedente where id_tache = '.$id_tache.' AND id_tache_precedente not in ('.$not_in.')');
        $r = true;
            $this->selectTachesPrecedByTache->execute();
            if ($this->selectTachesPrecedByTache->errorCode()!=0){
                print_r($this->selectTachesPrecedByTache->errorInfo());}
                return $this->selectTachesPrecedByTache->fetchAll();
    }

}


