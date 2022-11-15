<?
require_once 'includes/class_equipo.php';
$equipoobj= new equipo($db);

//si la ronda tiene grupos colocar el select para los grupos
   if ($grupos>1){
   	  print "     <tr><th>Pos<th style=\"text-align: center;\" colspan=\"2\"> Grupo:  <SELECT name=\"grupo\" id=\"grupo\" onchange=\"actualizar_g('ronda$ronda',$ronda)\">";
      $i=0;
      while($i<$grupos){
         $valor=chr($i+65);
         print "<option value=\"$valor\">$valor</option>\n";
         $i++;
      }
      print "  </SELECT>";
   }
   else{
   	   print " <tr><th>Pos<th style=\"text-align: center;\" colspan=\"2\"> &nbsp;";
   }

   print "<th style=\"text-align: center; width: 35px;\">PJ
          <th style=\"text-align: center; width: 35px;\">PG
          <th style=\"text-align: center; width: 35px;\">PE
          <th style=\"text-align: center; width: 35px;\">PP
          <th style=\"text-align: center; width: 35px;\">GF
          <th style=\"text-align: center; width: 35px;\">GC
          <th style=\"text-align: center; width: 35px;\">GD
          <th style=\"text-align: center; width: 35px;\">Puntos";

   $query="SELECT * FROM gruposxevento WHERE id_evento=:id_evento AND num_ronda=:ronda AND grupo='A' ORDER BY ptos DESC,gd DESC,pg DESC";
   $stmt= $db->prepare($query,array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt->bindParam(':id_evento',$id_evento);
	$stmt->bindParam(':ronda',$ronda);
	$stmt->execute();
//print "q=$query<br>";


if ($stmt->rowCount()==0){
   print "<tr><td colspan=\"11\"  style=\"text-align: center;\">No hay registros para esta fase\n";
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
   $nombre_equipo=$equipoobj->get_nombre($id_equipo);

   print "<tr><td>$i<td style=\"width: 120px;\">$nombre_equipo
          <td style=\"width: 50px;\"><div class=\"img_thumb\"><img src=\"".$equipoobj->get_imagen($id_equipo)."\" height=\"55\" width=\"55\" title=\"$nombre_equipo\" id=\"img$id_equipo\"></div>
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
