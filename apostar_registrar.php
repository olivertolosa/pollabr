<?php
session_start();
include 'includes/_Policy.php';
require 'includes/Open-Connection.php';
require_once 'includes/class_equipo.php';
require_once 'audit.php';
audit_max();
require_once 'includes/class_partido.php';

$debug=false;

$partidoobj=new partido($db);
$equipo=new equipo($db);

if ($debug) print_r ($_REQUEST);  print "<br>";


$hoy=date('Y-m-d H:i');
$monas=0;
$id_album=$_SESSION['id_album'];

$id_evento=$_POST['id_evento'];

if (isset($_REQUEST['id_usuario_suplantado'])){

   $id_usuario=$_REQUEST['id_usuario_suplantado'];
   $id_partido=$_REQUEST['id_partido'];
   $goles1=$_REQUEST['goles1'];
   $goles2=$_REQUEST['goles2'];

   if ($debug) print "impersonando usuario $id_usuario<br>";

   //valiadr si ya existe un marcador para ese partido
   $query_validacion="SELECT fecha_aposto FROM apuestas WHERE id_partido=:id_partido AND id_usuario=:id_usuario";
	$stmt_validacion= $db->prepare($query_validacion,array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt_validacion->bindParam(':id_partido',$id_partido);
	$stmt_validacion->bindParam(':id_usuario',$id_usuario);
	$stmt_validacion->execute();
   $num_resultados=$stmt_validacion->rowCount();

   if ($num_resultados>0){
	   $query="UPDATE apuestas SET equipo1=:goles1,equipo2=:goles2, fecha_aposto=:hoy,aleatorio='0' WHERE id_partido=:id_partido AND id_usuario=:id_usuario";
   }else{
      $query="INSERT INTO apuestas VALUES (:id_usuario,:id_partido,:goles1,:goles2,:hoy,'0')";
   }

   if ($debug) print "query=$query<br>parámetros:<br>goles1:$goles1<br>goles2:$goles2<br>hoy:$hoy<br>id_partido:$id_partido<br>id_usuario:$id_usuario<br>";

   $stmt= $db->prepare($query);
   $stmt->bindParam(':goles1',$goles1);
   $stmt->bindParam(':goles2',$goles2);
   $stmt->bindParam(':hoy',$hoy);
   $stmt->bindParam(':id_partido',$id_partido);
   $stmt->bindParam(':id_usuario',$id_usuario);
   $stmt->execute();


   $nom_eq1=$equipo->get_nombre($partidoobj->get_info_eq1_partido($id_partido));
   $nom_eq2=$equipo->get_nombre($partidoobj->get_info_eq2_partido($id_partido));

   $query="SELECT usuario FROM usuarios WHERE id_usuario=:id_usuario";
   $stmt= $db->prepare($query);
   $stmt->bindParam(':id_usuario',$id_usuario);
   $stmt_validacion->execute();

   $row = $stmt->fetch(PDO::FETCH_ASSOC);
   $usuario_suplantado=$row['usuario'];

   audit($_SESSION['usuario_polla'],"Impersonando usuario","usuario:$usuario_suplantado, id_partido:$id_partido, $nom_eq1:$goles1, $nom_eq2:$goles2");
   //print "q=$query<br>";

   require 'includes/Close-Connection.php';
   $_SESSION['msg']="<span class=\"msg_ok\">Marcadores Registrados</span>";
   $id_evento=$partidoobj->get_id_evento($id_partido);

   //if (!headers_sent()) {
      $redirect="index.php?accion=evento_admin&id_evento=$id_evento&accion2=impersonar";
      if (!$debug) header('Location: '.$redirect);
	  exit();
   //}

}else
   $id_usuario=$_SESSION['usuario_polla'];
//print_r($_POST);

foreach($_REQUEST as $key => $valor){


  if (is_numeric($valor) and $valor>=0 and $key[0]=='p'){ //viene un marcador



  	  $id_partido=substr($key,1,strpos($key,"-")-1);

  	  if ($debug) print "<br>*********************<br>Analizando partido $id_partido<br> $key => $valor<br>";

  	 //validar si el partido es editable
  	 $editable=$partidoobj->editable($id_partido);
//print "partido->$id_partido.....editable=$editable<br>\n";

     //validar que la fecha actual sea previa a la del partido
	 $fecha=date('Y-m-d');
     $hora=date('H:i');
     $minutos=substr($hora,strpos($hora,":")+1,2);
     $hora=substr($hora,0,2);

     $time=($hora*60)+$minutos;

     $query_b="SELECT fecha,hora FROM partidos WHERE id_partido=:id_partido";
//print "q=$query_b<br>";
     $stmt_b= $db->prepare($query_b);
     $stmt_b->bindParam(':id_partido',$id_partido);
     $stmt_b->execute();


     $row_b=$stmt_b->fetch(PDO::FETCH_ASSOC);
	 $fecha_partido=$row_b['fecha'];
     $hora_partido=$row_b['hora'];
     $minutos_partido=substr($hora_partido,strpos($hora_partido,':')+1,2);
     $hora_partido=substr($hora_partido,0,2);
     $time_partido=($hora_partido*60)+$minutos_partido;
     $bloquear=false;


if ($debug) print "fecha; $fecha.....fecha_partido=$fecha_partido<br>";
if ($debug) print "hora_partido; $hora_partido.....minutos_partido=$minutos_partido<br>";
if ($debug)print "time:$time.......time_partido:$time_partido<br>";

    if ($fecha>$fecha_partido){
    	if ($debug) print "APUESTA FUERA DE HORARIO!!!!....FECHA MAL!!!<BR>";
	   $bloquear=true;
       audit($id_usuario,$audit,"Se detectó apuesta en horario no permitido...el día ya pasó...usuario: $id_usuario, partido: $id_partido\r\n");
    }else if ($fecha==$fecha_partido){
	     print "fecha igual a la del partido<br>";
	     if ($time>=$time_partido){
			   $bloquear=true;
			   if ($debug) print "APUESTA FUERA DE HORARIO!!!!....HORA MAL!!!<BR>";
               audit($id_usuario,$audit,"Se detectó apuesta en horario no permitido...usuario: $id_usuario, partido: $id_partido\r\n");
		 }
	 }

  	 if (!$editable or $bloquear){
  	 	$alerta_editable=TRUE;
  	 }else{
  	 	if ($debug) print "Condiciones aprobadas para hacer la apuesta<br>validando partido:$id_partido para usuario:$id_usuario";
     	 //valiadr si ya existe un marcador para ese partido
     	 $query_validacion="SELECT fecha_aposto FROM apuestas WHERE id_partido=:id_partido AND id_usuario=:id_usuario";
         $stmt_validacion= $db->prepare($query_validacion,array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
     	 $stmt_validacion->bindParam(':id_partido',$id_partido);
     	 $stmt_validacion->bindParam(':id_usuario',$id_usuario);

         if ($debug) print "<br>query para ver si hay apuesta:"; $stmt_validacion->debugDumpParams();

     	 $stmt_validacion->execute();



     	 $num_resultados=$stmt_validacion->rowCount();

if ($debug) print "<br><br>num_resultados: $num_resultados<br>";

     	 $numero_equipo=substr($key,strlen($key)-1);

 if ($debug) print "partido=$id_partido...equipo=$numero_equipo...marcador=$valor<br>";
     	 if ($numero_equipo==1){
     	 	if ($num_resultados>0){
  	    	   $query="UPDATE apuestas SET equipo1=:equipo1";
  	    	   $audit="actualizar apuesta";
  	    	   $eq1=$valor;
  	 	   }else{
        	   $query="INSERT INTO apuestas VALUES (:id_usuario,:id_partido,:equipo1";
               $audit="Registrar apuesta";
               //asignar monita
               $monas++;
               $eq1=$valor;
           }
     	 }else if ($numero_equipo==2){

  	 	   if ($num_resultados>0){
  	 	      $query.=",equipo2=:equipo2, fecha_aposto=:hoy,aleatorio='0' WHERE id_partido=:id_partido AND id_usuario=:id_usuario";
  	 	      $eq2=$valor;
     	   }else
       	      $query.=",:equipo2,:hoy,'0')";
       	      $eq2=$valor;
print "q=$query<br>";
            $nom_eq1=$equipo->get_nombre($partidoobj->get_info_eq1_partido($id_partido));
            $nom_eq2=$equipo->get_nombre($partidoobj->get_info_eq2_partido($id_partido));
            audit($id_usuario,$audit,"id_partido:$id_partido, $nom_eq1:$eq1, $nom_eq2:$eq2");


	        $stmt= $db->prepare($query);
     	 	$stmt->bindParam(':id_partido',$id_partido);
     	 	$stmt->bindParam(':equipo1',$eq1);
     	 	$stmt->bindParam(':equipo2',$eq2);
     	 	$stmt->bindParam(':id_usuario',$id_usuario);
     	 	$stmt->bindParam(':hoy',$hoy);
     	 	$stmt->execute();

     	 }

     	 $_SESSION['msg']="<span class=\"msg_ok\">Marcadores Registrados</span>";
     }
  }

}

//print "monas:$monas<br>";
if ($monas>0 and $habilitar_albums){
//   print "entregando monas $monas";
   require_once 'function_entrega_sobres.php';
  // entregar_sobres($id_usuario,$id_album,$monas,"registro de apuesta");
}



if ($alerta_editable)
   $_SESSION['msg']="<span class=\"msg_error\">Al menos un partido de los solicitados no es modificable en este momento!!!</span>";

//print_r($_POST);
require 'includes/Close-Connection.php';
if (!headers_sent() && $msg == '') {
  //  $redirect="index.php?accion=apostar&id_evento=$id_evento";
  // if (!$debug) header('Location: '.$redirect);
}

?>
