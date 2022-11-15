<?

session_start();

?>
<link rel="stylesheet" type="text/css" href="css/polla.css" />

<style type="text/css">
.container_vs {
   position: relative;
}

.background_vs {
   position: absolute;
   top: -5;
   left: 0;
   bottom: 0;
   right: 0;
   z-index: 5;
   font-size: 35px;
   overflow: hidden;
   opacity: 0.1;
   font-weight:bold;
}
</style>

<?php

include 'includes/Open-Connection.php';
include 'includes/class_equipo.php';
include 'includes/class_bolsa.php';

$id_equipo=$_GET['id_equipo'];
$id_bolsa=$_GET['id_bolsa'];


$eq=new equipo($db);
$bolsa=new bolsa($db);

print "<table style=\"width:250;\">
         <tr><td style=\"text-align:center;vertical-align:middle;\"><img src=\"".$eq->get_imagen($id_equipo)."\" style=\"max-height:80px; max-width:80px\">
          <td style=\"text-align:center;vertical-align:middle;\"><span class=\"titulo_medio\">".$eq->get_nombre($id_equipo)."</span></table>
          <br>";

print "<table class=\"tabla_simple_pequena\" style=\"width:100%;\">\n";
print "<tbody>\n";
print "<tr>\n";
print "   <th colspan=\"3\">Próximos Partidos\n";



$query="SELECT * FROM partidos WHERE id_evento='$id_bolsa' AND tipo_e='b' AND (id_equipo1='$id_equipo' OR id_equipo2='$id_equipo')
             AND (fecha >CURDATE() OR (fecha=CURDATE() AND hora>CURTIME())) ORDER BY fecha ASC";

$query.=" LIMIT 0,10";

$stmt = $db->query($query);

if ($stmt->rowCount()==0){
   print "<tr><td style=\"text-align:center\"> No hay partidos";
}


while ($row=$stmt->fetch(PDO::FETCH_ASSOC)){
    $id_partido=$row['id_partido'];
    $id_equipo1=$row['id_equipo1'];
    $id_equipo2=$row['id_equipo2'];
    $fecha=$row['fecha'];
    $hora=$row['hora'];
    $hora=substr($hora,0,5);

   print "<tr>
         <td style=\"text-align:center;border:0px;\"><img class=\"img_thumb\" src=\"".$eq->get_imagen($id_equipo1)."\" width=\"45\" height=\"45\" title=\"".$eq->get_nombre($id_equipo1)."\">
         <td style=\"text-align:center;border:0px;\"><div class=\"container_vs\"><div class=\"background_vs\">Vs</div>$fecha<br>$hora</div>
         <td style=\"text-align:center;border:0px;\">
           <img class=\"img_thumb\" src=\"".$eq->get_imagen($id_equipo2)."\" width=\"45\" height=\"45\" title=\"".$eq->get_nombre($id_equipo2)."\">";
}

?>
</tbody>
</table>


