<?php

session_start();
include 'includes/_Policy.php';
require 'includes/Open-Connection.php';
require 'includes/class_usuario.php';
require_once 'audit.php';
audit_max();


$id_usuario=$_POST['id_usuario'];

$usuario=new usuario($id_usuario);
$usuario->set_clave('');

$_SESSION['msg']="<span class=\"msg_ok\">Clave Reseteada</span>";
require 'includes/Close-Connection.php';

if (!headers_sent() && $msg == '') {
    $redirect="index.php?accion=editar_usuario&id_usuario=$id_usuario";
      header('Location: '.$redirect);
}
?>
