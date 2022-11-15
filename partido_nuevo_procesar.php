<?php

session_start();
include 'includes/_Policy.php';
include 'includes/Open-Connection.php';
require_once 'audit.php';
audit_max();

//print_r($_POST);

//exit();

//Tipo de Evento (polla o bolsa)
if ($_REQUEST['id_evento']>0){
   $tipo_e="e";
   $id_evento=$_POST['id_evento'];
   $id_even=$id_evento;
   $redirect="index.php?accion=evento_admin&id_evento=$id_evento&accion2=listar_partidos";
}else if ($_REQUEST['id_bolsa']>0){
   $tipo_e="b";
   $id_bolsa=$_POST['id_bolsa'];
   $id_even=$id_bolsa;
   $redirect="index.php?accion=bolsa_admin&id_bolsa=$id_bolsa&accion2=listar_partidos";
}



$num_rows=$_POST['num_rows'];



function id_partido_nuevo(){
   global $db;
   //calcular el id
   $query="SELECT max(id_partido)as p FROM partidos";
   $stmt= $db->prepare($query);
   $stmt->execute();
   $row=$stmt->fetch(PDO::FETCH_ASSOC);
   $id_originales=$row['p'];

   $query="SELECT max(id_partido)as pc FROM partidos_clon";
   $stmt= $db->prepare($query);
   $stmt->execute();
   $row=$stmt->fetch(PDO::FETCH_ASSOC);
   $id_clones=$row['pc'];
	($id_originales>$id_clones) ? $id_partido=$id_originales+1 : $id_partido=$id_clones+1;

	return $id_partido;
}



   for ($i=1 ; $i<= $num_rows ; $i++){
      $id_equipo1=$_POST['eq1-'.$i];
      $id_equipo2=$_POST['eq2-'.$i];
      $fecha=$_POST['fecha-'.$i];
      $hora=$_POST['hora-'.$i];
      $ronda=$_POST['ronda-'.$i];




//print_r($_POST);

//validar que los 2 equipos sean diferentes
      if ($id_equipo1==$id_equipo2){         $_SESSION['msg']="<span class=\"msg_error\">Debe seleccionar 2 equipos diferentes</span>";
         $redirect="index.php?accion=evento_admin&id_evento=$id_evento&accion2=partido_nuevo";
      }else{
	      $id_partido=id_partido_nuevo();

          $query="SET IDENTITY_INSERT partidos ON; INSERT INTO partidos (id_partido,id_evento,tipo_e,id_equipo1,id_equipo2,goles1,goles2,penales1,penales2,fecha,hora,ronda,editable,marcaval)
                                VALUES (:id_partido,:id_even,:tipo_e,:id_equipo1,:id_equipo2,'-1','-1','0','0',:fecha,:hora,:ronda,'1','0');";
//      print "query=$query<br>";
          $stmt= $db->prepare($query);
		  $stmt->bindParam(':id_partido',$id_partido);
		  $stmt->bindParam(':id_even',$id_even);
		  $stmt->bindParam(':tipo_e',$tipo_e);
		  $stmt->bindParam(':id_equipo1',$id_equipo1);
		  $stmt->bindParam(':id_equipo2',$id_equipo2);
		  $stmt->bindParam(':fecha',$fecha);
		  $stmt->bindParam(':hora',$hora);
		  $stmt->bindParam(':ronda',$ronda);
		  $stmt->execute();


          $_SESSION['msg']="<span class=\"msg_ok\">Partido Creado</span>";

          //si la ronda solo tiene un grupo ...incluir el equipo en el grupo A de la ronda.
          $query="SELECT grupos FROM rondasxevento WHERE id_evento=:id_evento AND num_ronda=:ronda";
          $stmt= $db->prepare($query);
		  $stmt->bindParam(':id_evento',$id_evento);
		  $stmt->bindParam(':ronda',$ronda);
		  $stmt->execute();

          $row=$stmt->fetch(PDO::FETCH_ASSOC);
          $grupos=$row['grupos'];

          if ($grupos==1){
     		  //validar si cada equipo ya está
   	          $query="SELECT * FROM gruposxevento WHERE id_equipo=:id_equipo1 AND num_ronda=:ronda";
              $stmt= $db->prepare($query,array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		  	  $stmt->bindParam(':id_equipo1',$id_equipo1);
		  	  $stmt->bindParam(':ronda',$ronda);
		  	  $stmt->execute();
        	  if ($stmt->rowCount()==0){
				$query="INSERT INTO gruposxevento VALUES(:id_evento,:id_equipo1,:ronda,'A','0','0','0','0','0','0','0','0')";
   	            $stmt= $db->prepare($query);
		  		$stmt->bindParam(':id_evento',$id_evento);
				$stmt->bindParam(':id_equipo1',$id_equipo1);
				$stmt->bindParam(':ronda',$ronda);
				$stmt->execute();
              }
   	          $query="SELECT * FROM gruposxevento WHERE id_equipo=:id_equipo2 AND num_ronda=:ronda";
   	          $stmt= $db->prepare($query);
			  $stmt->bindParam(':id_equipo2',$id_equipo2);
			  $stmt->bindParam(':ronda',$ronda);
			  $stmt->execute();
   	          if ($stmt->rowCount()==0){
   	             $query="INSERT INTO gruposxevento VALUES(:id_evento,:id_equipo2,:ronda,'A','0','0','0','0','0','0','0','0')";
   	            $stmt= $db->prepare($query);
		  		$stmt->bindParam(':id_evento',$id_evento);
				$stmt->bindParam(':id_equipo2',$id_equipo2);
				$stmt->bindParam(':ronda',$ronda);
				$stmt->execute();

               }
          }
      }


      //validar si él evento es plantilla de alguien mas
      $query="SELECT id_evento FROM plantillas_eventos WHERE id_evento=:id_evento";
	  $stmt= $db->prepare($query,array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
 	  $stmt->bindParam(':id_evento',$id_evento);
	  $stmt->execute();
      if ($stmt->rowCount()>0){
        	// el evento es plantilla --> crear los partidos clones para los eventos que usan la plantilla
     	$query_eventos="SELECT id_evento FROM eventos WHERE plantilla=:id_evento";
	  	$stmt_eventos= $db->prepare($query_eventos);
 	  	$stmt_eventos->bindParam(':id_evento',$id_evento);
	  	$stmt_eventos->execute();

	    while ($row_eventos=$stmt_eventos->fetch(PDO::FETCH_ASSOC)){
		    $id_event=$row_eventos['id_evento'];
	        $id_partido_clon=id_partido_nuevo();
	        $query="INSERT INTO partidos_clon VALUES(:id_partido_clon,:id_partido,:id_event)";
		    $stmt_eventos= $db->prepare($query_eventos);
 	  		$stmt_eventos->bindParam(':id_partido_clon',$id_partido_clon);
 	  		$stmt_eventos->bindParam(':id_partido',$id_partido);
 	  		$stmt_eventos->bindParam(':id_event',$id_event);
	  		$stmt_eventos->execute();
	    }
      }

   }



   include 'includes/Close-Connection.php';
if (!headers_sent() && $msg == '') {
      header('Location: '.$redirect);
}


?>
