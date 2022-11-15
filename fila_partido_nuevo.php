<br><br>
<table border="1">
<tr>
   <th>Equipo1<th>Equipo2<th>Ronda<th>Fecha<th>Hora
<?php

$id_evento=$_GET['id_evento'];

require_once 'includes/Open-Connection.php';

   print "<tr><td><select name=\"eq1\">";
   $queryeq="SELECT id_equipo,equipo FROM equipos WHERE id_equipo IN(SELECT id_equipo FROM equiposxevento WHERE id_evento='$id_evento')ORDER BY equipo ASC";
   $resulteq = mysql_query($queryeq) or die(mysql_error());
   while($roweq=mysql_fetch_assoc($resulteq)){
   	   $id_equipo=$roweq['id_equipo'];
   	   $nombre_eq=$roweq['equipo'];
       print "<option class=\"equipo_$id_equipo\" value=\"$id_equipo\" class=\"usa\"";
       if ($id_equipo==$id_equipo1) print " SELECTED";
       print ">$nombre_eq\n";
   }
   print "<td><SELECT name=\"eq2\">";
   $queryeq="SELECT id_equipo,equipo FROM equipos WHERE id_equipo IN(SELECT id_equipo FROM equiposxevento WHERE id_evento='$id_evento') ORDER BY equipo ASC";
   $resulteq = mysql_query($queryeq) or die(mysql_error());
   while($roweq=mysql_fetch_assoc($resulteq)){
   	   $id_equipo=$roweq['id_equipo'];
   	   $nombre_eq=utf8_decode($roweq['equipo']);


       print "<option class=\"equipo_$id_equipo\" value=\"$id_equipo\"";
       if ($id_equipo==$id_equipo2) print " SELECTED";
       print ">$nombre_eq\n";
   }

   print "<td><SELECT name=\"ronda\">";
   //seleccionar los nombres de las rondas disponibles
   $query="SELECT nombre,num_ronda FROM rondasxevento WHERE id_evento='$id_evento'";
   $result=mysql_query($query) or die(mysql_error());

   while ($row=mysql_fetch_assoc($result)){
       $label=$row['nombre'];
       $num_ronda=$row['num_ronda'];
       print "<option value=\"$num_ronda\">$label</option>";
   }
   print "</SELECT>";

    print"<td><input type=\"date\" name=\"fecha\" required>\n
              <td align=\"center\"><input type=\"time\" name=\"hora\" value=\"$hora\" required>\n";



?>


</table>

