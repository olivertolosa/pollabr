<?php

session_start();
//include 'includes/_Policy.php';
require 'includes/Open-Connection.php';
require_once 'audit.php';
audit_max();


$accion=$_REQUEST['accion'];

$id_mensaje=$_REQUEST['id_mensaje'];
$fecha=$_POST['fecha'];
$titulo=$_POST['titulo'];
$titulo=addslashes($titulo);
$mensaje=$_POST['mensaje'];
$mensaje=addslashes($mensaje);
$mensaje=nl2br($mensaje);
$categoria=$_POST['categoria'];

if ($accion=="eliminar_mensaje"){   $query="DELETE FROM mensajes WHERE id_mensaje='$id_mensaje'";
   $db->query($query);

   $_SESSION['msg']="<span class=\"msg_ok\">Mensaje Eliminado</span>";

   $redirect="index.php?accion=listar_mensajes";
   header('Location: '.$redirect);
   exit();
}

$query="UPDATE mensajes set fecha='$fecha',titulo='$titulo',mensaje='$mensaje',categoria='$categoria' WHERE id_mensaje='$id_mensaje'";
$db->query($query);

$_SESSION['msg']="<span class=\"msg_ok\">Mensaje Modificado</span>";
require 'includes/Close-Connection.php';


if (!headers_sent()) {
    $redirect="index.php?accion=editar_mensaje&id_mensaje=$id_mensaje";
     header('Location: '.$redirect);
}
?>
