<?
include 'seguridad.php';

?>

<script>
function mostrarOcultarTablas(id){

ronda=id.substr(5)

mostrado=0;
elem = document.getElementById(id);
lin=document.getElementById('link_ronda'+ronda);
if(elem.style.display=='block'){
   mostrado=1;
   elem.style.display='none';
   lin.style.display='block';
}
if(mostrado!=1){
   elem.style.display='block';
   lin.style.display='none';
}
}
</script>

<script>
function actualizar_g(div_a_cambiar,ronda){
var xmlhttp;

var sel = document.getElementById("grupo");
var grupo = sel.options[sel.selectedIndex].value;

//alert ("grupo:"+grupo+"  div="+div_a_cambiar);

if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
// alert (xmlhttp.responseText);
    document.getElementById(div_a_cambiar).innerHTML=xmlhttp.responseText;
    }
  }
//alert ("tabla_equiposxgrupo.php?id_evento=<?php echo $id_evento; ?>&grupo="+grupo);

xmlhttp.open("GET","posiciones_torneo_grupo.php?id_evento=<?php echo $id_evento; ?>&grupo="+grupo+"&num_ronda="+ronda,true);
xmlhttp.send();
}
</script>

<center>
<?php


//obtener el número de rondas del evento
require_once 'includes/class_evento.php';
$eventobj=new evento($db);
$num_rondas=$eventobj->get_numrondas($id_evento);

include 'function_tablas.php';
$ronda_visible=ronda_a_mostrar($id_evento,$num_rondas);


if (($id_plantilla=$eventobj->tiene_plantilla($id_evento))!=0){
	$id_event=$id_plantilla;
}else{
   $id_event=$id_evento;
}


for ($ronda=1 ; $ronda<=$num_rondas; $ronda++){
   //averiguar la cantidad de partidos x ronda
   $query_partidos="SELECT COUNT(id_partido) as num_partidos FROM partidos WHERE id_evento=:id_event AND ronda=:ronda";
   $stmt_partidos= $db->prepare($query_partidos);
	$stmt_partidos->bindParam(':id_event',$id_event);
	$stmt_partidos->bindParam(':ronda',$ronda);
	$stmt_partidos->execute();
   $row_partidos=$stmt_partidos->fetch(PDO::FETCH_ASSOC);

   $num_partidos=$row_partidos['num_partidos'];

   print "<!-- ************************** Ronda $ronda ********************* -->\n";

//  poner la marca de la ronda
   $query="SELECT nombre,grupos,elim_directa FROM rondasxevento WHERE id_evento='$id_event' AND num_ronda='$ronda'";
   $stmt=$db->query($query);
   $row=$stmt->fetch(PDO::FETCH_ASSOC);
   $nombre_ronda=$row['nombre'];
   $grupos=$row['grupos'];
   $elim_directa=$row['elim_directa'];
   print "<div id=\"link_ronda$ronda\"><table class=\"tabla_con_encabezado\"><tr><th style=\"text-align: center;cursor:pointer;\" onclick=\"javascript:mostrarOcultarTablas('ronda$ronda');\"><strong>$nombre_ronda</strong></table></div>\n";
   print "<div id=\"ronda$ronda\" class=\"table-responsive\" style=\"display: none\">\n";
   print "<table class=\"tabla_con_encabezado table\">\n";
   print "<tr> <th colspan=\"11\" style=\"text-align: center; cursor:pointer;\" onclick=\"javascript:mostrarOcultarTablas('ronda$ronda');\"><strong>$nombre_ronda</strong>\n";

   if ($num_partidos>0){
      if ($grupos>1){      	  include 'resultados_grupos.php';
      }else if ($elim_directa){      	  include 'resultados_elim_directa.php';
      }else if ($grupos==1){         include 'resultados_grupos.php';
      }
   }else{      print "<tr><td colspan=\"11\"  style=\"text-align: center;\">No hay registros para esta fase\n";
   }

?>
</table>
</div>
<br><br>

<?
    if ($ronda==$ronda_visible){
   	  echo "<script>mostrarOcultarTablas('ronda$ronda');</script>\n";
   }
}
?>
</center>



