<?php
function getPage($db)
{
    $lesPages['accueil'] = "actionAccueil;0";
    $lesPages['apropos'] = "actionApropos;0";
    $lesPages['mentions'] = "actionMentions;0";
    $lesPages['inscription'] = "actionInscription;0";
    $lesPages['connexion'] = "actionConnexion;0";
    $lesPages['deconnexion'] = "actionDeconnexion;0";
    $lesPages['maintenance'] = "actionMaintenance;0";
    $lesPages['ajoutEntreprise'] = "actionAjoutEntreprise;0";
    $lesPages['ajoutProjet'] = "actionAjoutProjet;0";
    $lesPages['ajoutTache'] = "actionAjoutTache;0";
    $lesPages['listeProjets'] = "actionListeProjets;0";
    $lesPages['listeTacheByProjet'] = "actionListeTacheByProjet;0";
    $lesPages['listeTacheByUtilisateur'] = "actionListeTacheByUtilisateur;0";
    $lesPages['pert'] = "actionPert;0";
    $lesPages['abonnements'] = "actionAbonnements;0";
    $lesPages['risque'] = "actionAjoutRisque;0";
    $lesPages['anomalie'] = "actionAjoutAnomalie;0";
    $lesPages['listeTypeRisque'] = "actionListeTypeRisque;0";
    $lesPages['listeUtilisateur'] = "actionListeUtilisateur;0";
    $lesPages['graphiqueAnomalie'] = "actionGraphAnomalie;0";


    /*****************
     Routing des API
    *****************/

    $lesPages['allUsers'] = "actionAllUsers;0";
    $lesPages['inscriptionEntreprise'] = "actioninscriptionEntreprise;0";
    $lesPages['risqueByClasse'] = "actionRisqueByClasse;0";
    $lesPages['userByEmail'] = "actionUserByEmail;0";
    $lesPages['userByEntreprise'] = "actionUserByEntreprise;0";
    $lesPages['projetByUser'] = "actionProjetByUser;0";
    $lesPages['projetByEntreprise'] = "actionProjetByEntreprise;0";
    $lesPages['deleteUser'] = "actionDeleteUser;0";
    $lesPages['addUser'] = "actionAddUser;0";
    $lesPages['entrepriseById'] = "actionGetEntreprise;0";
    $lesPages['roleById'] = "actionGetRole;0";

    
    if ($db != null) {
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
        } else {
            $page = 'accueil';
        }

        if (!isset($lesPages[$page])) {
            $page = 'accueil';
        }
        $explose = explode(";", $lesPages[$page]); // Nous d??coupons la ligne du tableau sur le  // caract??re ?? ; ?? Le r??sultat est stock?? dans le tableau $explose
        $role = $explose[1]; // Le r??le est dans la 2??me partie du tableau $explose
        if ($role != 0) { // Si mon r??le n??cessite une v??rification
            if (isset($_SESSION['login'])) {  // Si je me suis authentifi??
                if (isset($_SESSION['role'])) {  // Si j???ai bien un r??le
                    if ($role != $_SESSION['role']) { // Si mon r??le ne correspond pas ?? celui qui est n??cessaire //pour voir la page
                        $contenu = 'actionAccueil';  // Je le redirige vers l???accueil, car il n???a pas le bon r??le
                    } else {
                        $contenu = $explose[0];
                        // Je r??cup??re le nom du contr??leur, car il a le bon r??le
                    }
                } else {
                    $contenu = 'actionAccueil';
                }
            } else {
                $contenu = 'actionAccueil';  // Page d???accueil, car il n???est pas authentifi??
            }
        } else {
            $contenu = $explose[0]; //  Je r??cup??re le contr??leur, car il n???a pas besoin d???avoir un r??le
        }

    } else {
        $contenu = 'actionMaintenance';
    }
    return $contenu;
}
?>