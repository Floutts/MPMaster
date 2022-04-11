<?php

function actionAjoutTache($twig, $db) {
    $form = array();
    if(isset($_POST['btAjoutTache'])){
        $tache = new Projet($db);
        $exec = $projet->insert($_POST['libelle'], $_POST['niveau']);
        if($exec){
            $form['valide'] = true;
            $form['message'] = "Tache ajoutée";
        }else{
            $form['valide'] = false;
            $form['message'] = "Erreur d'ajout de la tache";
        }
    }

    echo $twig->render('ajoutTache.html.twig', array('form'=>$form));
}

?>