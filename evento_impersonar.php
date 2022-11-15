<?
//session_start();
$fecha=$_POST['fecha'];



require_once 'includes/class_equipo.php';

$eq_obj=new equipo($db);


?>

<center>
<h2>Registrar apuesta por un usuario</h2>

<?
 if (isset($_SESSION['msg'])){
	 echo $_SESSION['msg'];
	 unset($_SESSION['msg']);
 }
?>

<form name="impersonar" action="apostar_registrar.php" method="POST">
<table class="tabla_simple">
<tr><th>Usuario
<td><SELECT name="id_usuario_suplantado">
<?
$query="SELECT id_usuario,usuario FROM usuarios WHERE id_usuario IN (SELECT id_usuario FROM usuariosxevento WHERE id_evento=:id_evento) order by usuario ASC";
$stmt= $db->prepare($query);
$stmt->bindParam(':id_evento',$id_evento);
$stmt->execute();
While ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	$id_usuario=$row['id_usuario'];
	$usuario=$row['usuario'];

	print "<option value=\"$id_usuario\">$usuario</option>\n";
}
?>

</SELECT>
<tr><th>Partido
    <td><SELECT name="id_partido">
<?
$query="SELECT id_partido,id_equipo1,id_equipo2
        FROM partidos
		WHERE id_evento=:id_evento
		AND editable='1'
		ORDER BY fecha, hora";
//print "q=$query<br>";
$stmt= $db->prepare($query);
$stmt->bindParam(':id_evento',$id_evento);
$stmt->execute();
While ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	$id_partido=$row['id_partido'];
	$eq1=$row['id_equipo1'];
	$nombre_eq1=$eq_obj->get_nombre($eq1);
	$eq2=$row['id_equipo2'];
	$nombre_eq2=$eq_obj->get_nombre($eq2);

	print "<option value=\"$id_partido\">$nombre_eq1 vs $nombre_eq2</option>\n";
}

?>
        </SELECT>
<tr><th>Equipo1
    <td><input type="number" name="goles1" min="0" required>
<tr><th>Equipo2
    <td><input type="number" name="goles2" min="0" required>
<tr><th colspan=2 style="text-align:center"><input type="submit" value="Registrar">
</table>
</form>
</center>
