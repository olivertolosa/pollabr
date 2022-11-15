<?php
session_start();
include 'includes/_Policy.php';
require 'includes/Open-Connection.php';

//print_r($_POST);


$id_evento=$_POST['id_evento'];
$nombre_plantilla=$_POST['nombre_plantilla'];

if ($nombre_plantilla!=''){
   $query="INSERT INTO plantillas_eventos VALUES ('$id_evento','$nombre_plantilla')";
   $db->query($query);
}

$_SESSION['msg']="<span class=\"msg_ok\">Plantilla Creada</span>";

require 'includes/Close-Connection.php';

$redirect="index.php?accion=evento_detalle&id_evento=$id_evento";


if (!headers_sent() && $msg == '') {
     header('Location: '.$redirect);
}
?>
