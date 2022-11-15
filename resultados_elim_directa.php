<?php
//include 'includes/Open-Connection.php';

require_once 'includes/class_equipo.php';
$eqobj=new equipo($db);


$query="SELECT * FROM partidos WHERE id_evento='$id_evento' AND ronda='$ronda'";
//print "q=$query";
foreach($db->query('SELECT * FROM table') as $row) {
   $id_partido=$row['id_partido'];
   $id_equipo1=$row['id_equipo1'];
   $id_equipo2=$row['id_equipo2'];
   $fecha=$row['fecha'];
   $hora=$row['hora'];
   $ronda=$row['ronda'];
   $hora=substr($hora,0,5);
   $editable=$row['editable'];
   $goles1=$row['goles1'];
   $goles2=$row['goles2'];

   $penales=false;

   if ($goles1==$goles2){   	   $penales1=$row['penales1'];
   	   $penales2=$row['penales2'];
   	   $penales=true;
   }

   if ($goles1==-1){   	   $goles1="-";
   	   $goles2="-";
   	   $penales=false;
   }

   //averiguar los nombres de los equipos
   $nombre_equipo1=$eqobj->get_nombre($id_equipo1);

   $nombre_equipo2=$eqobj->get_nombre($id_equipo2);

   // detectar la extensión de la banderas
   $extension=extension_imagen($id_equipo1);
   $imagen1="imagenes/logos_equipos/".$id_equipo1.$extension;

   $extension=extension_imagen($id_equipo2);
   $imagen2="imagenes/logos_equipos/".$id_equipo2.$extension;


   print "<tr><td><center><table class=\"tabla_simple\">
          <tr>
          <td style=\"width: 100px;border-left:5px\"><div class=\"thumb\"><img src=\"$imagen1\" width=\"55\" height=\"45\" title=\"$nombre_equipo1\" id=\"img$id_equipo1\"></div>
          <td style=\"width: 100px;border-left:0px\">$nombre_equipo1
          <td style=\"width: 30px;border-left:0px\">$goles1";
          if ($penales) print " ($penales1)";
   //valiadar quien gano
   if ($goles1>$goles2){
        $ganador="<td rowspan=\"2\" style=\"vertical-align:middle\"><div class=\"thumb\"><img src=\"$imagen1\" width=\"55\" height=\"45\" title=\"$nombre_equipo1\" id=\"img$id_equipo1\"></div><td rowspan=\"2\" style=\"width: 100px;border-left:0px\">$nombre_equipo1";
   }else if ($goles2>$goles1){
        $ganador="<td rowspan=\"2\" style=\"vertical-align:middle\"><div class=\"thumb\"><img src=\"$imagen2\" width=\"55\" height=\"45\" title=\"$nombre_equipo2\" id=\"img$id_equipo2\"></div><td rowspan=\"2\" style=\"width: 100px;border-left:0px\">$nombre_equipo2";
   }else if ($goles1!="-"){  //penalties   	   if ($penales1>$penales2){          $ganador="<td rowspan=\"2\" style=\"vertical-align:middle\"><div class=\"thumb\"><img src=\"$imagen1\" width=\"55\" height=\"45\" title=\"$nombre_equipo1\" id=\"img$id_equipo1\"></div><td rowspan=\"2\" style=\"width: 100px;border-left:0px\">$nombre_equipo1";
   	   }else{          $ganador="<td rowspan=\"2\" style=\"vertical-align:middle\"><div class=\"thumb\"><img src=\"$imagen2\" width=\"55\" height=\"45\" title=\"$nombre_equipo2\" id=\"img$id_equipo2\"></div><td rowspan=\"2\" style=\"width: 100px;border-left:0px\">$nombre_equipo2";
   	   }
   }else{   	   $ganador="<td rowspan=\"2\" style=\"width: 165px;\">Por Definir";
   }

   print "$ganador";


   print "       <tr>
          <td><div class=\"thumb\"><img src=\"$imagen2\" width=\"55\" height=\"45\" title=\"$nombre_equipo2\" id=\"img$id_equipo2\"></div>
          <td style=\"width: 100px;border-left:0px\">$nombre_equipo2
          <td style=\"width: 30px;border-left:0px\">$goles2";
          if ($penales) print " ($penales2)";

   print"</table></center>";

}
?>
