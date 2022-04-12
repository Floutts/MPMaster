<?php 


function actionAjoutEntreprise($twig,$db) {

    $form = array();
    if(isset($_POST['btAjoutEntreprise'])){
        $entreprise = new Entreprise($db);
        $exec = $entreprise->insert($_POST['libelle']);
        $idEntreprise = $db->lastinsertid();
        if($exec){
            $form['valide'] = true;
            $form['message'] = "Entreprise ajoutée";
            header('Location: index.php?page=inscription&idEntreprise='.$idEntreprise);
        }else{
            $form['valide'] = false;
            $form['message'] = "Erreur d'ajout d'entreprise";
        }
    }

    echo $twig->render('ajoutEntreprise.html.twig', array('form'=>$form));
}


function actionInscriptionEntreprise($twig,$db){
    $form = array();
    if (isset($_POST['btInscrire'])){
        $email = $_POST['email'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $mdp = $_POST['password'];
        $confirmation =$_POST['confirmation'];
        $form['valide'] = true;
        $role = 2;
        $entreprise = $_GET['idEntreprise'];
        if ($mdp!=$confirmation){
            $form['valide'] = false;
            $form['message'] = 'Les mots de passe sont différents';
        }
        else{
            $utilisateur = new Utilisateur($db);
            $exec = $utilisateur->insert($email, password_hash($mdp,PASSWORD_DEFAULT),$nom, $prenom, $role, $entreprise);
            if (!$exec){
                $form['valide'] = false;
                $form['message'] = 'Problème d\'insertion dans la table utilisateur ';
            }

        }
        $form['email'] = $email;
        $form['nom'] = $nom;
        $form['prenom'] = $prenom;
    }
    echo $twig->render('inscription.html.twig',array('form'=>$form));
}





?>