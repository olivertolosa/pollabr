<?php
include 'includes/Open-Connection.php';

$query="SELECT id_equipo,equipo FROM equipos where id_equipo";
$result = mysql_query($query) or die(mysql_error());
while ($row=mysql_fetch_assoc($result)){   $id_equipo=$row['id_equipo'];
   $equipo=$row['equipo'];

   $query2="UPDATE equipos SET equipoLS='$equipo' WHERE id_equipo='$id_equipo'";
   	$result2 = mysql_query($query2) or die(mysql_error());
}

include 'includes/Close-Connection.php';
?>

