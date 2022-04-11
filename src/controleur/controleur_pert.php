<?php 


function actionPert($twig, $db) {
    $pert = new Pert($db);
    $listeTachePreced = $pert->selectTachePreced();
    $listeTaches = $pert->selectTaches();
    $a =-1;
    $i=0;
    $tableNiveau = [];
    $tableTmpNiveau = [];


    while($a <= count($listeTaches)-1){
        $a++;
        $i =-1;
       
        while ($i <= count($listeTachePreced)-1){
            $i++;
            
            if($listeTaches[$a]["id_tache"] == $listeTachePreced[$i]["id_tache"]){
                break;
            }
            elseif($i == count($listeTachePreced)-1){
                $tableTmpNiveau[] = $listeTaches[$a]["id_tache"];
                unset($listeTaches[$a]);
                break;
            }

            if( count($listeTachePreced)==0){
                $tableTmpNiveau[] = $listeTaches[$a]["id_tache"];
                
               
                
            }
            
        }
       
        if($a == count($listeTaches)-1){
            if(count($listeTachePreced)>0){
                $a = -1;
                
            }
            $tableNiveau[] = $tableTmpNiveau;
            
            for($t = 0; $t<=count($tableTmpNiveau)-1; $t++){
                
                for($u = 0; $u<=count($listeTachePreced); $u++){
                    
                    if($tableTmpNiveau[$t]["id_tache"] == $listeTachePreced[$u]["id_tache_precedente"]){      
                        
                       unset($listeTachePreced[$u]);
                       
                    }
                }
                
            }      
            array_diff($tableTmpNiveau,$tableTmpNiveau);     
        }
        
    }
    var_dump($tableNiveau);
        
    echo $twig->render('pert.html.twig', array('listeTachePreced'=>$listeTachePreced, 'listeTaches'=>$listeTaches, 'tableNiveau'=> $tableNiveau));
}


?>