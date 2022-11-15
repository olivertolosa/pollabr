<?
session_start();
include 'includes/_Policy.php';
?>

<script language="JavaScript">
function modificar_select_2(id_select){   var objselect=document.getElementById(id_select);
   var indice=objselect.selectedIndex;
   valor=objselect.options[indice].value;
//   alert ("valor="+valor);
   if (valor==-1)
      objselect.selectedIndex=1;

}
</script>

<br><br>
<?
$msg=$_SESSION['msg'];
        echo "<p><center>" . $msg  . "</p>";
$_SESSION['msg']="";
$id_usuario_suplantado=$_REQUEST['id_usuario_suplantado'];

?>
<form name="selectusuario" method="POST" action="index.php?accion=apostarxusuario">
Seleccionar usuario:
<SELECT name="id_usuario_suplantado" onchange="document.forms.selectusuario.submit();";>
<?
    $query="SELECT id_usuario, nombre FROM usuarios ORDER BY nombre ASC";
    $result = mysql_query($query) or die(mysql_error());
    while($row=mysql_fetch_assoc($result)){       $id_usr=$row['id_usuario'];
       $nombre=$row['nombre'];
       print "<option value=\"$id_usr\"";
       if ($id_usuario_suplantado==$id_usr)
          print " SELECTED";
       print ">$nombre\n";
    }
?>
</SELECT>
</form>
<?
if (isset($_REQUEST['id_usuario_suplantado'])){

?>

<center>
<form name="apostar" method="POST" action="apostar_registrar.php">
<center>
<table class="tabla_con_encabezado">
<tr>
<?php

//seleccionar hasta 20 partidos para apostar

require_once 'includes/Open-Connection.php';

$grupo_tabla="";

$query="SELECT p.id_partido,p.id_equipo1,p.id_equipo2,p.fecha,p.hora,e.grupo,p.goles1,p.editable
        FROM partidos as p, equipos as e
        WHERE p.id_equipo1=e.id_equipo";
//        AND p.fecha>=CURDATE()";
$result = mysql_query($query) or die(mysql_error());
//print "q=$query<br>";

while($row=mysql_fetch_assoc($result)){   $id_partido=$row['id_partido'];
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
     print "<tr><td colspan=\"8\">&nbsp";
     print "<tr> <td colspan=\"8\" style=\"text-align: center;\"><strong>Grupo $grupo</strong>\n";
     print "<tr><th colspan=\"3\" style=\"text-align: center;\">Equipo1<th colspan=\"3\" style=\"text-align: center;\">Equipo2<th width=\"95\">Fecha<th width=\"55\">Hora\n";

     $grupo_tabla=$grupo;
   }
   //averiguar los nombres de los equipos
   $querye1="SELECT equipo FROM equipos WHERE id_equipo='$id_equipo1'";
//print "q=$querye1";
   $resulte1 = mysql_query($querye1) or die(mysql_error());
   $rowe1=mysql_fetch_assoc($resulte1);
   $nombre_equipo1=$rowe1['equipo'];

   $querye2="SELECT equipo FROM equipos WHERE id_equipo='$id_equipo2'";
   $resulte2 = mysql_query($querye2) or die(mysql_error());
   $rowe2=mysql_fetch_assoc($resulte2);
   $nombre_equipo2=$rowe2['equipo'];

   //validar si el usuario ya registro marcador
   $query_marcador="SELECT equipo1,equipo2 FROM apuestas WHERE id_partido='$id_partido' AND id_usuario='$id_usuario_suplantado'";
   $result_marcador = mysql_query($query_marcador) or die(mysql_error());
   $marcador1="-1";
   $marcador2="-1";
   if (mysql_num_rows($result_marcador)>0){   	   $row=mysql_fetch_assoc($result_marcador);
   	   $marcador1=$row['equipo1'];
   	   $marcador2=$row['equipo2'];
   }

// detectar la extensión de la banderas
if (file_exists("imagenes/logos_equipos/".$id_equipo1.".png"))
   $extension=".png";
else if (file_exists("imagenes/logos_equipos/".$id_equipo1.".PNG"))
   $extension=".PNG";
else if (file_exists("imagenes/logos_equipos/".$id_equipo1.".jpg"))
    $extension=".jpg";
else if (file_exists("imagenes/logos_equipos/".$id_equipo1.".JPG"))
    $extension=".JPG";
else if (file_exists("imagenes/logos_equipos/".$id_equipo1.".bmp"))
    $extension=".bmp";
else if (file_exists("imagenes/logos_equipos/".$id_equipo1.".BMP"))
    $extension=".BMP";

$imagen1="imagenes/logos_equipos/".$id_equipo1.$extension;
// detectar la extensión de la banderas
if (file_exists("imagenes/logos_equipos/".$id_equipo2.".png"))
   $extension=".png";
else if (file_exists("imagenes/logos_equipos/".$id_equipo2.".PNG"))
   $extension=".PNG";
else if (file_exists("imagenes/logos_equipos/".$id_equipo2.".jpg"))
    $extension=".jpg";
else if (file_exists("imagenes/logos_equipos/".$id_equipo2.".JPG"))
    $extension=".JPG";
else if (file_exists("imagenes/logos_equipos/".$id_equipo2.".bmp"))
    $extension=".bmp";
else if (file_exists("imagenes/logos_equipos/".$id_equipo2.".BMP"))
    $extension=".BMP";

$imagen2="imagenes/logos_equipos/".$id_equipo2.$extension;

   print "<tr><td>$nombre_equipo1<td><img src=\"$imagen1\" height=\"85\" width=\"85\" title=\"$nombre_equipo1\"><td style=\"text-align: center;\">";
   if (!$editable){
      print "$marcador1";
   }else{
      print "<SELECT name=\"p$id_partido-eq1\" id=\"p$id_partido-eq1\" onchange=\"modificar_select_2('p$id_partido-eq2')\">";
      print "   <option value=\"-1\">";
      for ($m=0 ; $m<=20 ; $m++){
         print "   <option value=\"$m\"";
         if ($marcador1==$m) print " SELECTED";
         print ">$m \n";
      }

      print "       </SELECT>";
   }
   print "<td><img src=\"$imagen2\" height=\"85\" width=\"85\" title=\"$nombre_equipo2\"><td>$nombre_equipo2<td style=\"text-align: center;\">";
   if (!$editable){
      print "$marcador2";
   }else{
      print "<SELECT id=\"p$id_partido-eq2\" name=\"p$id_partido-eq2\">";
	  print "  <option value=\"-1\">";
      for ($m=0 ; $m<=20 ; $m++){
         print "   <option value=\"$m\"";
         if ($marcador1==$m) print " SELECTED";
         print ">$m \n";
      }

      print "          </SELECT>";
   }
   print "<td align=\"middle\">$fecha<td align=\"middle\">$hora\n";
   print "<input type=\"hidden\" name=\"id_usuario_suplantado\" value=\"$id_usuario_suplantado\">\n";

}

?>
</table>

<input type="Submit" value="Registrar">
</form>
<?
}
?>
</center>


