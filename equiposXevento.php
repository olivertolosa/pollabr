<?
require_once 'includes/class_equipo.php';
require_once 'includes/class_evento.php';
$eventoobj=new evento($db);
$eq=new equipo($db);

$plantilla=$eventoobj->tiene_plantilla($id_evento);

if ($plantilla==0){

?>
<script>
function actualizar_g1(){var xmlhttp;

var sel = document.getElementById("grupos_equipos");
var grupo_equipos = sel.options[sel.selectedIndex].value;

//alert ("grupo:"+grupo_equipos);

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
    document.getElementById("eq_disponibles").innerHTML=xmlhttp.responseText;
    }
  }

xmlhttp.open("GET","tabla_equipos.php?id_grupo="+grupo_equipos+"&id_evento=<?php echo $id_evento; ?>",true);
xmlhttp.send();
}
</script>

<script>
function inc_equipo(id_equipo,inc){
document.getElementById("loadingdiv").style.display="inline";

//inc: 1 incluir,2 excluir
var xmlhttp;

//alert ("id_equipo:"+id_equipo+"  inc:"+inc);


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
    document.getElementById("eq_participantes").innerHTML=xmlhttp.responseText;
    }
  }



xmlhttp.open("GET","inc_equipo_evento.php?id_evento=<?php echo $id_evento; ?>&id_equipo="+id_equipo+"&inc="+inc,false);
xmlhttp.send();

// alert("voy a actalizarg1");
actualizar_g1();

document.getElementById("loadingdiv").style.display="none";
}
</script>


<div id="loadingdiv" style="position: relative; top: 50px; display: none;">
             <img src="imagenes/loading.gif" style="width:35px;height:35px;">
</div>
<center>
De click sobre el equipo que desea adicionar/remover
<table class="tabla_simple" border="2" width="600">
<form name="mod_equipos" action="" method="POST">
<tr>
   <th width="50%">Equipos disponibles
   <th>Equipos participantes
<tr>
  <td><SELECT name="grupos_equipos" id="grupos_equipos" onchange="actualizar_g1()">
          <option value="-1">Seleccione una opción</option>
<?
    $query="SELECT * from grupos_equipos ORDER BY grupo_equipos ASC";
	$stmt= $db->prepare($query);
	$stmt->execute();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
       $id_grupo=$row['id_grupo_equipos'];
       $grupo=$row['grupo_equipos'];
       print "<option value=\"$id_grupo\">$grupo</option>\n";
    }
?>
  </SELECT>
</form>
<tr>
    <td><div class="scrollableContainer">
    <div id="eq_disponibles">
       <table class="tabla_simple" style="display: block; max-height: 500px; overflow-y: scroll;width:280;">
   </table>
     </div>
   </div>

   <td><div class="scrollableContainer">
          <div id="eq_participantes">
       <table class="tabla_simple" style="display: block; max-height: 500px; overflow-y: scroll;width:280;">
<?
   $query="SELECT id_equipo,equipo FROM equipos WHERE id_equipo IN (SELECT id_equipo FROM equiposxevento WHERE id_evento=:id_evento) ORDER BY equipo ASC";
	$stmt= $db->prepare($query);
	$stmt->bindParam(':id_evento',$id_evento);
	$stmt->execute();


	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
       $id_equipo=$row['id_equipo'];
       $equipo=$row['equipo'];

       print "<tr ><td style=\"vertical-align:middle;\"><span class=\"lista_equipos\">
           <a href=\"javascript:inc_equipo($id_equipo,2)\"><img style=\"vertical-align:middle\" src=\"".$eq->get_imagen($id_equipo)."\" width=\"40\" height=\"40\">&nbsp;&nbsp;$equipo</a></span></td>\n";
    }
?>   </table>
          </div>
       </div>

</table>
</center>

<?
}else{  //si usa plantilla....solo mostrar la lista
   print "<table  class=\"tabla_simple\" border=\"2\">";
   $query="SELECT id_equipo,equipo FROM equipos WHERE id_equipo IN (SELECT id_equipo FROM equiposxevento WHERE id_evento=:plantilla)";
	$stmt= $db->prepare($query);
	$stmt->bindParam(':plantilla',$plantilla);
	$stmt->execute();


	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	  $id_equipo=$row['id_equipo'];
      $nombre_equipo=$row['equipo'];
      print "<tr><td><img class=\"img_thumb\" src=\"".$eq->get_imagen($id_equipo)."\" height=\"55\" width=\"55\" title=\"$nombre_equipo\">
                 <td>$nombre_equipo\n";
   }
   print "</table>";

}