<?php

class Utilisateur{
    private $db;
    private $insert;
    private $connect;
    private $selectByEmail;
    private $select;
    private $selectByEntreprise;
    private $delete;

    public function __construct($db){
        $this->db=$db;
        $this->insert = $db->prepare("insert into utilisateur(email,mdp,nom,prenom,id_role, id_entreprise) values(:email,:mdp,:nom,:prenom,:role,:entreprise)");
        $this->connect = $db->prepare("select email, mdp, id_role, id_entreprise from utilisateur where email=:email");
        $this->selectByEmail = $db->prepare("select * from utilisateur where email=:email");
        $this->select = $db->prepare("select * from utilisateur");
        $this->selectByEntreprise = $db->prepare("select * from utilisateur where id_entreprise=:id_entreprise");
        $this->delete = $db->prepare("DELETE FROM utilisateur WHERE id_utilisateur = :id_utilisateur");
    }

    public function insert($email, $mdp, $nom, $prenom,$role, $entreprise) { // Étape 3
        $r = true;
        $this->insert->execute(array(':email' => $email, ':mdp' => $mdp, ':nom' => $nom, ':prenom' => $prenom, ':role' => $role, ':entreprise' => $entreprise));
        if ($this->insert->errorCode() != 0) {
            print_r($this->insert->errorInfo());
            $r = false;
        } return $r;
    }

    public function connect($email){
        $this->connect->execute(array(':email'=>$email));
        if ($this->connect->errorCode()!=0){
            print_r($this->connect->errorInfo());
        }
        return $this->connect->fetch();
    }

    public function selectByEmail($email){
        $this->selectByEmail->execute(array(':email'=>$email));
        if ($this->selectByEmail->errorCode()!=0){
            print_r($this->selectByEmail->errorInfo());
        }
        return $this->selectByEmail->fetch();
    }

    public function select(){
        $this->select->execute();
        if ($this->select->errorCode()!=0){
            print_r($this->select->errorInfo());
        }
        return $this->select->fetchAll();
    }
    public function selectByEntreprise($id_entreprise){
        $this->selectByEntreprise->execute(array(':id_entreprise'=>$id_entreprise));
        if ($this->selectByEntreprise->errorCode()!=0){
            print_r($this->selectByEntreprise->errorInfo());
        }
        return $this->selectByEntreprise->fetchAll();
    }

    public function delete($id_utilisateur){
        $this->delete->execute(array(':id_utilisateur'=>$id_utilisateur));
        if ($this->delete->errorCode()!=0){
            print_r($this->delete->errorInfo());
        }
        return $this->delete->fetch();
    }
}