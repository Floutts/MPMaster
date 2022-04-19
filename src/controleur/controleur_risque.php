<?php
function actionListeRisques($twig, $db) {
    $risque = new Risque($db);
    $idProjet = $_GET['idProjet'];
    $risques = $risque->selectRisqueByProjet($idProjet);
    $form = array();
    echo $twig->render('risque.html.twig', array('risques'=>$risques));
}





?>