<?php 


function actionPert($twig, $db) {
    $pert = new Pert($db);
    $idProjet = $_GET['idProjet'];
    $listeTachesCopie = $pert->selectTaches($idProjet);
    $listeTaches = $listeTachesCopie;
    $not_in = "0";
    $not_in_tmp = "";
    $tableNiveau = [];
    $tableTmpNiveau = [];
    $finalTable = array(); // [id_tache,libelle,duree,[id_tache_precedente1,id_tache_precedente2],[id_tache_suivante1,id_tache_suivante2],niveau,duree_min,duree_max,marge_libre,marge_totale,is_critique]
    $id_tache_to_key = array();

    foreach($listeTaches as $key=>$tache){
        $id_tache_to_key[$tache['id_tache']] = $key;
        $listeTachePreced = [];
        $listeTacheSuivante = [];
        $finalTable[$tache['id_tache']] = array();
        $finalTable[$tache['id_tache']]['id_tache'] = $tache['id_tache'];
        $finalTable[$tache['id_tache']]['libelle'] = $tache['libelle'];
        $finalTable[$tache['id_tache']]['duree'] = $tache['duree'];
        foreach($pert->selectTachesPrecedByTache($db,$tache['id_tache'],$not_in) as $tache_precedente){
            array_push($listeTachePreced,$tache_precedente['id_tache_precedente']);
        }
        $finalTable[$tache['id_tache']]['liste_taches_precedentes'] = $listeTachePreced;
        foreach($pert->selectTachesSuivantesByTache($tache['id_tache']) as $tache_suivante){
            array_push($listeTacheSuivante,$tache_suivante['id_tache']);
        }
        $finalTable[$tache['id_tache']]['liste_taches_suivantes'] = $listeTacheSuivante;
    }

    $tache_fin = array("id_tache"=> -1,"duree"=>null,"liste_taches_precedentes"=>array(),"liste_tache_suivantes" => null);

    // Fonction pour avoir les niveaux
    while(count($listeTaches) != 0){
        $tableTmpNiveau = [];
        $not_in_tmp = "";
        foreach($listeTaches as $key=>$tache){
            if(count($pert->selectTachesPrecedByTache($db,$tache['id_tache'],$not_in)) == 0){
                $finalTable[$tache['id_tache']]['niveau'] = count($tableNiveau);
                array_push($tableTmpNiveau,$tache['id_tache']);
                $not_in_tmp .= ','.$tache['id_tache'];
                unset($listeTaches[$key]);
                continue;
            }
        }
        $not_in .= $not_in_tmp;
        array_push($tableNiveau,$tableTmpNiveau);
    }

    $tache_fin['niveau'] = count($tableNiveau);

    foreach($finalTable as $tache){
        if(count($tache['liste_taches_suivantes']) == 0){
            array_push($tache_fin['liste_taches_precedentes'],$tache['id_tache']);
        }
    }

    $listeTaches = $listeTachesCopie;

    // Fonction pour avoir la durée min
    while(count($listeTaches) != 0){
        foreach($finalTable as $tache){
            if($tache['niveau'] == 0){
                $finalTable[$tache['id_tache']]['duree_min'] = 0;
                unset($listeTaches[$id_tache_to_key[$tache['id_tache']]]);
            }else{
                $break = 0;
                $liste_durees_prec = array();
                foreach($tache['liste_taches_precedentes'] as $id_tache_precedente){
                    if(isset($finalTable[$id_tache_precedente]['duree_min'])){
                        array_push($liste_durees_prec,$finalTable[$id_tache_precedente]['duree_min']+$finalTable[$id_tache_precedente]['duree']);
                    }else{
                        $break = 1;
                        break;
                    }
                }
                if($break == 0){
                    $finalTable[$tache['id_tache']]['duree_min'] = max($liste_durees_prec);
                    unset($listeTaches[$id_tache_to_key[$tache['id_tache']]]);
                }
            }
        }
    }
    $liste_durees_prec = array();
    foreach($tache_fin['liste_taches_precedentes'] as $id_tache_precedente){
        array_push($liste_durees_prec,$finalTable[$id_tache_precedente]['duree_min']+$finalTable[$id_tache_precedente]['duree']);
    }

    $tache_fin['duree_min'] =  max($liste_durees_prec);
    $tache_fin['duree_max'] =  max($liste_durees_prec);

    $listeTaches = $listeTachesCopie;

    // Fonction pour avoir la durée max
    while(count($listeTaches) != 0){
        foreach($finalTable as $tache){
            if(in_array($tache['id_tache'],$tache_fin['liste_taches_precedentes'])){
                $finalTable[$tache['id_tache']]['duree_max'] = $tache_fin['duree_max']  - $finalTable[$tache['id_tache']]['duree'];
                unset($listeTaches[$id_tache_to_key[$tache['id_tache']]]);
            }else{
                $break = 0;
                $liste_durees_suiv = array();
                foreach($tache['liste_taches_suivantes'] as $id_tache_suivante){
                    if(isset($finalTable[$id_tache_suivante]['duree_max'])){
                        array_push($liste_durees_suiv,$finalTable[$id_tache_suivante]['duree_max']);
                    }else{
                        $break = 1;
                        break;
                    }
                }
                if($break == 0){
                    $finalTable[$tache['id_tache']]['duree_max'] = min($liste_durees_suiv) - $finalTable[$tache['id_tache']]['duree'];
                    unset($listeTaches[$id_tache_to_key[$tache['id_tache']]]);
                }
            }
        }
    }

    // Fonction pour avoir la marge totale

    foreach($finalTable as $tache){
        $finalTable[$tache['id_tache']]['marge_totale'] =  $finalTable[$tache['id_tache']]['duree_max'] - $finalTable[$tache['id_tache']]['duree_min'];
    }

    // Fonction pour avoir la marge libre
    foreach($finalTable as $tache){
        if(in_array($tache['id_tache'],$tache_fin['liste_taches_precedentes'])){
            $finalTable[$tache['id_tache']]['marge_libre'] = $tache_fin['duree_min']  - $finalTable[$tache['id_tache']]['duree'] - $finalTable[$tache['id_tache']]['duree_min'];
            $finalTable[$tache['id_tache']]['critique'] = $finalTable[$tache['id_tache']]['marge_libre'] == 0;
        }else{
            $liste_durees_suiv = array();
            foreach($tache['liste_taches_suivantes'] as $id_tache_suivante){
                array_push($liste_durees_suiv,$finalTable[$id_tache_suivante]['duree_min']);
            }
            $finalTable[$tache['id_tache']]['marge_libre'] = min($liste_durees_suiv) - $finalTable[$tache['id_tache']]['duree'] - $finalTable[$tache['id_tache']]['duree_min'];
        }
    }

    array_push($finalTable,$tache_fin);
    var_dump($finalTable);




    // while($a <= count($listeTaches)-1){
    //     $a++;
    //     $i =-1;
       
    //     while ($i <= count($listeTachePreced)-1){
    //         $i++;
           
    //         if($listeTaches[$a]["id_tache"] == $listeTachePreced[$i]["id_tache"]){
    //             break;
    //         }
    //         elseif($i == count($listeTachePreced)-1){
    //             $tableTmpNiveau[] = $listeTaches[$a]["id_tache"];
    //             unset($listeTaches[$a]);
    //             break;
    //         }

    //         if( count($listeTachePreced)==0){
    //             $tableTmpNiveau[] = $listeTaches[$a]["id_tache"];
                
    //         }
            
    //     }
       
    //     if($a == count($listeTaches)-1){
    //         if(count($listeTachePreced)>0){
    //             $a = -1;
                
    //         }
    //         $tableNiveau[] = $tableTmpNiveau;
            
    //         for($t = 0; $t<=count($tableTmpNiveau)-1; $t++){
                
    //             for($u = 0; $u<=count($listeTachePreced); $u++){
                    
    //                 if($tableTmpNiveau[$t]["id_tache"] == $listeTachePreced[$u]["id_tache_precedente"]){      
                        
    //                    unset($listeTachePreced[$u]);
                       
    //                 }
    //             }
                
    //         }      
    //         array_diff($tableTmpNiveau,$tableTmpNiveau);     
    //     }
        
    // }
    // var_dump($tableNiveau);
        
    echo $twig->render('pert.html.twig', array('taches'=>$finalTable));
}


?>