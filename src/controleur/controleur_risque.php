<?php
function actionListeRisques($twig, $db) {
    $risque = new Risque($db);
    $idProjet = $_GET['idProjet'];
    $risques = $risque->selectRisqueByProjet($idProjet);
    $form = array();
    echo $twig->render('risque.html.twig', array('risques'=>$risques));
}

function actionAjoutRisque($twig, $db) {
    $form = array();
    $idProjet = $_GET['idProjet'];
    $risque = new Risque($db);
    $listeTypeRisque = $risque->selectTypeRisque();
    if(isset($_POST['btAjoutRisque'])){
        var_dump($_POST);
        $id_type_risque = $_POST['type_risque'];
        $probabilite = $_POST['proba'];
        $severite = $_POST['severite'];
        $cout_reduc_risque = $_POST['cout'];
        $moyen_detection = $_POST['detection'];
        $mesure_correction = $_POST['correction'];
        $exec = $risque->insert($idProjet,$id_type_risque,$probabilite,$severite,$cout_reduc_risque,$moyen_detection,$mesure_correction);
        if($exec){
            $form['valide'] = true;
            $form['message'] = "Risque ajouté";
        }else{
            $form['valide'] = false;
            $form['message'] = "Erreur d'ajout du risque";
        }
    }

    echo $twig->render('ajoutRisque.html.twig', array('form'=>$form, 'type_risque'=>$listeTypeRisque));
}




?>