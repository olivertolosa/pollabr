<?
$id_liga=$_GET['id_liga'];
require_once 'includes/Open-Connection.php';
require_once 'includes/class_liga.php';

$liga=new liga($db);

$imagen=$liga->get_imagen($id_liga);

print "<img src=\"$imagen\" style=\"max-width:180px; max-height:80px\">  \n";


$query="SELECT grupo_equipos FROM grupos_equipos WHERE id_grupo_equipos='$id_liga'";
$stmt=$db->query($query);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$nombre_liga=$row['grupo_equipos'];


require_once 'includes/Open-Connection.php';
?>
<script>
cambia_nombre_torneo('<? echo $nombre_liga ?>');
</script>

