<?
//session_start();
$fecha=$_POST['fecha'];
require_once 'includes/class_equipo.php';
require_once 'includes/class_partido.php';
require_once 'includes/class_evento.php';
require_once 'includes/class_usuario.php';

//$usuarioobj=new usuario($db);
$equipoobj=new equipo($db);
$partidoobj=new partido($db);
$eventoobj=new evento($db);


$plantilla=$eventoobj->tiene_plantilla($id_evento);
if ($plantilla>0){
	$id_evento_query=$plantilla;
	$plant=1;
}else{
	$id_evento_query=$id_evento;
	$plant=0;
}

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

  $("#tabla_partido").load("usuarios_sin_apuesta_tabla.php?id_partido="+id+"&plantilla="+plantilla+"&id_evento=<?= $id_evento ?>");

}
</script>


<center>
	<h2>Usuarios Sin Apuesta</h2>
<?


$query="SELECT p.id_partido,p.id_equipo1,p.id_equipo2,p.fecha
        FROM partidos as p
        WHERE p.editable=1 AND p.id_evento=:id_evento_query
        order by p.fecha ASC, p.hora ASC";

$stmt= $db->prepare($query);
$stmt->bindParam(':id_evento_query',$id_evento_query);
$stmt->execute();
print "<input type=\"hidden\" name=\"plantilla\" id=\"plantilla\" value=\"$plant\">";

if ($stmt->rowCount()==0){
   print "<center>No hay partidos con apuestas abiertas en este momento</center>";
}else{

//-- Carrusel con los partidos -->
include 'carrousel_partidos.php';
//-- Fin del Carrusel de Fechas -->



print "<br>";
//devolver el recordset para cargar el partido mas reciente
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$id_partido=$row['id_partido'];
//traducir el id del partido si se está usando plantilla
if ($plantilla!=0){
      $id_partido=$partidoobj->get_id_partido_clon_from_original($id_partido,$id_evento);
}

print "<br>";

print "<div id=\"tabla_partido\"></div>\n";

}
//  $lista_correo=substr($lista_correo,0,strlen($lista_correo)-1);
//  print "lista correo=$lista_correo";


print "   <div id=\"loadingdiv\" style=\"position: relative; top: 50px; display: none;\">
             <img src=\"imagenes/loading.gif\" style=\"width:35px;height:35px;\">
          </div>\n";
?>

</center>
<script type="text/javascript">

mostrarOcultarTablas(<?= $id_partido ?>);

jQuery(document).ready(function() {
    jQuery('.jcarousel-skin-tango').jcarousel();
});

</script>