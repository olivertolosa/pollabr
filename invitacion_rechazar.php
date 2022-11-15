<?php

session_start();
//include 'includes/_Policy.php';
require 'includes/Open-Connection.php';


$id_evento=$_REQUEST['id_evento'];
$id_usuario=$_SESSION['usuario_polla'];

$query="DELETE FROM invitaciones WHERE id_usuario='$id_usuario' AND id_evento='$id_evento'";
//print "q=$query";
$db->query($query);

$_SESSION['msg']="<span class=\"msg_ok\">Invitación Eliminada</span>";

require 'includes/Close-Connection.php';


if (!headers_sent()) {
   $redirect="index.php";
   header('Location: '.$redirect);
}
?>
