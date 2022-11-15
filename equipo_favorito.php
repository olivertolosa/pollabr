<?php
$id_usuario=$_GET['id_usuario'];
$id_equipo=$_GET['id_equipo'];

include 'includes/Open-Connection.php';

//validar si el equipo ya es favorito

$query="SELECT * FROM equipos_favoritos WHERE id_usuario='$id_usuario' AND id_equipo='$id_equipo'";
$stmt=$db->query($query);


$num=$stmt->rowCount();

if ($num==0){   $query="INSERT INTO equipos_favoritos VALUES('$id_usuario','$id_equipo')";
   print "1";

}else{   $query="DELETE FROM equipos_favoritos WHERE id_usuario='$id_usuario' AND id_equipo='$id_equipo'";
   print "0";
}
$stmt=$db->query($query);


include 'includes/Close-Connection.php';
?>
