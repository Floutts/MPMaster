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
                $_SESSION['role'] = $unUtilisateur['role'];
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
    if (isset($_POST['btInscrire'])){
        //$nbUnique = uniqid();
        $email = $_POST['email'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $mdp = $_POST['password'];
        $confirmation =$_POST['confirmation'];
        if($_GET['idrole'] == '1'){
           $role = 'ROLE_ENTREPRISE';
        }elseif($_GET['idrole'] == '2'){
            $role = 'ROLE_CHEF';
        }elseif($_GET['idrole'] == '3'){
            $role = 'ROLE_DEVELOPPEUR';
        }else{
            $role = 'ROLE_UNKNOWN';
        }
        $form['valide'] = true;
        if ($mdp!=$confirmation){
            $form['valide'] = false;
            $form['message'] = 'Les mots de passe sont différents';
        }
        else{
            $utilisateur = new Utilisateur($db);
            $exec = $utilisateur->insert($email, password_hash($mdp,PASSWORD_DEFAULT),$nom, $prenom, $role);
            if (!$exec){
                $form['valide'] = false;
                $form['message'] = 'Problème d\'insertion dans la table utilisateur ';
            }

        }
        $form['email'] = $email;
        $form['nom'] = $nom;
        $form['prenom'] = $prenom;
    }
    echo $twig->render('inscription.html.twig',array('form'=>$form));
}



