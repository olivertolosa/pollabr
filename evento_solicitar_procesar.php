<?php

session_start();
//include 'includes/_Policy.php';
require 'includes/Open-Connection.php';
require_once 'audit.php';
audit_max();

$id_usuario=$_SESSION['usuario_polla'];


$evento=$_POST['evento'];
$descripcion=$_POST['descripcion'];
$max_usuarios=$_POST['max_usuarios'];
$publica=$_POST['publica'];
($publica) ? $publica=1 : $publica=0;

$fecha_inicio=$_POST['fecha_inicio'];
$fecha_fin=$_POST['fecha_fin'];


//validar que el evento no exista
$query="SELECT evento FROM eventos WHERE evento='$evento'";
//print "q=$query<br>";
$stmt = db->query($query);
if ($stmt->rowCount()>0){   $_SESSION['msg']="Error: Ya existe un evento con ese nombre!!";
   $redirect="index.php?accion=evento_nuevo";
   header('Location: '.$redirect);
   exit();
}

$query="INSERT INTO eventos_solicitados VALUES('','$id_usuario','$evento','$descripcion','$max_usuarios','$publica','$fecha_inicio','$fecha_fin')";
//print "query=$query<br>";
$db->query($query);
$id_evento_new=$db->lastInsertId();


$_SESSION['msg']="<span class=\"msg_ok\">Solcitud de creación de evento enviada</span>";
require 'includes/Close-Connection.php';

$redirect="index.php";

if (!headers_sent() && $msg == '') {
     header('Location: '.$redirect);
}
?>
