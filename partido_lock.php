<?php
session_start();
include '_Policy.php';
require 'includes/Open-Connection.php';

$id_partido=$_GET['id_partido'];
$lock=$_GET['lock'];

//obtener el id del evento a la q pertenece el partido y si ya estaba bloqueado para no vovler a generar apuestas aleatorias
$query="SELECT id_evento,editable FROM partidos WHERE id_partido='$id_partido'";
$stmt=$db->query($query);
$row=$stmt->fetch(PDO::FETCH_ASSOC);
$id_evento=$row['id_evento'];
$editable=$row['editable'];

//validar que el usuario si sea administrador del evento...o administrador global
//print_r($_SESSION);
$puede_modificar=false;
$admin=$_SESSION['admin'];
if ($admin){
   $puede_modificar=true;
}else{
   $query="SELECT admin FROM eventos WHERE id_evento='$id_evento'";
   $stmt=$db->query($query);
   $row=$stmt->fetch(PDO::FETCH_ASSOC);
   $admin=$row['admin'];
   if ($admin==$_SESSION['usuario_polla']){   	   $puede_modificar=true;
   }
}

if ($puede_modificar){	$query="UPDATE partidos SET editable='$lock' WHERE id_partido='$id_partido'";
	$db->query($query);    $_SESSION['msg']="<span class=\"msg_ok\">Partido Modificado</span>";

}else{
   $_SESSION['msg']="<span class=\"msg_error\">Acceso No Autorizado</span>";
}

if ($lock==0 and $editable){
   include 'set_aleatorio.php';
}

require 'includes/Close-Connection.php';
if (!headers_sent() && $msg == '') {
       $redirect="index.php?accion=evento_admin&id_evento=$id_evento&accion2=listar_partidos";
       header('Location: '.$redirect);
   }
?>
