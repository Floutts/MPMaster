<?php

function actionAjoutTache($twig, $db) {

    $form = array();
    $projet = new Projet($db);
    $tache = new Tache($db);
    if(isset($_GET['idTache'])){
        $tache->deleteTache($_GET['idTache']);
     }
    $listeTacheProjet = $tache->selectAll($_GET['idProjet']);
    $utilisateur = new Utilisateur($db);
    $unUtilisateur = $utilisateur->selectByEmail($_SESSION['login']);
    $UtilisateursByProjet = $utilisateur->selectByProjet($_GET['idProjet']);
    $idProjet = $_GET['idProjet'];

    if($unUtilisateur['id_role'] == 4){
        $listeTaches = $tache->selectTachesByUser($unUtilisateur['id_utilisateur']);
    }else{
        $listeTaches = $tache->selectTachesByProjet($idProjet);
    }
    if(isset($_POST['btAjoutTache'])){
        
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
            if(isset($_POST['tachePreced'])){
                foreach ($_POST['tachePreced'] as $option){
                    if($option != 'Aucune'){
                        $lastTacheProjet = $tache->selectLastTache($_GET['idProjet']);
                        $tache->insertTachePrecedente($lastTacheProjet['id_tache'], $option);
                    }
                }
            }
            $form['valide'] = true;
            $form['message'] = "Tache ajoutée";
        }else{
            $form['valide'] = false;
            $form['message'] = "Erreur d'ajout de la tache";
        }
    }

    echo $twig->render('tache.html.twig', array('form'=>$form, 'utilisateurs'=>$UtilisateursByProjet, "listeTacheProjet"=>$listeTacheProjet, 'taches'=>$listeTaches, 'idProjet' => $idProjet));
}

function actionListeTacheByUtilisateur($twig, $db) {
    $form = array();
    $tache = new Tache($db);
    if(isset($_GET['idTache'])){
        $tache->deleteTache($_GET['idTache']);
         
     }
    $utilisateur = new Utilisateur($db);
    $unUtilisateur = $utilisateur->selectByEmail($_SESSION['login']);
    $listeTaches = $tache->selectTachesByUser($unUtilisateur['id_utilisateur']);
    echo $twig->render('listeTachesUtilisateur.html.twig', array('form'=>$form,'taches'=>$listeTaches ));
}


function actionListeTacheByProjet($twig, $db) {
    $form = array();
    $idProjet = $_GET['idProjet'];
    $tache = new Tache($db);
    if(isset($_GET['idTache'])){
        $tache->deleteTache($_GET['idTache']);
         
     }
    $listeTaches = $tache->selectTachesByProjet($idProjet);
    echo $twig->render('listeTachesProjet.html.twig', array('form'=>$form,'taches'=>$listeTaches, 'idProjet' => $idProjet));
}


?>