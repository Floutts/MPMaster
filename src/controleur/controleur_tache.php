<?php

function actionAjoutTache($twig, $db) {
    $form = array();
    $projet = new Projet($db);
    $utilisateur = new Utilisateur($db);
    $unUtilisateur = $utilisateur->selectByEmail($_SESSION['login']);
    $UtilisateursByProjet = $utilisateur->selectByProjet($_GET['idProjet']);
    if(isset($_POST['btAjoutTache'])){
        $tache = new Tache($db);
        $exec = $tache->insert($_GET['idProjet'],$_POST['libelle'], $_POST['duree']);
        $idTache = $db->lastinsertid();

        if($exec){
            if(isset($_POST['utilsateurTache'])){
                $utilisateurTache = $_POST['utilsateurTache'];
                foreach($utilisateurTache as $user){
                   $tache->insertUtilisateurInTache(floatval($user), $idTache);
                }
            }else{
                $utilisateurProjet = array();
            }
            $form['valide'] = true;
            $form['message'] = "Tache ajoutée";
        }else{
            $form['valide'] = false;
            $form['message'] = "Erreur d'ajout de la tache";
        }
    }

    echo $twig->render('ajoutTache.html.twig', array('form'=>$form, 'utilisateurs'=>$UtilisateursByProjet));
}

function actionListeTaches($twig, $db) {
    $form = array();
    $projet = new Projet($db);
    $utilisateur = new Utilisateur($db);
    $unUtilisateur = $utilisateur->selectByEmail($_SESSION['login']);
    $listeProjet = $projet->selectProjetById($unUtilisateur['id_utilisateur']);
    echo $twig->render('listeProjets.html.twig', array('form'=>$form,'projets'=>$listeProjet ));
}

?>