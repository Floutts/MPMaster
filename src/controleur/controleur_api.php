<?php

function actionAllUsers($twig,$db){
    $users = new Utilisateur($db);
    $allUsers = $users->select();
    header('Content-Type: application/json');
    echo json_encode($allUsers);
}

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

function actionProjetByEntreprise($twig,$db){
    $projet = new Projet($db);
    $projetByEntreprise = $projet->selectByEntreprise($_GET['idEntreprise']);
    header('Content-Type: application/json');
    echo json_encode($projetByEntreprise);
}

function actionDeleteUser($twig, $db){
    $user = new Utilisateur($db);
    $userToDelete = $user->delete($_GET['idUser']);
}

function actionAddUser($twig, $db){
    $user = new Utilisateur($db);
    $userToAdd = $user->insert($_GET['email'], $_GET['mdp'],$_GET['nom'], $_GET['prenom'], 1, $_GET['idEntreprise']);
}

function actionGetEntreprise($twig, $db){
    $entreprise = new Entreprise($db);
    $entrepriseById = $entreprise->selectById($_GET['idEntreprise']);
    header('Content-Type: application/json');
    echo json_encode($entrepriseById);
}

function actionGetRole($twig, $db){

}
?>