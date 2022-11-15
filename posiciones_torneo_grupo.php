<?php
include 'includes/Open-Connection.php';
include 'common.php';

require_once 'includes/class_equipo.php';
$eqobj=new equipo($db);

$id_evento=$_GET['id_evento'];
$num_ronda=$_GET['num_ronda'];
$grupo=$_GET['grupo'];



$query="SELECT nombre,grupos FROM rondasxevento WHERE id_evento='$id_evento' AND num_ronda='$num_ronda'";
$stmt=$db->query($query);
$row=$stmt->fetch(PDO::FETCH_ASSOC);
$nombre_ronda=$row['nombre'];
$grupos=$row['grupos'];
print "<div id=\"ronda$num_ronda\" style=\"display: display\">\n";
print "<table class=\"tabla_con_encabezado\" width=\"600\">\n";
print "<tr> <th colspan=\"11\" style=\"text-align: center; cursor:pointer;\" onclick=\"javascript:mostrarOcultarTablas('ronda$num_ronda');\"\><strong>$nombre_ronda</strong>\n";

//si la ronda tiene grupos colocar el select para los grupos
if ($grupos>1){
	  print "     <tr><th>Pos<th style=\"text-align: center;\" colspan=\"2\"> Grupo:  <SELECT name=\"grupo\" id=\"grupo\" onchange=\"actualizar_g('ronda$num_ronda',$num_ronda)\">";
      $i=0;
      while($i<$grupos){
         $valor=chr($i+65);
         print "<option value=\"$valor\"";
         if ($valor==$grupo) print " SELECTED";
         print ">$valor</option>\n";
         $i++;
      }
      print "  </SELECT>";
}else{
   	   print " <tr><th>Pos<th style=\"text-align: center;\" colspan=\"2\"> &nbsp;";
}

   print "<th style=\"text-align: center;\">PJ
          <th style=\"text-align: center;\">PG
          <th style=\"text-align: center;\">PE
          <th style=\"text-align: center;\">PP
          <th style=\"text-align: center;\">GF
          <th style=\"text-align: center;\">GC
          <th style=\"text-align: center;\">GD
          <th style=\"text-align: center;\">Puntos";

   $query="SELECT * FROM gruposxevento WHERE id_evento='$id_evento' AND num_ronda='$num_ronda' AND grupo='$grupo' ORDER BY ptos DESC,gd DESC, pg DESC";
   $stmt=$db->query($query);
//print "q=$query<br>";


if ($stmt->rowCount()==0){
   print "<tr><td colspan=\"8\"  style=\"text-align: center;\">No hay registros para esta fase\n";
}

$i=1;
while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
   $id_equipo=$row['id_equipo'];
   $pj=$row['pj'];
   $pg=$row['pg'];
   $pp=$row['pp'];
   $pe=$row['pe'];
   $gf=$row['gf'];
   $gc=$row['gc'];
   $gd=$row['gd'];
   $ptos=$row['ptos'];

   //averiguar el nombre del equipo
   $nombre_equipo=$eqobj->get_nombre($id_equipo);

   // detectar la extensión de la banderas
   $extension=extension_imagen($id_equipo);
   $imagen1="imagenes/logos_equipos/".$id_equipo.$extension;

   print "<tr><td>$i<td style=\"width: 120px;\">$nombre_equipo
          <td style=\"width: 50px;\"><div class=\"thumb\"><img src=\"$imagen1\" height=\"55\" width=\"55\" title=\"$nombre_equipo\" id=\"img$id_equipo\"></div>
          <td style=\"text-align: center;\">$pj
          <td style=\"text-align: center;\">$pg
          <td style=\"text-align: center;\">$pe
          <td style=\"text-align: center;\">$pp
          <td style=\"text-align: center;\">$gf
          <td style=\"text-align: center;\">$gc
          <td style=\"text-align: center;\">$gd
          <td style=\"text-align: center;\">$ptos";
   $i++;
}

?>
