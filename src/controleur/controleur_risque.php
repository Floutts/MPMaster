<?php

function actionListeTypeRisque($twig, $db) {
    $risque = new Risque($db);
    
 
    if(isset($_GET['idRisque'])){
       $risque->deleteTypeRisque($_GET['idRisque']);
        
    }
    if(isset($_POST['btAjoutTypeRisque'])){
        $idClasseRisque = $_POST['classe_risque'];
        
        $libelle = $_POST['libelle'];
        $exec = $risque->insertTypeRisque($idClasseRisque, $libelle);
        if($exec){
            $form['valide'] = true;
            $form['message'] = "Type risque ajouté";
        }else{
            $form['valide'] = false;
            $form['message'] = "Erreur d'ajout du type risque";
        }
    }
    $risques = $risque->selectTypeRisque();
    $classeType = $risque->selectClasseRisque();
    echo $twig->render('listeTypeRisque.html.twig', array('risques'=>$risques, 'classeType'=>$classeType));
}


function actionRisqueByClasse($twig,$db){
    $risque = new Risque($db);
    echo json_encode($risque->selectRisqueByClasse($_GET['id_classe_risque']),JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    //json_encode();
}

function actionAjoutRisque($twig, $db) {
    $form = array();
    $risque = new Risque($db);
    $idProjet = $_GET['idProjet'];
    if(isset($_GET['idRisque'])){
       $risque->delete($_GET['idProjet'], $_GET['idRisque']);
    }
    $risques = $risque->selectRisqueByProjet($idProjet);
    $listeTypeRisque = $risque->selectTypeRisque();
    $listeClasseRisque = $risque->selectClasseRisque();
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
    echo $twig->render('risque.html.twig', array('form'=>$form, 'type_risque'=>$listeTypeRisque, 'risques'=>$risques, 'idProjet'=>$idProjet));
}


?>