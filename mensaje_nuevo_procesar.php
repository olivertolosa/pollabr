<?php

session_start();
//include 'includes/_Policy.php';
require 'includes/Open-Connection.php';
require_once 'audit.php';
audit_max();


$fecha=$_POST['fecha'];
$titulo=$_POST['titulo'];
$titulo=addslashes($titulo);
$mensaje=$_POST['mensaje'];
$mensaje=addslashes($mensaje);
$mensaje=nl2br($mensaje);
$categoria=$_POST['categoria'];

$query="INSERT INTO mensajes VALUES(NULL,:fecha,:titulo,:mensaje,:categoria)";
$stmt= $db->prepare($query);
$stmt->bindParam(':fecha',$fecha);
$stmt->bindParam(':titulo',$titulo);
$stmt->bindParam(':mensaje',$mensaje);
$stmt->bindParam(':categoria',$categoria);
$stmt->execute();

$id_mensaje=$db->lastInsertId();

$_SESSION['msg']="<span class=\"msg_ok\">Mensaje Creado</span>";
require 'includes/Close-Connection.php';


if (!headers_sent()) {
    $redirect="index.php?accion=listar_mensajes";
     header('Location: '.$redirect);
}
?>
