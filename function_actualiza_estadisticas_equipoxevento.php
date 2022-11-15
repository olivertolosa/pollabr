<?php


function actualizar_estadisticas_equipoxevento($id_equipo,$id_evento){//actualiza las estadisticas del equipo con id $id_equipo en el evento $id_evento
//se debe contar con conexión a la bd activa.

//usada en carga_marcadores.php y en la opción de menú de actualzar posicioness

//actualzar estadisticas del equipo en el evento
     //obtener los datos del eq1
     global $db;

     $query="SELECT DISTINCT id_partido,id_equipo1,id_equipo2,goles1,goles2 FROM partidos WHERE (id_equipo1='$id_equipo' OR id_equipo2='$id_equipo') AND goles1<>'-1' AND id_evento='$id_evento'";
     $stmt = $db->query($query);

print "evento:$id_evento<br>";
print "equipo:$id_equipo";


     $pj=0;
     $pg=0;
     $pp=0;
     $gf=0;
     $gc=0;
     $pe=0;
     $ptos=0;
     while ($row=$stmt->fetch(PDO::FETCH_ASSOC)){     	$id_partido=$row['id_partido'];
        $id_equipo1=$row['id_equipo1'];
        $id_equipo2=$row['id_equipo2'];
        $goles1=$row['goles1'];
        $goles2=$row['goles2'];
        $id_part=$row['id_partido'];

//        print "<br>evaluando partido $id_partido<br>";

     //obtener la ronda a la que pertenece el partido
     $query_ronda="SELECT ronda FROM partidos WHERE id_partido='$id_partido'";
     $stmt_ronda = $db->query($query);
     $row_ronda=$stmt_ronda->fetch(PDO::FETCH_ASSOC);
     $num_ronda=$row_ronda['ronda'];



        $pj++;


        if ($id_equipo1==$id_equipo){
           $gf+=$goles1;
           $gc+=$goles2;
           if ($goles1>$goles2){ //gano
              $pg++;
              $ptos+=3;
//print "eq1......gano partido...";
           }else if ($goles1<$goles2){  //perdio
              $pp++;
//print "eq1......perdio partido...";
           }else{             //empato
              $pe++;
              $ptos+=1;
//print "eq1......empató partido...";
           }
        }else{
           $gf+=$goles2;
           $gc+=$goles1;
           if ($goles2>$goles1){ //gano
              $pg++;
              $ptos+=3;
//print "eq2......gano partido...";
           }else if ($goles2<$goles1){  //perdio
              $pp++;
//print "eq2......perdio partido...";
           }else{             //empato
              $pe++;
              $ptos+=1;
//print "eq2......empató partido...";
           }
        }
        $gd=$gf-$gc;
        $query_update="UPDATE gruposxevento SET pj='$pj',pg='$pg',pp='$pp',pe='$pe',gf='$gf',gc='$gc',gd='$gd',ptos='$ptos' WHERE id_equipo='$id_equipo' AND id_evento='$id_evento' AND num_ronda='$num_ronda'";
        $db->query($query_update);

//print " -->update=$query_update<br><br>";
     }
}
?>

</body>

</html>