<?php
function actionAjoutAnomalie($twig, $db) {
    $form = array();
    $utilisateur = new Utilisateur($db);
    $anomalie = new Anomalie($db);
    $unUtilisateur = $utilisateur->selectByEmail($_SESSION['login']);
    $idProjet = $_GET['idProjet'];
    $nbAnomalie = $anomalie->selectNumberAnomalieByProjet($idProjet);
    $num_anomalie = $nbAnomalie['nbAnomalie'];
    $num_anomalie = $num_anomalie + 1;
    $date_anomalie = date("Y.m.d"); 
    if(isset($_POST['btAjoutAnomalie'])){
        $emplacement = $_POST['emplacement'];
        $scenario = $_POST['scenario'];
        $exec = $anomalie->insert($idProjet,$num_anomalie,$emplacement,1,$scenario,$date_anomalie,$unUtilisateur['id_utilisateur']);
        if($exec){
            $form['valide'] = true;
            $form['message'] = "Risque ajouté";
        }else{
            $form['valide'] = false;
            $form['message'] = "Erreur d'ajout du risque";
        }
    }

    echo $twig->render('ajoutAnomalie.html.twig', array('form'=>$form));
}

function actionListeAnomalie($twig, $db) {
    $form = array();
    $anomalie = new Anomalie($db);
    $idProjet = $_GET['idProjet'];
    $anomalie = $anomalie->selectAnomalieByProjet($idProjet);
    echo $twig->render('listeAnomalie.html.twig', array('anomalies'=>$anomalie));
}

function actionGraphAnomalie($twig, $db) {
    $form = array();
    $anomalie = new Anomalie($db);
    $idProjet = $_GET['idProjet'];
    $anomalie = $anomalie->selectAnomalieByProjet($idProjet);
    echo $twig->render('grapiqueAnomalie.html.twig');
}
