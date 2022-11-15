<?
include 'seguridad.php';
require_once 'includes/class_evento.php';
require_once 'includes/class_partido.php';
$eventobj=new evento($db);
$partidoobj=new partido($db);

$id_evento=$_REQUEST['id_evento'];

?>
<script>
function mostrarOcultarTablas(id){

	document.getElementById("tabla_partido").innerHTML="";
   plantilla=document.getElementById("plantilla").value;

  //document.getElementById("loadingdiv").style.display="block";

  $(document).ajaxStart(function(){
  $("#loadingdiv").css("display","block");
});
$(document).ajaxComplete(function(){
  $("#loadingdiv").css("display","none");
});
  $("#tabla_partido").load("apuestas_todos_tabla.php?id_partido="+id+"&plantilla="+plantilla+"&id_evento=<?php echo $id_evento; ?>");

}
</script>


<center>
<?php

$plantilla=$eventobj->tiene_plantilla($id_evento);
if ($plantilla>0){
	$id_evento_query=$plantilla;
	$plant=1;
}else{
	$id_evento_query=$id_evento;
	$plant=0;
}
print "<input type=\"hidden\" name=\"plantilla\" id=\"plantilla\" value=\"$plant\">";

$query="SELECT p.id_partido,p.id_equipo1,p.id_equipo2,p.fecha,p.hora,p.goles1,p.goles2
        FROM partidos as p, equipos as e
        WHERE p.id_equipo1=e.id_equipo
        AND p.editable=0 AND p.id_evento=:id_evento_query
        order by p.fecha DESC, p.hora DESC";
//        AND p.fecha>=CURDATE()";

//print "q=$query<br>";
$stmt= $db->prepare($query,array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$stmt->bindParam(':id_evento_query',$id_evento_query);
$stmt->execute();

if ($stmt->rowCount()==0){   print "<center>No hay partidos con apuestas cerradas en este momento</center>";
}else{

//-- Carrusel con los partidos -->
include 'carrousel_partidos.php';
//-- Fin del Carrusel de Fechas -->

//devolver el recordset para cargar el partido mas reciente
$stmt->execute();
$row=$stmt->fetch(PDO::FETCH_ASSOC);
$id_partido=$row['id_partido'];
//traducir el id del partido si se está usando plantilla
if ($plantilla!=0){
      $id_partido=$partidoobj->get_id_partido_clon_from_original($id_partido,$id_evento);
}

print "<br>";

require_once 'includes/class_equipo.php';
$eq=new equipo($db);

print "   <div id=\"loadingdiv\" style=\"position: relative; top: 50px; display: none;\">
             <img src=\"imagenes/loading.gif\" style=\"width:35px;height:35px;\">
          </div>\n";


print "<div id=\"tabla_partido\"></div>\n";

?>


</center>


<script type="text/javascript">

mostrarOcultarTablas(<?= $id_partido ?>);

jQuery(document).ready(function() {
    jQuery('.jcarousel-skin-tango').jcarousel();
});

</script>
<?
}
?>