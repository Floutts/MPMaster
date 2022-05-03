<?php

function actionAjoutProjet($twig, $db) {
    $form = array();
    $idEntreprise = isset($_SESSION['entreprise'])? $_SESSION['entreprise'] : false;
    $projet = new Projet($db);
    $utilisateur = new Utilisateur($db);
    $listeUtilisateur = $utilisateur->selectByEntreprise($idEntreprise);
    $chef = $projet->selectChefByEntreprise($idEntreprise);
    if(sizeof($listeUtilisateur) != 0){
        if(isset($_POST['btAjoutProjet'])){
            $exec = $projet->insert($_POST['libelle'], intval($idEntreprise), intval($_POST['chef']));
            $idProjet = $db->lastinsertid();
            if($exec){
                if(isset($_POST['utilsateurProjet'])){
                    $utilisateurProjet = $_POST['utilsateurProjet'];
                    foreach($utilisateurProjet as $user){
                       $projet->insertUtilisateurInProjet(floatval($utilisateurProjet[0]), $idProjet);
                    }
                    $form['valide'] = true;
                    $form['message'] = "Projet ajouté";
                }else{
                    $utilisateurProjet = array();
                    $form['valide'] = false;
                    $form['message'] = "Attention, aucun utilisateur n'est assigné au projet ";
                }
                
            }else{
                $form['valide'] = false;
                $form['message'] = "Erreur d'ajout du projet";
            }
        }
    }else{
        $form['valide'] = false;
        $form['message'] = "Vous ne pouvez pas ajouter de projet tant qu'aucun participant n'est ajouté";
    }
  

    echo $twig->render('ajoutProjet.html.twig', array('form'=>$form, 'chefs'=>$chef, 'utilisateurs'=>$listeUtilisateur));
}

function actionListeProjets($twig, $db) {
    $form = array();
    var_dump($_SESSION);
    $projet = new Projet($db);
    $utilisateur = new Utilisateur($db);
    $unUtilisateur = $utilisateur->selectByEmail($_SESSION['login']);
    if($unUtilisateur['id_role'] <= 2){
        $listeProjet = $projet->selectByEntreprise($_SESSION['entreprise']);
    }
    else{
        $listeProjet = $projet->selectProjetById($unUtilisateur['id_utilisateur']);
    }
    echo $twig->render('listeProjets.html.twig', array('form'=>$form, 'projets'=>$listeProjet));
}

?>