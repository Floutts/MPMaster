<?php

class Utilisateur{
    private $db;
    private $insert;
    private $connect;
    private $selectByEmail;
    private $select;

    public function __construct($db){
        $this->db=$db;
        $this->insert = $db->prepare("insert into utilisateur(email,mdp,nom,prenom,role) values(:email,:mdp,:nom,:prenom,:role)");
        $this->connect = $db->prepare("select email, mdp, role from utilisateur where email=:email");
        $this->selectByEmail = $db->prepare("select * from utilisateur where email=:email");
        $this->select = $db->prepare("select * from utilisateur");
    }

    public function insert($email, $mdp, $nom, $prenom,$role) { // Étape 3
        $r = true;
        $this->insert->execute(array(':email' => $email, ':mdp' => $mdp, ':nom' => $nom, ':prenom' => $prenom, ':role' => $role));
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

}