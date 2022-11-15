<?

session_start();
include 'includes/_Policy.php';


require_once 'includes/class_evento.php';
$eventoobj=new evento($db);

require_once 'includes/class_equipo.php';
$eqobj=new equipo($db);

$plantilla=$eventoobj->tiene_plantilla($id_evento);



if ($plantilla!=0){
	print "<span class=\"msg_warn\">Este evento usa una plantilla<br><br> No es posible gestionar los marcadores manualmente</span>";

}else{
?>

<script language="JavaScript">
function modificar_select_2(id_select){
   var objselect=document.getElementById(id_select);
   var indice=objselect.selectedIndex;
   valor=objselect.options[indice].value;
//   alert ("valor="+valor);
   if (valor==-1)
      objselect.selectedIndex=1;

}
</script>
<script src="includes/jquery.hoverpulse.js" type="text/javascript"></script>

<?
$msg=$_SESSION['msg'];
        echo "<p><center>" . $msg  . "</p><br>";
$_SESSION['msg']="";

?>

<center>
<form name="marcadores" method="POST" action="marcadores_registrar.php">
<center>
<table class="tabla_con_encabezado">
<tr>
   <th colspan="3" style="text-align: center;">Equipo 1
   <th colspan="3" style="text-align: center;">Equipo 2
   <th style="text-align: center;">Fecha
   <th style="text-align: center;">Hora
<tr>
<?php

//seleccionar hasta 20 partidos para apostar


$grupo_tabla="";

/*$query="SELECT p.id_partido,p.id_equipo1,p.id_equipo2,p.fecha,p.hora,p.goles1,p.editable
        FROM partidos as p, equipos as e
        WHERE p.id_equipo1=e.id_equipo";
//        AND p.fecha>=CURDATE()";      */
$query="SELECT * FROM partidos WHERE id_evento=:id_evento ORDER BY fecha ASC";
$stmt= $db->prepare($query);
$stmt->bindParam(':id_evento',$id_evento);
$stmt->execute();


while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {   $id_partido=$row['id_partido'];
   $id_equipo1=$row['id_equipo1'];
   $id_equipo2=$row['id_equipo2'];
   $fecha=$row['fecha'];
   $hora=$row['hora'];
   $hora=substr($hora,0,5);
   $grupo=$row['grupo'];
   $goles1=$row['goles1'];
   $editable=$row['editable'];


//poner la marca para separación de grupo
   if ($grupo!=$grupo_tabla){
     print "<tr><td colspan=\"8\" value=\"-1\">&nbsp";
     print "<tr> <td colspan=\"8\" align=\"middle\">Grupo $grupo\n";
     print "<tr><th colspan=\"3\">Equipo1<th colspan=\"3\">Equipo2<th width=\"95\">Fecha<th width=\"55\">Hora\n";

     $grupo_tabla=$grupo;
   }
   //averiguar los nombres de los equipos
   $nombre_equipo1=$eqobj->get_nombre($id_equipo1);
   $nombre_equipo2=$eqobj->get_nombre($id_equipo2);

// detectar la extensión de la banderas
$extension=extension_imagen($id_equipo1);
$imagen1="imagenes/logos_equipos/".$id_equipo1.$extension;

$extension=extension_imagen($id_equipo2);
$imagen2="imagenes/logos_equipos/".$id_equipo2.$extension;

   //validar si el usuario ya registro marcador
   $query_marcador="SELECT goles1,goles2 FROM partidos WHERE id_partido=:id_partido";
//print "q=$query_marcador<br>";
   $stmt_marcador= $db->prepare($query_marcador,array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt_marcador->bindParam(':id_partido',$id_partido);
	$stmt_marcador->execute();


   $marcador1="-1";
   $marcador2="-1";
   if ($stmt_marcador->rowCount()>0){   	   $row=$stmt_marcador->fetch(PDO::FETCH_ASSOC);
   	   $marcador1=$row['goles1'];
   	   $marcador2=$row['goles2'];
   }

   print "<tr><td>$nombre_equipo1<td><div class=\"thumb\"><img src=\"$imagen1\" width=\"55\" height=\"55\" id=\"img$id_equipo1\"></div><td style=\"text-align: center;\">";
   print "<SELECT name=\"p$id_partido-eq1\" id=\"p$id_partido-eq1\" onchange=\"modificar_select_2('p$id_partido-eq2')\">";
   print "   <option value=\"-1\">";
   print "   <option value=\"0\"";
   if ($marcador1==0) print " SELECTED";
   print ">0";
   print "   <option value=\"1\"";
   if ($marcador1==1) print " SELECTED";
   print ">1";
   print "   <option value=\"2\"";
   if ($marcador1==2) print " SELECTED";
   print ">2";
   print "   <option value=\"3\"";
   if ($marcador1==3) print " SELECTED";
   print ">3";
   print "   <option value=\"4\"";
   if ($marcador1==4) print " SELECTED";
   print ">4";
   print "   <option value=\"5\"";
   if ($marcador1==5) print " SELECTED";
   print ">5";
   print "   <option value=\"6\"";
   if ($marcador1==6) print " SELECTED";
   print ">6";
   print "   <option value=\"7\"";
   if ($marcador1==7) print " SELECTED";
   print ">7";
   print "   <option value=\"8\"";
   if ($marcador1==8) print " SELECTED";
   print ">8";
   print "       </SELECT>";
   print "<td><div class=\"thumb\"><img src=\"$imagen2\" width=\"55\" height=\"55\" id=\"img$id_equipo2\"></div><td>$nombre_equipo2<td align=\"middle\">";

   print "<SELECT id=\"p$id_partido-eq2\" name=\"p$id_partido-eq2\">";
   print "  <option value=\"-1\">";
   print "   <option value=\"0\"";
   if ($marcador2==0) print " SELECTED";
   print ">0";
   print "   <option value=\"1\"";
   if ($marcador2==1) print " SELECTED";
   print ">1";
   print "   <option value=\"2\"";
   if ($marcador2==2) print " SELECTED";
   print ">2";
   print "   <option value=\"3\"";
   if ($marcador2==3) print " SELECTED";
   print ">3";
   print "   <option value=\"4\"";
   if ($marcador2==4) print " SELECTED";
   print ">4";
   print "   <option value=\"5\"";
   if ($marcador2==5) print " SELECTED";
   print ">5";
   print "   <option value=\"6\"";
   if ($marcador2==6) print " SELECTED";
   print ">6";
   print "   <option value=\"7\"";
   if ($marcador2==7) print " SELECTED";
   print ">7";
   print "   <option value=\"8\"";
   if ($marcador2==8) print " SELECTED";
   print ">8";
   print "          </SELECT>";

   print "<td align=\"middle\">$fecha<td align=\"middle\">$hora\n";

}

?>
</table>
<input type="hidden" name="id_evento" value="<?= $id_evento ?>">
<input type="Submit" value="Registrar">
</form>

</center>
<!--<script>
$(document).ready(function() {
    $('div.thumb img').hoverpulse({
        size: 80,  // number of pixels to pulse element (in each direction)
        speed: 400 // speed of the animation
    });
});
</script>-->

<?
}
?>

