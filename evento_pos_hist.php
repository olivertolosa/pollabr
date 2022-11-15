<?

session_start();

?>
<link rel="stylesheet" type="text/css" href="css/polla.css" />
<script src="imagemap.js" type="text/javascript"></script>
<?php



include 'includes/Open-Connection.php';

include 'includes/class_partido.php';
include 'includes/class_usuario.php';

$id_evento=$_GET['id_evento'];
$id_usuario=$_GET['id_usuario'];

$partido=new partido($db);
$usr=new usuario($id_usuario);


$query="SELECT posicion,puntos,marcadores_exactos,ganadorempate,marcador1 FROM polla_posiciones_historia WHERE id_usuario='$id_usuario' AND id_evento='$id_evento'
        ORDER BY id_evento ASC";
//print "<br>q=$query2<br>";
/*$result2 = mysql_query($query2) or die(mysql_error());
$row2=mysql_fetch_assoc($result2);
$num_acciones=$row2['cuantas'];*/


print "<center><table style=\"width:50%\">
          <tr><td style=\"text-align:center;vertical-align:middle;\"><img src=\"".$usr->get_imagen($id_usuario)."\" style=\"max-height:40px; max-width:40px\">
          <td style=\"text-align:center;vertical-align:middle;\"><span class=\"titulo_pequeno\">".$usr->get_nombre($id_usuario)."</span>
          </table><br>";

print "<img src=\"evento_pos_hist_graph.php?id_evento=$id_evento&id_usuario=$id_usuario\" id=\"testPicture\" class=\"pChartPicture\">";
print "</center>";
?>
<script>
   addImage("testPicture","pictureMap","draw.php?ImageMap=get");
</script>