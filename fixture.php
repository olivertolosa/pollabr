<?php

session_start();
include 'includes/_Policy.php';

require_once 'includes/class_evento.php';
$eventoobj=new evento($db);

require_once 'includes/class_equipo.php';
$eqobj=new equipo($db);

$id_evento=$_REQUEST['id_evento'];
if (($id_plantilla=$eventoobj->tiene_plantilla($id_evento))!=0)
   $id_evento=$id_plantilla;
?>
<script>
function cambiarTablas(id,num_fechas){

//$('#contenido').html($('#'+id).html());

//ocultar todos los divs

for (i=1 ; i<=num_fechas ; i++){
   elem = document.getElementById('fechac'+i);
   elem.style.display='none';
}
   elem = document.getElementById(id);
elem.style.display='block';

}
</script>

<!-- Carrusel con las fechas -->
<? include 'carrousel.php'; ?>
<!-- Fin del Carrusel de Fechas -->

<center>

<?
//<div id="contenido">&nbsp;</div>
echo $_SESSION['msg'];
print "<br><br>";
unset($_SESSION['msg']);

//obtener las fechas en las que hay partidos
//$query_fechas="SELECT DISTINCT fecha FROM partidos WHERE id_evento='$id_evento' AND ronda='1'";
//$result_fechas = mysql_query($query_fechas) or die(mysql_error());
$i=1;
$hidden=false;  //valor para mostrar solo 1 tabla.
$query_fechas="SELECT DISTINCT fecha FROM partidos WHERE id_evento='$id_evento' AND ronda='1' ORDER BY fecha DESC";

foreach($db->query($query_fechas) as $row_fechas){	$fecha_g=$row_fechas['fecha'];

    if ($hidden) {    	$hid="none";
    }else{       $hid="display";
       $hidden="true";
    }
?>
<div id="container" style="position: relative; ">


<!--    <div id="link_fecha<?= $i ?>"><table class="tabla_con_encabezado" width="600"><tr><th style="text-align: center;cursor:pointer;" onclick="javascript:mostrarOcultarTablas('fecha<?= $i ?>');"><strong><?= $fecha_g ?></strong></table></div>-->

    <div id="fechac<?= $i ?>" style="display: <?= $hid ?>;text-align:center">
<div class="table-responsive">
<table class="tabla_con_encabezado table table-condensed">
<tr>
   <th colspan="7" style="text-align: center;"><strong><?= $fecha_g ?></strong>
<tr>
   <th colspan="2" style="text-align: center;">Equipo1<th colspan="2" style="text-align: center;">Equipo2<th style="text-align: center;">Fecha<th style="text-align: center;">Hora
<?php


$query="SELECT id_partido,id_equipo1,id_equipo2,fecha,hora,goles1,goles2
        FROM partidos
        WHERE id_evento='$id_evento' and fecha='$fecha_g'";
$stmt=$db->query($query);

if ($stmt->rowCount()==0){	print "<tr><td colspan=\"7\"><center>No se encontraron registros</center>\n";

}else{
while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
   $id_partido=$row['id_partido'];
   $id_equipo1=$row['id_equipo1'];
   $id_equipo2=$row['id_equipo2'];
   $fecha=$row['fecha'];
   $hora=$row['hora'];
   $ronda=$row['ronda'];
   $hora=substr($hora,0,5);
   $goles1=$row['goles1'];
   $goles2=$row['goles2'];

   if ($goles1==-1) $goles1="-";
   if ($goles2==-1) $goles2="-";

   //averiguar los nombres de los equipos
   $nombre_equipo1=$eqobj->get_nombre($id_equipo1);

   $nombre_equipo2=$eqobj->get_nombre($id_equipo2);


// detectar la extensión de la banderas
$extension=extension_imagen($id_equipo1);
$imagen1="imagenes/logos_equipos/".$id_equipo1.$extension;

$extension=extension_imagen($id_equipo2);
$imagen2="imagenes/logos_equipos/".$id_equipo2.$extension;

//imagen editable
if ($editable){	$img_candado="imagenes/unlocked.png";
}else{	$img_candado="imagenes/locked.png";
}


   print "<tr><td style=\"vertical-align:middle;text-align: center;width: 80px;\" ><div class=\"thumb\"><img src=\"$imagen1\" width=\"55\" height=\"45\" title=\"$nombre_equipo1\" id=\"img$id_equipo1\"></div>
             <td style=\"vertical-align:middle;text-align: center;width: 80px;\">$goles1
             <td style=\"vertical-align:middle;text-align: center;width: 80px;\">$goles2
             <td style=\"vertical-align:middle;text-align: center;width: 80px;\"><div class=\"thumb\"><img src=\"$imagen2\" width=\"55\" height=\"45\" title=\"$nombre_equipo2\" id=\"img$id_equipo2\"></div>
             <td style=\"vertical-align:middle;text-align: center;\">$fecha\n
              <td style=\"vertical-align:middle;text-align: center;\">$hora\n";
}
}

?>
</table></div>
</div></div>
<?
    $i++;
}
?>
</center>


<script>
window.onload=cambiarTablas('fecha1');

</script>

<script type="text/javascript">

jQuery(document).ready(function() {
    jQuery('#mycarousel').jcarousel();
});

</script>