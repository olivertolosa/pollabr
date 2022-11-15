<?php
session_start();
include 'includes/_Policy.php';
require 'includes/Open-Connection.php';

$id_apuesta=$_GET['id_apuesta'];
$lock=$_GET['lock'];

//print_r ($_GET);

//validar que el usuario si sea administrador del evento...o administrador global
//print_r($_SESSION);
$puede_modificar=false;
$admin=$_SESSION['admin'];
if ($admin){
   $puede_modificar=true;
}

if ($puede_modificar){	$query="UPDATE apuesta_directa SET editable='$lock' WHERE id_apuesta='$id_apuesta'";
//print "q=$query<br>";
	$db->query($query);    $_SESSION['msg']="<span class=\"msg_ok\">Apuesta Modificada</span>";

}else{
   $_SESSION['msg']="<span class=\"msg_error\">Acceso No Autorizado</span>";
}


require 'includes/Close-Connection.php';
if (!headers_sent() && $msg == '') {
       $redirect="index.php?accion=apuestad_listar";
       header('Location: '.$redirect);
   }
?>
