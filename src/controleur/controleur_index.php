<?php

function actionAccueil($twig) {
    $form = array();
    echo $twig->render('index.html.twig', array());
}

function actionMentions($twig){
    echo $twig->render('mentions.html.twig',array());
}

function actionApropos($twig){
    echo $twig->render('apropos.html.twig',array());
}

function actionMaintenance($twig){
    echo $twig->render('maintenance.html.twig',array());
}

function actionAbonnements($twig){
    echo $twig->render('abonnement.html.twig',array());
}


function actionDeconnexion($twig){
    session_unset();
    session_destroy();
    header("Location:index.php");
}

function actionConnexion($twig,$db){
    $form = array();
    $form['valide'] = true;
    if (isset($_POST['btConnexion'])){
        $email = $_POST['email'];
        $mdp = $_POST['mdp'];
        $utilisateur = new Utilisateur($db);
        $unUtilisateur = $utilisateur->connect($email);
        if ($unUtilisateur!=null){
            if(!password_verify($mdp,$unUtilisateur['mdp'])){
                $form['valide'] = false;
                $form['message'] = 'Login ou mot de passe incorrect';
            }
            else{  
                $_SESSION['login'] = $email;
                $_SESSION['role'] = $unUtilisateur['id_role'];
                $_SESSION['entreprise'] = $unUtilisateur['id_entreprise'];
                header("Location:index.php");
            }
        }
        else{
            $form['valide'] = false;
            $form['message'] = 'Login ou mot de passe incorrect';

        }
    }
    echo $twig->render('connexion.html.twig',array('form'=>$form));
}

function actionInscription($twig,$db){
    $form = array();
    $utilisateur = new Utilisateur($db);
    if(isset($_GET['idEntreprise'])){
        $form['parapage'] = 1;
        }
    if (isset($_POST['btInscrire'])){
        //$nbUnique = uniqid();
        $email = $_POST['email'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $mdp = $_POST['password'];
        $confirmation =$_POST['confirmation'];
        $unUtilisateur = $utilisateur->selectByEmail($email);
        if(isset($_POST['role'])){
            if($_POST['role']){
                $role = 3;
            }else{
                $role = 4;
            }
        }else{
            $role = 2;
        }
        $entreprise = isset($_SESSION['entreprise'])? $_SESSION['entreprise'] : $_GET['idEntreprise'];
        $form['valide'] = true;
        if ($mdp!=$confirmation){
            $form['valide'] = false;
            $form['message'] = 'Les mots de passe sont différents';
        }
        else{
            if($unUtilisateur == false){
                $exec = $utilisateur->insert($email, password_hash($mdp,PASSWORD_DEFAULT),$nom, $prenom, $role, $entreprise);
                if (!$exec){
                    $form['valide'] = false;
                    $form['message'] = 'Problème d\'insertion dans la table utilisateur ';
                }
            }else{
                $form['valide'] = false;
                $form['message'] = 'Utilisateur déjà inscrit ';
            }
          

        }
        $form['email'] = $email;
        $form['nom'] = $nom;
        $form['prenom'] = $prenom;
    }
    echo $twig->render('inscription.html.twig',array('form'=>$form));
}


function actionListeUtilisateur($twig, $db) {
    $form = array();
    $utilisateur = new Utilisateur($db);
    $listeUtilisateur = $utilisateur->selectAllByEntreprise($_SESSION['entreprise']);
    if(isset($_GET['idUtilisateur'])){
        $utilisateur->delete($_GET['idUtilisateur']);
    }
    echo $twig->render('listeUtilisateurs.html.twig', array('form'=>$form, 'utilisateurs'=>$listeUtilisateur));
}


