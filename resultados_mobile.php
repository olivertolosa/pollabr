<?
///validar que el usuario si haga parte del evento
include 'seguridad.php';
session_start();
?>
<center>
<script src="includes/jquery.hoverpulse.js" type="text/javascript"></script>
<div class="table-responsive">
<table class="tabla_con_encabezado table table-condensed" style>
<?php
$mostrar_resultados=false;
if (isset($_GET['id_usuario'])){

   $id_usuario=$_GET['id_usuario'];
   //validar que el usuario si est� participando en el evento
   $query="SELECT id_usuario FROM usuariosxevento WHERE id_evento='$id_evento' AND id_usuario='$id_usuario'";
   $stmt=$db->query($query);
   if ($stmt->rowCount()==0){
   	   print "<span class=\"msg_error\">El usuario no est� participando en el evento</span>";
   }else{
      require_once 'includes/class_usuario.php';

      $usuario=new usuario($db);
      $user=$usuario->get_usuario($id_usuario);
      print "<h3>Resultados para el usuario $user<h3>";
      $mostrar_resultados=true;
   }
}else{
$id_usuario=$_SESSION['usuario_polla'];
print "id:usuario=$id_usuario";
   $mostrar_resultados=true;
}

if ($mostrar_resultados){

$grupo_tabla="";


$puntos_totales=0;

//obtener el n�mero de rondas del evento
$query="SELECT num_rondas FROM eventos WHERE id_evento='$id_evento'";
$stmt=$db->query($query);
$row=$stmt->fetch(PDO::FETCH_ASSOC);
$num_rondas=$row['num_rondas'];

$primer_salto=FALSE;  //flag para saber si hay que poner el salto entre las tablas


for ($ronda=1 ; $ronda<=$num_rondas ; $ronda++){

//  poner la marca de la ronda
   $query="SELECT nombre FROM rondasxevento WHERE id_evento='$id_evento' AND num_ronda='$ronda'";
   $stmt=$db->query($query);
   $row=$stmt->fetch(PDO::FETCH_ASSOC);
   $nombre_ronda=$row['nombre'];


$query="SELECT * FROM partidos WHERE id_evento='$id_evento' AND ronda='$ronda' AND goles1>=0 ORDER BY fecha ASC";
   $stmt=$db->query($query);

   $tabla_cabeza=true;
   $primer_partido=true;

   if (mysql_num_rows($result)>0){
	  if (!$primer_salto)
         $primer_salto=TRUE;
      else
      print "<tr><td colspan=\"4\">&nbsp";

      print "<tr> <th colspan=\"4\" style=\"text-align: center;\"><strong>$nombre_ronda</strong>\n";

//     print "<tr><th colspan=\"4\" style=\"text-align: center;\">Equipo1\n";

while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
   $id_partido=$row['id_partido'];
   $id_equipo1=$row['id_equipo1'];
   $id_equipo2=$row['id_equipo2'];
   $goles1=$row['goles1'];
   $goles2=$row['goles2'];
   $fecha=$row['fecha'];
   $hora=$row['hora'];
   $grupo=$row['grupo'];
   $hora=substr($hora,0,strlen($hora)-3);

//print "grupo=$grupo";

      //poner la marca para separaci�n de grupo
         if ($tabla_cabeza){
           if ($grupo)
              print "<tr> <td colspan=\"4\" style=\"text-align: center;\"><strong>Grupo $grupo</strong>\n";

           $tabla_cabeza=false;
           $primer_partido=true;
         }

   //averiguar los nombres de los equipos
   $nombre_equipo1=$eq->get_nombre($id_equipo1);
   $nombre_equipo2=$eq->get_nombre($id_equipo2);

   //validar si el usuario ya registro marcador
   $query_marcador="SELECT equipo1,equipo2,aleatorio FROM apuestas WHERE id_partido='$id_partido' AND id_usuario='$id_usuario'";
   $stmt_marcador = $db->query($query_marcador);
   $marcador1="-1";
   $marcador2="-1";
   if ($stmt->rowCount()>0){
   	   $row=$stmt->fetch(PDO::FETCH_ASSOC);
   	   $marcador1=$row['equipo1'];
   	   $marcador2=$row['equipo2'];
   	   $aleatorio=$row['aleatorio'];
   }

   //setear el fondo de la casilla para indicar si acert� o no
   $bg1="#F78181";
   $bg2="#F78181";
   if ($goles1==$marcador1)
       $bg1="#81F781";
   if ($goles2==$marcador2)
       $bg2="#81F781";

// detectar la extensi�n de la banderas
$extension=extension_imagen($id_equipo1);
$imagen1="imagenes/logos_equipos/".$id_equipo1.$extension;

$extension=extension_imagen($id_equipo2);
$imagen2="imagenes/logos_equipos/".$id_equipo2.$extension;

    if ($primer_partido){
        $primer_partido=false;
    }else{
        print "<tr style=\"line-height:5px;\"><td colspan=\"4\" style=\"line-height:5px;backgournd: #C3C2C4\">&nbsp;";
    }

   print "<tr><th colspan=\"4\">$fecha - $hora\n<tr><td style=\"width: 150px;vertical-align:middle;";
   if ($aleatorio){
         print "background: url(imagenes/random.png);background-repeat:no-repeat;background-position:95% 50%;\" title=\"Marcador Aleatorio";
      }
   print "\";>$nombre_equipo1
              <td><img class=\"img_thumb\" src=\"$imagen1\" title=\"$nombre_equipo1\" height=\"55\" width=\"55\">
              <td title=\"Marcador del partido\" style=\"text-align: center;vertical-align:middle;\">$goles1
              <td bgcolor=\"$bg1\" title=\"Mi Marcador\" style=\"text-align: center;vertical-align:middle;\">$marcador1";
   print "<tr><td colspan=\"4\" style=\"text-align: center;\">Vs";

   print "<tr><td style=\"vertical-align:middle;\">$nombre_equipo2
              <td><img class=\"img_thumb\" src=\"$imagen2\" title=\"$nombre_equipo2\" height=\"55\" width=\"55\">
              <td title=\"Marcador del partido\" style=\"text-align: center;vertical-align:middle;\">$goles2
              <td bgcolor=\"$bg2\" title=\"Mi Marcador\" style=\"text-align: center;vertical-align:middle;\">$marcador2";
   //calcular los puntos obtenidos
   $puntos=0;
   if ($marcador1!=-1){
   	  //validar ganador
   	  if ($goles1>$goles2){
   	  	   if ($marcador1>$marcador2)
   	  	      $puntos+=5;
   	  }else if ($goles1<$goles2){
   	  	   if ($marcador1<$marcador2)
   	  	      $puntos+=5;
   	  }else{
   	  	   if ($marcador1==$marcador2)
   	  	      $puntos+=5;
   	  }

   	  //validar marcadores
   	  if ($goles1==$marcador1)
   	     $puntos+=5;
   	  if ($goles2==$marcador2)
   	     $puntos+=5;

   }
   $puntos_totales+=$puntos;
   print "<tr><td colspan=\"3\" style=\"text-align: left;\">Puntos<td style=\"text-align: right;\">$puntos\n";

   }
}
//print "</table><br><br>";
   print "<tr style=\"line-height:5px;\"><td colspan=\"4\" style=\"line-height:5px;backgournd: #C3C2C4\">&nbsp;";
}

print "<tr><td colspan=\"3\">Puntos totales<td style=\"text-align: center;\">$puntos_totales\n";
}
?>
</table>
</div>

</center>

<script>
$(document).ready(function() {
    $('div.thumb img').hoverpulse({
        size: 80,  // number of pixels to pulse element (in each direction)
        speed: 400 // speed of the animation
    });
});
</script>
