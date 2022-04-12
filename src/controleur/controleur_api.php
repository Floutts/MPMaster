<?php

function actionAllUsers($twig,$db){
    $users = new Utilisateur($db);
    $allUsers = $users->select();
    header('Content-Type: application/json');
    echo json_encode($allUsers);
}
?>