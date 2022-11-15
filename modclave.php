<?php
session_start();
include 'includes/_Policy.php';


$id_usuario=$_POST['id_usuario'];
$clave=$_POST['clave1'];

//remover bandera que obliga a cambio de clave
if ($clave!="")
   unset($_SESSION['cambia_clave']);

$clave=md5($clave);

require 'includes/Open-Connection.php';
$query="UPDATE usuarios SET password='$clave' WHERE id_usuario='$id_usuario'";
$result = mysql_query($query) or die(mysql_error());

$_SESSION['msg']="<span class=\"msg_ok\">Clave modificada</span>";

//setear el id del usuario si no se ha hecho

if (!isset($_SESSION['usuario_polla'])){   $_SESSION['usuario_polla']="$id_usuario";
}

require 'includes/Close-Connection.php';
if (!headers_sent() && $msg == '') {
    $redirect="index.php?accion=micuenta";
      header('Location: '.$redirect);
}
?>
