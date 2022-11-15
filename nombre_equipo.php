<?php

require 'includes/Open-Connection.php';
include 'includes/class_equipo.php';

$id_equipo=$_POST['id_equipo'];

$equipo=new equipo();

$nombre=$equipo->get_nombre($id_equipo);

print $nombre;

?>
