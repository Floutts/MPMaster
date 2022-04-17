<?php

function actionAjoutTache($twig, $db) {
    $form = array();
    $projet = new Projet($db);
    $utilisateur = new Utilisateur($db);
    $unUtilisateur = $utilisateur->selectByEmail($_SESSION['login']);
    $listeProjet = $projet->selectProjetById($unUtilisateur['id_utilisateur']);
    if(isset($_POST['btAjoutTache'])){
        $tache = new Tache($db);
        $exec = $tache->insert($_POST['projet'],$_POST['libelle'], $_POST['duree']);
        if($exec){
            $form['valide'] = true;
            $form['message'] = "Tache ajoutée";
        }else{
            $form['valide'] = false;
            $form['message'] = "Erreur d'ajout de la tache";
        }
    }

    echo $twig->render('ajoutTache.html.twig', array('form'=>$form, 'projets'=>$listeProjet));
}

function actionListeTaches($twig, $db) {
    $form = array();
    $projet = new Projet($db);
    $utilisateur = new Utilisateur($db);
    $unUtilisateur = $utilisateur->selectByEmail($_SESSION['login']);
    $listeProjet = $projet->selectProjetById($unUtilisateur['id_utilisateur']);
    echo $twig->render('listeProjets.html.twig', array('form'=>$form, 'projets'=>$listeProjet));
}

?>