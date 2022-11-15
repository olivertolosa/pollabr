<?php

require 'includes/Open-Connection.php';
include 'includes/class_lamina.php';

$id_lamina=$_REQUEST['id_lamina'];
$lam=new lamina($db);

$nombre=$lam->get_nombre($id_lamina);

//devolver en JSON
$resp['nombre']=$nombre;

print json_encode($resp);

require 'includes/Close-Connection.php';
?>
