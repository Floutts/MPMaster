<?php
function actionUserByEmail($twig,$db){
    $user = new Utilisateur($db);
    $userByEmail = $user->selectByEmail($_GET['email']);
    header('Content-Type: application/json');
    echo json_encode($userByEmail);
}

function actionUserByEntreprise($twig,$db){
    $user = new Utilisateur($db);
    $userByEntreprise = $user->selectByEntreprise($_GET['idEntreprise']);
    header('Content-Type: application/json');
    echo json_encode($userByEntreprise);
}

function actionProjetByUser($twig,$db){
    $projet = new Projet($db);
    $projetByUser = $projet->selectByUser($_GET['idUser']);
    header('Content-Type: application/json');
    echo json_encode($projetByUser);
}
?>