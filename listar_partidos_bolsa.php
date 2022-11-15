<?php

session_start();
include 'includes/_Policy.php';


require_once 'includes/class_equipo.php';
$eq=new equipo($db);

?>
<script>
function cambiarTablas(id){

//alert ("id: "+id);

$('#contenido').html($('#'+id).html());

}
</script>

<script>
function bloquear_partido(id_partido,lock){

if (lock==0){
   resp=confirm("Está seguro de bloquear este partido?");
}else{   resp=confirm("Está seguro de desbloquear este partido?");
}

if (resp){//   alert ("toca cambiar");
   window.location.replace("partido_lock.php?id_partido="+id_partido+"&lock="+lock);
   return;
}

//alert ("no hacer nada");
}
</script>

<script src="includes/jquery.hoverpulse.js" type="text/javascript"></script>
<script type="text/javascript" src="includes/jquery.jcarousel.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/carrusel.css">
<div id="wrap">
<?

if (isset($_SESSION['msg'])){
   echo $_SESSION['msg'];
   print "<br>";
   unset($_SESSION['msg']);
}


include 'function_tablas.php';


$query_fechas="SELECT DISTINCT fecha FROM partidos WHERE id_evento='$id_evento' AND ronda='$ronda' ORDER BY fecha DESC";
//$result_fechas = mysql_query($query_fechas) or die(mysql_error());
?>
<!-- Carrusel con las fechas -->
<ul id="mycarousel" class="jcarousel-skin-tango">
<?
$query_fechas="SELECT DISTINCT fecha FROM partidos WHERE id_evento='$id_bolsa' and tipo_e='b' ORDER BY fecha DESC";
$stmt_fechas = $db->query($query_fechas);

$i=1;

while ($row_fechas=$stmt_fechas->fetch(PDO::FETCH_ASSOC)){   $fecha=$row_fechas['fecha'];
   $dia=substr($fecha,8);
   $mes=substr($fecha,5,2);
   $anho=substr($fecha,0,4);

   switch ($mes){   	  case 1 : $mes="Enero";
               break;
   	  case 2 : $mes="Febrero";
               break;
   	  case 3 : $mes="Marzo";
               break;
   	  case 4 : $mes="Abril";
               break;
   	  case 5 : $mes="Mayo";
               break;
   	  case 6 : $mes="Junio";
               break;
   	  case 7 : $mes="Julio";
               break;
   	  case 8 : $mes="Agosto";
               break;
   	  case 9 : $mes="Sept";
               break;
   	  case 10 : $mes="Octubre";
               break;
   	  case 11 : $mes="Nov";
               break;
   	  case 12 : $mes="Dic";
               break;

   }
?>
    <li><div style="border: 1px solid #0D0909;padding:1px;width:115px;height: 60px;cursor:pointer;text-align:middle;color: #000000;padding-top:10px;" onclick="cambiarTablas('fechac<?= $i ?>')"><?print "$dia<br>$mes&nbsp;$anho"; ?></div></li>
<?
   $i++;
}
?>
</ul>

<!-- Fin del Carrusel de Fechas -->

<br><br>
<div id="contenido"> </div>
<?
//obtener las fechas en las que hay partidos

$query_fechas="SELECT DISTINCT fecha FROM partidos WHERE id_evento='$id_bolsa' and tipo_e='b' ORDER BY fecha DESC";
$stmt_fechas = $db->query($query_fechas);
$j=1;
$hidden=false;  //valor para mostrar solo 1 tabla.

while ($row_fechas=$stmt_fechas->fetch(PDO::FETCH_ASSOC)){	$fecha_g=$row_fechas['fecha'];

?>
    <div id="fechac<?= $j ?>" style="display: none;">

<table class="tabla_con_encabezado">
<tr>
   <th colspan="9" style="text-align: center;"><strong><?= $fecha_g ?></strong>
<tr>
   <th>Id<th>Equipo1<th>Equipo2<th>Fecha<th>Hora
<?php
if ($plantilla==0) print "<th>Modificar<th>Eliminar";


$query="SELECT id_partido,id_equipo1,id_equipo2,fecha,hora
        FROM partidos
        WHERE id_evento='$id_bolsa' and tipo_e='b' and fecha='$fecha_g'";
$stmt = $db->query($query);
$num=$stmt->rowCount();
if ($num==0){	print "<tr><td colspan=\"9\"><center>No se encontraron registros</center>\n";

}else{
   while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
      $id_partido=$row['id_partido'];
      $id_equipo1=$row['id_equipo1'];
      $id_equipo2=$row['id_equipo2'];
      $fecha=$row['fecha'];
      $hora=$row['hora'];
      $hora=substr($hora,0,5);

      print "<tr><td>$id_partido
             <td style=\"text-align: center;\"><div class=\"thumb\"><img src=\"".$eq->get_imagen($id_equipo1)."\" width=\"55\" height=\"45\" title=\"".$eq->get_nombre($id_equipo1)."\" id=\"img$id_equipo1\"></div>
             <td style=\"text-align: center;\"><div class=\"thumb\"><img src=\"".$eq->get_imagen($id_equipo2)."\" width=\"55\" height=\"45\" title=\"".$eq->get_nombre($id_equipo2)."\" id=\"img$id_equipo2\"></div>
             <td style=\"text-align: center;\">$fecha\n
             <td style=\"text-align: center;\">$hora\n
             <td><a href=\"index.php?accion=bolsa_admin&id_bolsa=$id_bolsa&accion2=editar_partido&id_partido=$id_partido\" title=\"Ver detalle de partido\">Modificar</a>\n
             <td><a href=\"partido_eliminar.php?id_partido=$id_partido\" title=\"Eliminar Partido\" onclick=\"return confirm('Esta seguro de eliminar este partido?');\">Eliminar</a>\n";
}
}

?>
</table>
</div>

<?
    $j++;
}

?>


<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery('div.thumb img').hoverpulse({
        size: 80,  // number of pixels to pulse element (in each direction)
        speed: 400 // speed of the animation
    });

});
</script>

<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery('.jcarousel-skin-tango').jcarousel();

    $('#contenido').html($('#fechac<?= $j ?>').html());

});

</script>

<script>
$('#contenido').html($('#fechac1').html());
</script>
