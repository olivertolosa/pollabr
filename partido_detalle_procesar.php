<?php


session_start();
include 'includes/_Policy.php';
require_once 'audit.php';
audit_max();

include 'includes/Open-Connection.php';
include 'includes/class_partido.php';

$partido=new partido($db);

$id_partido=$_POST['id_partido'];
$id_equipo1=$_POST['eq1'];
$id_equipo2=$_POST['eq2'];
$fecha=$_POST['fecha'];
$hora=$_POST['hora'];
$editable=$_POST['editable'];
$ronda=$_POST['ronda'];
$goles1=$_POST['goles1'];
$goles2=$_POST['goles2'];
$enprogreso=$_POST['enprogreso'];
$marcaval=$_POST['marcaval'];

($marcaval)? $marcaval=1 : $marcaval=0;


//eliminar marca de partido en progreso e incluirla si está marcado
$query="DELETE FROM partidos_iniciados WHERE id_partido=:id_partido";
$stmt= $db->prepare($query);
$stmt->bindParam(':id_partido',$id_partido);
$stmt->execute();

if ($enprogreso){   $query="INSERT INTO partidos_iniciados VALUES(:id_partido)";
   $stmt= $db->prepare($query);
	$stmt->bindParam(':id_partido',$id_partido);
	$stmt->execute();
}


//validar si el partido es de polla o bolsa
$query="SELECT tipo_e,editable,id_evento FROM partidos WHERE id_partido=:id_partido";
$stmt= $db->prepare($query);
$stmt->bindParam(':id_partido',$id_partido);
$stmt->execute();
$row=$stmt->fetch(PDO::FETCH_ASSOC);

$tipo_e=$row['tipo_e'];
$editable_original=$row['editable'];
$id_evento=$row['id_evento'];


($editable)? $editable=1 : $editable=0;

($editable)? $editable=1 : $editable=0;

if ($tipo_e=='e'){
  $query="UPDATE partidos SET id_equipo1=:id_equipo1,id_equipo2=:id_equipo2,fecha=:fecha, hora=:hora,ronda=:ronda,editable=:editable,goles1=:goles1,goles2=:goles2
        WHERE id_partido=:id_partido";

  $redirect="index.php?accion=evento_admin&id_evento=$id_evento&accion2=editar_partido&id_partido=$id_partido";
}else if ($tipo_e=='b'){
  $query="UPDATE partidos SET id_equipo1=:id_equipo1,id_equipo2=:id_equipo2,fecha=:fecha, hora=:hora,goles1=:goles1,goles2=:goles2,marcaval=:marcaval
        WHERE id_partido=:id_partido";

  $redirect="index.php?accion=bolsa_admin&id_bolsa=$id_evento&accion2=editar_partido&id_partido=$id_partido";
}

$stmt= $db->prepare($query);
$stmt->bindParam(':id_equipo1',$id_equipo1);
$stmt->bindParam(':id_equipo2',$id_equipo2);
$stmt->bindParam(':fecha',$fecha);
$stmt->bindParam(':hora',$hora);
$stmt->bindParam(':goles1',$goles1);
$stmt->bindParam(':goles2',$goles2);
if ($tipo_e=='e'){	$stmt->bindParam(':ronda',$ronda);
	$stmt->bindParam(':editable',$editable);
}else if ($tipo_e=='b'){	$stmt->bindParam(':marcaval',$marcaval);
}
$stmt->bindParam(':id_partido',$id_partido);
$stmt->execute();

//print "q=$query<br>";

if ($tipo_e=='e'){
    //poner marcadores aleatorios para los q no apostaron
   if ($editable_original=='1' and $editable=='0'){
      $query_usuarios="SELECT id_usuario FROM usuarios WHERE id_usuario IN (SELECT id_usuario FROM usuariosxevento WHERE id_evento=:id_evento)";
      $stmt_usuarios= $db->prepare($query_usuarios);
	  $stmt_usuarios->bindParam(':id_evento',$id_evento);
	  $stmt_usuarios->execute();
	  while($row_usuarios = $stmt_usuarios->fetch(PDO::FETCH_ASSOC)) {
   	     $id_usuario=$row_usuarios['id_usuario'];
         $query_apuesta="SELECT * FROM apuestas WHERE id_usuario=:id_usuario AND id_partido=:id_partido";
		 $stmt= $db->prepare($query_apuesta,array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		 $stmt->bindParam(':id_usuario',$id_usuario);
		 $stmt->bindParam(':id_partido',$id_partido);
		 $stmt->execute();

         $num=$stmt->rowCount();
         if ($num==0){
            $hoy=date('Y-m-d H:i');
            $marcador1=rand(0,4);
            $marcador2=rand(0,4);
      	     $query_update="INSERT INTO apuestas VALUES(:id_usuario,:id_partido,:marcador1,:marcador2,:hoy,'1')";
//   	     print "q=$query_update<br>";
   	         $stmt_update= $db->prepare($query_update);
			 $stmt_update->bindParam(':id_usuario',$id_usuario);
			 $stmt_update->bindParam(':id_partido',$id_partido);
			 $stmt_update->bindParam(':marcador1',$marcador1);
			 $stmt_update->bindParam(':marcador2',$marcador2);

         }
      }

      //print "query=$query<br>";
     $stmt_update->execute();
   }
}
$_SESSION['msg']="<span class=\"msg_ok\">Partido Modificado</span>";
include 'includes/Close-Connection.php';

if (!headers_sent() && $msg == '') {

      header('Location: '.$redirect);
}


?>

</body>

</html>