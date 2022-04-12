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

function actionListeProjets($twig) {
    echo $twig->render('listeProjets.html.twig', array());
}

?>