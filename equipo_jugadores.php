<?
session_start();
include 'includes/_Policy.php';
require_once 'includes/class_jugador.php';
require_once 'includes/class_equipo.php';

$id_equipo=$_REQUEST['id_equipo'];
$eq_obj= new equipo($db);
$img=$eq_obj->get_imagen($id_equipo);
$nombre_equipo=$eq_obj->get_nombre($id_equipo);

?>

<center>

<h2>Jugadores del equipo</h2><br>

<table>
<tr><td><h2><? echo $nombre_equipo; ?></h2><td><img src="<? echo $img; ?>" class="img_thumb">
</table>




<div id="tabla_jugadores">
<table class="tabla_con_encabezado">
<tr>
   <th>#<th>Nombre<th>Foto
<?
$query="SELECT id_jugador FROM jugadores WHERE id_equipo='$id_equipo' AND activo='1' ORDER BY posicion,nombre ASC";
//print "q=$query<br>";
$stmt = $db->query($query);
if ($stmt->rowCount()==0){
	print "<tr><td colspan=\"3\" style=\"text-align:center\">No se encontraron jugadores\n";
}else{
   $i=1;
   while ($row=$stmt->fetch(PDO::FETCH_ASSOC)){
	   $id_jugador=$row['id_jugador'];
	   $jugador=new jugador($id_jugador);
	
	   print "<tr><td>$i<td>";
	   if ($admin){
		   print "<a href=\"index.php?accion=jugador_editar&id_jugador=$id_jugador\">$jugador->nombre</a>";
	   }else{
		   print "$jugador->nombre"; 
	   }
	   print "<td><img src=\"".$jugador->get_imagen()."\" class=\"img_thumb_big\">\n";
	   $i++;
   }
}   

?>   

</table>
</div>
<a href="index.php?accion=editar_equipo&id_equipo=<? echo $id_equipo; ?>"><input type="button" value="Volver al equipo"></a>
</center>