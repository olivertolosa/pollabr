<?
session_start();
$hoy=date("Y-m-d");
?>
<center>

<table class="tabla_simple">
   <tr>
      <th colspan="2" style="text-align:center"><span class="titulo_medio"> Filtro</span>
   <tr>
      <td>Fecha<td><input type="date" name="fecha" id="fecha" value="<? echo $hoy ?>">
   <tr><td>Liga<td><SELECT id="id_liga" name="id_liga" title="Selecciona una liga">
   <option value="-1">Todas</option>
   <option value="0">Sin definir</option>
<?
   $query="SELECT * FROM grupos_equipos ORDER BY grupo_equipos ASC";
   foreach($db->query($query) as $row) {
   	   $id_liga2=$row['id_grupo_equipos'];
   	   $liga=$row['grupo_equipos'];
   	   print "<option value=\"$id_liga2\">$liga</option>\n";
   }

?>
</SELECT>

   <tr>
      <th colspan="2" style="text-align:center"> <input type="button" value="Buscar" id="buscar">
</table>

<script>
$(document).ready(function() {
$('#buscar').on('click', function() {
		     var fecha = $('#fecha').val();
		     var id_liga= $( "#id_liga" ).val();
             $(document).ajaxStart(function(){
                     //$("#loadingdiv").css("display","block");
                  });
             $(document).ajaxComplete(function(){
                      //$("#loadingdiv").css("display","none");
                  });
             $("#resultados_partidos").load("carga_partidos_historicos.php?fecha="+fecha+"&id_liga="+id_liga);
        });

});

</script>


<br>

<div id="resultados_partidos">&nbsp;</div>


</center>
