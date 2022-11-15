<?
session_start();
include 'includes/_Policy.php';
?>
<center>
<table class="tabla_con_encabezado">
<tr>
   <th>Id<th>Equipo<th>Grupo<th>Escudo
<?php

include 'includes/Open-Connection.php';

$query="SELECT * FROM equipos";
$result = mysql_query($query) or die(mysql_error());
$cadena="";
$num=mysql_num_rows($result);
//print "num=$num<br>";
while($row=mysql_fetch_assoc($result)){
   $id_equipo=$row['id_equipo'];
   $equipo=$row['equipo'];
   $grupo=$row['grupo'];

// detectar la extensión de la bandera
if (file_exists("imagenes/logos_equipos/".$id_equipo.".png"))
   $extension=".png";
else if (file_exists("imagenes/logos_equipos/".$id_equipo.".PNG"))
   $extension=".PNG";
else if (file_exists("imagenes/logos_equipos/".$id_equipo.".jpg"))
    $extension=".jpg";
else if (file_exists("imagenes/logos_equipos/".$id_equipo.".JPG"))
    $extension=".JPG";
else if (file_exists("imagenes/logos_equipos/".$id_equipo.".bmp"))
    $extension=".bmp";
else if (file_exists("imagenes/logos_equipos/".$id_equipo.".BMP"))
    $extension=".BMP";

$imagen=$id_equipo.$extension;

   print "<tr><td>$id_equipo
              <td><a href=\"index.php?accion=editar_equipo&id_equipo=$id_equipo\">$equipo
              <td style=\"text-align: center;\">$grupo
              <td style=\"text-align: center;\"><img src=\"imagenes/logos_equipos/$imagen\" width=\"45\" height=\"45\">\n";
}

include 'includes/Close-Connection.php';

?>
</center>