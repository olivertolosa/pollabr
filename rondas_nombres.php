<?php
$id_evento=$_GET['id_evento'];
$num_rondas=$_GET['num_rondas'];
include 'includes/Open-Connection.php';

$ronda[]="Primera Fase";
$ronda[]="Segunda Fase";
$ronda[]="Tercera Fase";
$ronda[]="Cuarta Fase";
$ronda[]="Todos contra Todos";
$ronda[]="Fase de Grupos";
$ronda[]="Dieciseisavos de Final";
$ronda[]="Octavos de Final";
$ronda[]="Cuartos de Final";
$ronda[]="Semifinal";
$ronda[]="Tercero y Cuarto";
$ronda[]="Final";

   for ($i=1 ; $i<=$num_rondas ; $i++){//seleccionar el nombre de la ronda existente
   $query_r="SELECT * FROM rondasxevento WHERE id_evento='$id_evento' AND num_ronda='$i'";
   $stmt_r = $db->query($query_r);
   $row_r = $stmt_r->fetch(PDO::FETCH_ASSOC);
   $label_ronda=$row_r['nombre'];
   $grupos=$row_r['grupos'];
   if (!$grupos) $grupos=1;



   	   print "Ronda $i :";
   	   print "<SELECT name=\"ronda$i\">\n";
       foreach ($ronda as $label){
       	   print "<option";
       	   if ($label_ronda==$label) print " SELECTED";
       	   print ">$label</option>\n";
       }

//   	   print "</SELECT>&nbsp;&nbsp;<input type=\"checkbox\" name=\"gruporonda$i\">Grupo<br>\n";
       print "<input type=\"number\" name=\"gruposronda$i\" min=\"1\" max=\"16\" value=\"$grupos\" maxlength=\"2\" step=\"1\">Grupos";
       if ($grupos==1) print "<input type=\"checkbox\" name=\"elimdirecta$i\" id=\"elimdirecta$i\" title=\" Eliminación Directa\">";
       print"<br>\n";
   }

include 'includes/Close-Connection.php';
?>
