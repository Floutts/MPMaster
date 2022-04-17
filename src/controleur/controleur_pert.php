<?php 


function actionPert($twig, $db) {
    $pert = new Pert($db);
    $listeTachePreced = $pert->selectTachePreced();
    $listeTaches = $pert->selectTaches();
    $not_in = "0";
    $not_in_tmp = "";
    $tableNiveau = [];
    $tableTmpNiveau = [];

    while(count($listeTaches) != 0){
        $tableTmpNiveau = [];
        $not_in_tmp = "";
        foreach($listeTaches as $key=>$tache){
            if(count($pert->selectTachesPrecedByTache($db,$tache['id_tache'],$not_in)) == 0){
                array_push($tableTmpNiveau,$tache['id_tache']);
                $not_in_tmp .= ','.$tache['id_tache'];
                unset($listeTaches[$key]);
                continue;
            }
        }
        $not_in .= $not_in_tmp;
        array_push($tableNiveau,$tableTmpNiveau);
    }

    var_dump(json_encode($tableNiveau));
        
    echo $twig->render('pert.html.twig', array('listeTachePreced'=>json_encode($listeTachePreced), 'listeTaches'=>json_encode($listeTaches), 'tableNiveau'=> json_encode($tableNiveau)));
}


?>