<?php

session_start();
include 'includes/_Policy.php';


require_once 'includes/class_evento.php';
$eventoobj=new evento($db);

require_once 'includes/class_equipo.php';
$eq=new equipo($db);

$plantilla=$eventoobj->tiene_plantilla($id_evento);

if ($plantilla!=0)
   $id_evento=$plantilla;
else
   $id_evento=$_REQUEST['id_evento'];
?>
<script>
function cambiarTablas(id,ronda){

//	alert ("id: "+id+"  ronda:"+ronda);

$('#contenido'+ronda).html($('#'+id).html());
elem=document.getElementById(id);
elem.style.display='none';
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

<script>
function mostrarOcultarTablas(id){

ronda=id.substr(5)

mostrado=0;
elem = document.getElementById(id);
lin=document.getElementById('link_ronda'+ronda);
if(elem.style.display=='block'){
   mostrado=1;
   elem.style.display='none';
//   lin.style.display='block';
}
if(mostrado!=1){
   elem.style.display='block';
//   lin.style.display='none';
}
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

//obtener el numero de rondas y los labels
$query="SELECT num_rondas FROM eventos WHERE id_evento=:id_evento";
$stmt= $db->prepare($query);
$stmt->bindParam(':id_evento',$id_evento);
$stmt->execute();
$row=$stmt->fetch(PDO::FETCH_ASSOC);
$num_rondas=$row['num_rondas'];


include 'function_tablas.php';
$ronda_visible=ronda_a_mostrar($id_evento,$num_rondas);


$i=1;  //contador para los carruseles
$j=1;  //contador para las tablas de fechas
for ($ronda=1 ; $ronda<=$num_rondas; $ronda++){
    $query_ronda="SELECT nombre FROM rondasxevento WHERE id_evento=:id_evento AND num_ronda=:ronda";
    $stmt_ronda= $db->prepare($query_ronda);
	$stmt_ronda->bindParam(':id_evento',$id_evento);
	$stmt_ronda->bindParam(':ronda',$ronda);
	$stmt_ronda->execute();
    $row_ronda=$stmt_ronda->fetch(PDO::FETCH_ASSOC);
    $nombre_ronda=$row_ronda['nombre'];

   $query_fechas="SELECT DISTINCT fecha FROM partidos WHERE id_evento=:id_evento AND ronda=:ronda ORDER BY fecha DESC";
    $stmt_fechas= $db->prepare($query_fechas,array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt_fechas->bindParam(':id_evento',$id_evento);
	$stmt_fechas->bindParam(':ronda',$ronda);
	$stmt_fechas->execute();
   if ($stmt_fechas->rowCount()==0){
   //	print "No hay partidos registrados para esta fase";
   }else{


      print "<br><div id=\"link_ronda$ronda\"><table class=\"tabla_con_encabezado\"><tr><th style=\"text-align: center;cursor:pointer;\" onclick=\"javascript:mostrarOcultarTablas('ronda$ronda');\"><strong>$nombre_ronda</strong></table></div>\n";
      print "<div id=\"ronda$ronda\" style=\"display: none\">\n";



?>


<!-- Carrusel con las fechas -->
<ul id="mycarousel<?= $num_ronda ?>" class="jcarousel-skin-tango">
<?
     $query_fechas="SELECT DISTINCT fecha FROM partidos WHERE id_evento=:id_evento AND ronda=:ronda ORDER BY fecha DESC";
    $stmt_fechas= $db->prepare($query_fechas,array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt_fechas->bindParam(':id_evento',$id_evento);
	$stmt_fechas->bindParam(':ronda',$ronda);
	$stmt_fechas->execute();


    while($row_fechas = $stmt_fechas->fetch(PDO::FETCH_ASSOC)) {
        $fecha=$row_fechas['fecha'];
        $dia=substr($fecha,8);
        $mes=substr($fecha,5,2);
        $anho=substr($fecha,0,4);

        switch ($mes){      	  case 1 : $mes="Enero";
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
     	  case 9 : $mes="Septiembre";
                 break;
   	     case 10 : $mes="Octubre";
                 break;
         case 11 : $mes="Noviembre";
                 break;
   	     case 12 : $mes="Diciembre";
                break;

   }
?>
    <li><div style="border: 1px solid #0D0909;padding:1px;width:115px;height: 60px;cursor:pointer;text-align:middle;color: #000000;padding-top:10px;" onclick="cambiarTablas('fechac<?= $i ?>',<?= $ronda ?>)"><? print "$dia<br>$mes&nbsp;$anho"; ?></div></li>
<?
   $i++;
}
?>
</ul>

<!-- Fin del Carrusel de Fechas -->

<center>
<br><br>
<div id="contenido<?= $ronda ?>">&nbsp;</div>
<?

//obtener las fechas en las que hay partidos
//$query_fechas="SELECT DISTINCT fecha FROM partidos WHERE id_evento='$id_evento' AND ronda='1'";
//$result_fechas = mysql_query($query_fechas) or die(mysql_error());
$stmt_fechas->execute();

$hidden=false;  //valor para mostrar solo 1 tabla.

while ($row_fechas=$stmt_fechas->fetch(PDO::FETCH_ASSOC)){	$fecha_g=$row_fechas['fecha'];

    if ($hidden) {    	$hid="none";
    }else{       $hid="display";
       $hidden="true";
    }
?>
<!--    <div id="link_fecha<?= $i ?>"><table class="tabla_con_encabezado" width="600"><tr><th style="text-align: center;cursor:pointer;" onclick="javascript:mostrarOcultarTablas('fecha<?= $i ?>');"><strong><?= $fecha_g ?></strong></table></div>-->
    <div id="fechac<?= $j ?>" style="display: none;">

<table class="tabla_con_encabezado">
<tr>
   <th colspan="9" style="text-align: center; cursor:pointer;"><strong><?= $fecha_g ?></strong>
<tr>
   <th>Id<th>Equipo1<th>Equipo2<th>Ronda<th>Editable<th>Fecha<th>Hora
<?php
if ($plantilla==0) print "<th>Modificar<th>Eliminar";


$query="SELECT id_partido,id_equipo1,id_equipo2,fecha,hora,editable,ronda
        FROM partidos
        WHERE id_evento=:id_evento and ronda=:ronda and fecha=:fecha_g";
$stmt= $db->prepare($query,array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$stmt->bindParam(':id_evento',$id_evento);
$stmt->bindParam(':ronda',$ronda);
$stmt->bindParam(':fecha_g',$fecha_g);
$stmt->execute();
$num=$stmt->rowCount();
if ($num==0){	print "<tr><td colspan=\"9\"><center>No se encontraron registros</center>\n";

}else{
while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
   $id_partido=$row['id_partido'];
   $id_equipo1=$row['id_equipo1'];
   $id_equipo2=$row['id_equipo2'];
   $fecha=$row['fecha'];
   $hora=$row['hora'];
   $ronda=$row['ronda'];
   $hora=substr($hora,0,5);
   $editable=$row['editable'];

   //averiguar los nombres de los equipos
   $nombre_equipo1=$eq->get_nombre($id_equipo1);
   $nombre_equipo2=$eq->get_nombre($id_equipo2);

// detectar la extensión de la banderas
$extension=extension_imagen($id_equipo1);
$imagen1="imagenes/logos_equipos/".$id_equipo1.$extension;

$extension=extension_imagen($id_equipo2);
$imagen2="imagenes/logos_equipos/".$id_equipo2.$extension;

//imagen editable
if ($editable){	$img_candado="imagenes/unlocked.png";
	$lock=0;
	$lock_text="Bloquear";
}else{	$img_candado="imagenes/locked.png";
	$lock=1;
	$lock_text="Desbloquear";
}


   print "<tr><td>$id_partido
             <td style=\"text-align: center;\"><div class=\"thumb\"><img src=\"$imagen1\" width=\"55\" height=\"45\" title=\"$nombre_equipo1\" id=\"img$id_equipo1\"></div>
             <td style=\"text-align: center;\"><div class=\"thumb\"><img src=\"$imagen2\" width=\"55\" height=\"45\" title=\"$nombre_equipo2\" id=\"img$id_equipo2\"></div>
             <td style=\"text-align: center;\">$ronda
             <td style=\"text-align: center;\"><img src=\"$img_candado\"";
   if ($plantilla==0) print "onclick=\"bloquear_partido($id_partido,$lock)\" style=\"cursor: pointer;\" title=\"$lock_text Partido\"";
   print "   ><td style=\"text-align: center;\">$fecha\n
             <td style=\"text-align: center;\">$hora\n";
   if ($plantilla==0) print "<td><a href=\"index.php?accion=evento_admin&id_evento=$id_evento&accion2=editar_partido&id_partido=$id_partido\" title=\"Ver detalle de partido\">Modificar</a>\n
             <td><a href=\"partido_eliminar.php?id_partido=$id_partido\" title=\"Eliminar Partido\" onclick=\"return confirm('Esta seguro de eliminar este partido?');\">Eliminar</a>\n";
}
}

?>
</table>
</div>
<?
    $j++;
}
}

?>

</div>
<?
   if ($ronda==$ronda_visible){
   	  echo "<script>mostrarOcultarTablas('ronda$ronda');</script>\n";
   }

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

<script>
<?
//para cada ronda mostrar la primera fecha
$query="select DISTINCT ronda FROM partidos WHERE id_evento=:id_evento";
$stmt= $db->prepare($query,array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$stmt->bindParam(':id_evento',$id_evento);
$stmt->execute();
$num_rondas=$stmt->rowCount();


print "window.onload=cambiarTablas('fechac1',1);";
$fechas=1;
for ($i=1 ; $i<$num_rondas ; $i++){
   $query="SELECT DISTINCT fecha FROM partidos WHERE id_evento=_id_evento and ronda=:i ORDER BY fecha ASC";
	$stmt= $db->prepare($query,array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt->bindParam(':id_evento',$id_evento);
	$stmt->bindParam(':i',$i);
	$stmt->execute();

   if ($stmt->rowCount()>0){
      $fechas+=$stmt->rowCount();
      $j=$i+1;
      print "window.onload=cambiarTablas('fechac$fechas',$j);\n";
   }
}
?>
</script>


<script type="text/javascript">

jQuery(document).ready(function() {
    jQuery('.jcarousel-skin-tango').jcarousel();
});

</script>
