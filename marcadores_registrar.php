<?php
session_start();
include 'includes/_Policy.php';
require 'includes/Open-Connection.php';
require_once 'audit.php';
audit_max();

$id_evento=$_POST['id_evento'];

foreach($_REQUEST as $key => $valor){	//print "valor=$valor<br>";  if ($valor>=-1 and $valor<9 and $key[0]=='p'){ //viene un marcador  	 $id_partido=substr($key,1,strpos($key,"-")-1);
     $eq=substr($key,strlen($key)-1);

   	 $query="UPDATE partidos SET goles$eq=:valor WHERE id_partido=:id_partido";
 	 //print "q=$query<br>";
     $stmt= $db->prepare($query);
	 $stmt->bindParam(':valor',$valor);
	 $stmt->bindParam(':id_partido',$id_partido);
	 $stmt->execute();

//print "<br><br>****************************************************<br>";

  }
     	 $_SESSION['msg']="<span class=\"msg_ok\">Marcadores Registrados</span>";
}


//obtener número de rondas del evento
$query_ronda="SELECT num_rondas FROM eventos WHERE id_evento=:id_evento";
$stmt_ronda= $db->prepare($query_ronda);
$stmt_ronda->bindParam(':id_evento',$id_evento);
$stmt_ronda->execute();

$row_ronda=$stmt_ronda->fetch(PDO::FETCH_ASSOC);
$num_rondas=$row_ronda['num_rondas'];


//actualzar estadisticas del equipo en el evento
//seleccionar todos los equipos del evento

//para cada ronda calcular las estadisticas de cada equipo

$query="SELECT id_equipo FROM equiposxevento WHERE id_evento=:id_evento";
$stmt= $db->prepare($query);
$stmt->bindParam(':id_evento',$id_evento);
$stmt->execute();


while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {   $i=1;
   $id_equipo=$row['id_equipo'];
   $pj=0;
   $pg=0;
   $pp=0;
   $gf=0;
   $gc=0;
   $pe=0;
   $ptos=0;
//print "<br><br>***********************<br>id_equipo:$id_equipo<br>";

   while ($i<=$num_rondas){
      //obtener la ronda a la que pertenece el partido
//      print "***************************************<br>id_equipo:$id_equipo<br>id_evento:$id_evento<br>i:$i<br>";
      $query_partidos="SELECT DISTINCT id_partido,id_equipo1,id_equipo2,goles1,goles2 FROM partidos
      				WHERE (id_equipo1=:id_equipo1 OR id_equipo2=:id_equipo2) AND goles1<>'-1' AND id_evento=:id_evento and ronda=:i";

//print "query=$query_partidos<br>";
      $stmt_partidos= $db->prepare($query_partidos,array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	  $stmt_partidos->bindParam(':id_equipo1',$id_equipo);
	  $stmt_partidos->bindParam(':id_equipo2',$id_equipo);
	  $stmt_partidos->bindParam(':id_evento',$id_evento);
	  $stmt_partidos->bindParam(':i',$i);
	  $stmt_partidos->execute();

      while ($row=$stmt_partidos->fetch(PDO::FETCH_ASSOC)){
         $id_equipo1=$row['id_equipo1'];
         $id_equipo2=$row['id_equipo2'];
         $goles1=$row['goles1'];
         $goles2=$row['goles2'];
         $id_part=$row['id_partido'];

//print "id_partido=$id_part";
         $pj++;


         if ($id_equipo1==$id_equipo){
            $gf+=$goles1;
            $gc+=$goles2;
            if ($goles1>$goles2){ //gano
               $pg++;
               $ptos+=3;
//print "......gano partido...";
            }else if ($goles1<$goles2){  //perdio
               $pp++;
//print "......perdio partido...";
            }else{             //empato
               $pe++;
               $ptos+=1;
//print "......empató partido...";
            }
         }else{
            $gf+=$goles2;
            $gc+=$goles1;
            if ($goles2>$goles1){ //gano
               $pg++;
               $ptos+=3;
//print "......gano partido...";
            }else if ($goles2<$goles1){  //perdio
               $pp++;
//print "......perdio partido...";
            }else{             //empato
               $pe++;
               $ptos+=1;
//print "......empató partido...";
            }
         }
         $gd=$gf-$gc;
     }
     $num=$stmt_partidos->rowCount();
     if ($num>0){
        $query_update="UPDATE gruposxevento SET pj=:pj,pg=:pg,pp=:pp,pe=:pe,gf=:gf,gc=:gc,gd=:gd,ptos=:ptos
        				WHERE id_equipo=:id_equipo AND id_evento=:id_evento AND num_ronda=:i";
        $stmt_update= $db->prepare($query_update);
		$stmt_update->bindParam(':pj',$pj);
		$stmt_update->bindParam(':pg',$pg);
		$stmt_update->bindParam(':pp',$pp);
		$stmt_update->bindParam(':pe',$pe);
		$stmt_update->bindParam(':gf',$gf);
		$stmt_update->bindParam(':gc',$gc);
		$stmt_update->bindParam(':gd',$gd);
		$stmt_update->bindParam(':ptos',$ptos);
		$stmt_update->bindParam(':id_equipo',$id_equipo);
		$stmt_update->bindParam(':id_evento',$id_evento);
		$stmt_update->bindParam(':i',$i);
		$stmt_update->execute();
        //print "<br> -->update=$query_update<br><br>";
     }
     $i++;

  }
}

//print_r($_POST);
require 'includes/Close-Connection.php';
if (!headers_sent() && $msg == '') {
    $redirect="index.php?accion=evento_admin&id_evento=$id_evento&accion2=actualizar_marcadores";
     header('Location: '.$redirect);
}

?>
