<?php

function actionAjoutProjet($twig, $db) {
    $form = array();
    
    echo '<script> confirm("Attention, votre projet ne contient pas d\'utilisateur"); </script>';
    $idEntreprise = isset($_SESSION['entreprise'])? $_SESSION['entreprise'] : false;
    $projet = new Projet($db);
    $utilisateur = new Utilisateur($db);
    $listeUtilisateur = $utilisateur->selectByEntreprise($idEntreprise);
    $chef = $projet->selectChefByEntreprise($idEntreprise);
    if(isset($_POST['btAjoutProjet'])){
        $exec = $projet->insert($_POST['libelle'], intval($idEntreprise), intval($_POST['chef']));
        $idProjet = $db->lastinsertid();

        if($exec){
            if(isset($_POST['utilsateurProjet'])){
                $utilisateurProjet = $_POST['utilsateurProjet'];
                foreach($utilisateurProjet as $user){
                   $projet->insertUtilisateurInProjet(floatval($utilisateurProjet[0]), $idProjet);
                }
            }else{
                $utilisateurProjet = array();
                echo '<script> alert("Attention, votre projet ne contient pas d\'utilisateur"); </script>';
            }
            $form['valide'] = true;
            $form['message'] = "Projet ajouté";
        }else{
            $form['valide'] = false;
            $form['message'] = "Erreur d'ajout du projet";
        }
    }

    echo $twig->render('ajoutProjet.html.twig', array('form'=>$form, 'chefs'=>$chef, 'utilisateurs'=>$listeUtilisateur));
}

function actionListeProjets($twig, $db) {
    $form = array();
    $projet = new Projet($db);
    $utilisateur = new Utilisateur($db);
    $unUtilisateur = $utilisateur->selectByEmail($_SESSION['login']);
    $listeProjet = $projet->selectProjetById($unUtilisateur['id_utilisateur']);
    echo $twig->render('listeProjets.html.twig', array('form'=>$form, 'projets'=>$listeProjet));
}

?>