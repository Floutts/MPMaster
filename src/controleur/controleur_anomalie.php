<?php
function actionAjoutAnomalie($twig, $db) {
    $form = array();
    $utilisateur = new Utilisateur($db);
    $anomalie = new Anomalie($db);
    $idProjet = $_GET['idProjet'];
    $lesAnomalies = $anomalie->selectAnomalieByProjet($idProjet);
    $unUtilisateur = $utilisateur->selectByEmail($_SESSION['login']);
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
            $form['message'] = "Anomalie ajoutée";
        }else{
            $form['valide'] = false;
            $form['message'] = "Erreur d'ajout de l'anomalie";
        }
    }
    if(isset($_POST['btModifierAnomalie'])){
        $statut = $_POST['statut'];
        $id = $_POST['id'];
        $exec = $anomalie->modifyStatut( $id,$statut);
        if($exec){
            $form['valide'] = true;
            $form['message'] = "Statut anomalie mis à jour";
        }else{
            $form['valide'] = false;
            $form['message'] = "Erreur statut anomalie";
        }
    }


    $nbOuvert = $anomalie->selectByEtat('1',$idProjet)['nb'];
    $nbCorrige = $anomalie->selectByEtat('2',$idProjet)['nb'];
    $nbNonReproduit = $anomalie->selectByEtat('3',$idProjet)['nb'];
    $nbFerme = $anomalie->selectByEtat('4',$idProjet)['nb'];

    echo $twig->render('anomalie.html.twig', array('form'=>$form, 'anomalies'=>$lesAnomalies, 'nbOuvert' => $nbOuvert, 'nbCorrige' => $nbCorrige, 'nbNonReproduit' => $nbNonReproduit, 'nbFerme' => $nbFerme));
}

