<?php

function actionAjoutProjet($twig, $db) {
    $form = array();
    $idEntreprise = isset($_SESSION['entreprise'])? $_SESSION['entreprise'] : false;
    $projet = new Projet($db);
    $chef = $projet->selectChefByEntreprise($idEntreprise);
    var_dump($chef);
    if(isset($_POST['btAjoutProjet'])){

        $exec = $projet->insert($_POST['libelle'], $idEntreprise, $_POST['chef']);
        if($exec){
            $form['valide'] = true;
            $form['message'] = "Projet ajouté";
        }else{
            $form['valide'] = false;
            $form['message'] = "Erreur d'ajout du projet";
        }
        
    }

    echo $twig->render('ajoutProjet.html.twig', array('form'=>$form, 'chefs'=>$chef));
}

function actionListeProjets($twig, $db) {
    $form = array();
    $projet = new Projet($db);
    $utilisateur = new Utilisateur($db);
    $unUtilisateur = $utilisateur->selectByEmail($_SESSION['login']);
    if($unUtilisateur['id_role'] == 1){
        $listeProjet = $projet->selectByEntreprise($_SESSION['entreprise']);
    }
    else{
        $listeProjet = $projet->selectProjetById($unUtilisateur['id_utilisateur']);
    }
    echo $twig->render('listeProjets.html.twig', array('form'=>$form, 'projets'=>$listeProjet));
}

?>