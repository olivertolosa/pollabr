
<script>
function getVote() {

   var sel=document.getElementById('equipo');
   var int=sel.options[sel.selectedIndex].value;
   var ya_voto=document.getElementById('ya_voto').value;
   document.getElementById('boton_enviar').value="Cambiar mi voto";
   document.getElementById('ya_voto').value=1;

  if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  } else {  // code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function() {
    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
      document.getElementById("poll_resultados").innerHTML=xmlhttp.responseText;
    }
  }
  xmlhttp.open("GET","encuesta_campeon_evento_procesar.php?voto="+int+"&ya_voto="+ya_voto,true);
  xmlhttp.send();
}
</script>

<div id="poll" class="tabla_simple_pequena" style="background-color: #EDE8E8;">
<span style="font-size:16px;font-weight:bold;">Quién será el campeón en Brasil 2014?</span>

<?
//validar si el usaurio ya votó
$ya_voto=0;
$texto_boton="Votar";
?>
<div id="poll_resultados">

<?

   require_once 'includes/class_equipo.php';
   $eqobj=new equipo();

   include 'tabla_campeon_brasil.php';

$query="SELECT voto FROM encuesta01 WHERE id_usuario='$id_usuario'";
$result = mysql_query($query) or die(mysql_error());
if (mysql_num_rows($result)>0){
   $row=mysql_fetch_assoc($result);
   $voto=$row['voto'];
   $ya_voto=1;
   $texto_boton="Cambiar mi voto";


   ?>


</center>
<br>
<?




   print "Mi voto:<br><img src=\"".$eqobj->get_imagen($voto)."\" style=\"width:60px;height:60px;\" class=\"img_thumb\" title=\"".$eqobj->get_nombre($voto)."\"><br><br>";

}
?>
</div>
<?
if (isset($_SESSION['usuario_polla'])){
?>
<form>
<select name="equipo" id="equipo">";
<?
   $queryeq="SELECT id_equipo,equipo FROM equipos WHERE id_equipo IN(SELECT id_equipo FROM equiposxevento WHERE id_evento='23')ORDER BY equipo ASC";
   $resulteq = mysql_query($queryeq) or die(mysql_error());
   while($roweq=mysql_fetch_assoc($resulteq)){
   	   $id_equipo=$roweq['id_equipo'];
   	   $nombre_eq=$roweq['equipo'];
       print "<option class=\"equipo_$id_equipo\" value=\"$id_equipo\" class=\"usa\"";
       if ($id_equipo==$id_equipo1) print " SELECTED";
       print ">$nombre_eq\n";
   }
?>
</SELECT>
<input type="button" name="enviar" id="boton_enviar" value="<?= $texto_boton ?>" onclick="getVote()">
<?
   print "<input type=\"hidden\" name=\"ya_voto\" id=\"ya_voto\" value=\"$ya_voto\">";
?>
</form>
<?
}else{
   print "<br><small>Debes estar autenticado para participar en la encuesta</small>";
}
?>
</div>

