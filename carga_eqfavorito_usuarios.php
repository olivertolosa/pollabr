<?
session_start();

require_once 'includes/Open-Connection.php';
include 'audit.php';
include 'includes/class_usuario.php';

$usuario_obj=new usuario($db);

$id_equipo=$_GET['id_equipo'];


$query="SELECT id_usuario FROM equipos_favoritos WHERE id_equipo='$id_equipo'";
//print "q=$query<br>";

$stmt = $db->query($query);
print "<center><table class=\"tabla_simple\"><tr><th colspan=\"2\">Usuario";
while ($row=$stmt->fetch(PDO::FETCH_ASSOC)){
      $id_usuario=$row['id_usuario'];
      print "<tr><td><img src=\"".$usuario_obj->get_imagen($id_usuario)."\" class=\"img_thumb\"><td>".$usuario_obj->get_usuario($id_usuario);

}
print"</table></center>";

include 'includes/Close-Connection.php';
?>
</center>
