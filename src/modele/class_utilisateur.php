<?php

class Utilisateur{
    private $db;
    private $insert;
    private $connect;
    private $selectByEmail;
    private $select;
    private $selectByEntreprise;
    private $selectAllByEntreprise;
    private $selectByProjet;
    private $delete;

    public function __construct($db){
        $this->db=$db;
        $this->insert = $db->prepare("insert into utilisateur(email,mdp,nom,prenom,id_role, id_entreprise) values(:email,:mdp,:nom,:prenom,:role,:entreprise)");
        $this->connect = $db->prepare("select email, mdp, id_role, id_entreprise from utilisateur where email=:email");
        $this->selectByEmail = $db->prepare("select * from utilisateur where email=:email");
        $this->select = $db->prepare("select * from utilisateur");
        $this->selectByEntreprise = $db->prepare("select u.* from utilisateur u where id_entreprise=:id_entreprise and id_role = 4");
        $this->selectAllByEntreprise = $db->prepare("select u.*, r.* from utilisateur u inner join role r on r.id_role = u.id_role where id_entreprise=:id_entreprise");
        $this->selectByProjet = $db->prepare("select distinct u.* from utilisateur u inner join projet_utilisateur pu on u.id_utilisateur = pu.id_utilisateur inner join projet p on pu.id_projet = p.id_projet and p.id_projet = :idProjet");
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

    public function selectByProjet($idProjet){
        $this->selectByProjet->execute(array(':idProjet'=>$idProjet));
        if ($this->selectByProjet->errorCode()!=0){
            print_r($this->selectByProjet->errorInfo());
        }
        return $this->selectByProjet->fetchAll();
    }

    public function selectByEntreprise($id_entreprise){
        $this->selectByEntreprise->execute(array(':id_entreprise'=>$id_entreprise));
        if ($this->selectByEntreprise->errorCode()!=0){
            print_r($this->selectByEntreprise->errorInfo());
        }
        return $this->selectByEntreprise->fetchAll();
    }

    public function selectAllByEntreprise($id_entreprise){
        $this->selectAllByEntreprise->execute(array(':id_entreprise'=>$id_entreprise));
        if ($this->selectAllByEntreprise->errorCode()!=0){
            print_r($this->selectAllByEntreprise->errorInfo());
        }
        return $this->selectAllByEntreprise->fetchAll();
    }

    public function delete($id_utilisateur){
        $this->delete->execute(array(':id_utilisateur'=>$id_utilisateur));
        if ($this->delete->errorCode()!=0){
            print_r($this->delete->errorInfo());
        }
        return $this->delete->fetch();
    }
}