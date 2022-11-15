<br>
<span style="font-size:14px;font-weight:bold;">Resultados:</span>

<center>
<table class="tabla_simple" style="width:90%;table-layout:fixed;">
   <tbody style="display:block;max-height: 300px;width:100%;overflow-y: auto;">
<?
//obtener el total de votos
$query="SELECT count(*) as total_votos from encuesta01";
$result = mysql_query($query) or die(mysql_error());
$row=mysql_fetch_assoc($result);
$total_votos=$row['total_votos'];

$query="SELECT voto,count(*) as votos from encuesta01 group by voto ORDER BY votos DESC";
$result = mysql_query($query) or die(mysql_error());
while($row=mysql_fetch_assoc($result)){
   $id_equipo=$row['voto'];
   $votos=$row['votos'];
?>
<tr>
<td style="width:30%;border-right:0px solid;"><? print "<img src=\"".$eqobj->get_imagen($id_equipo)."\" style=\"width:40px;height:40px;\" title=\"".$eqobj->get_nombre($id_equipo)."\">"; ?>
<td style="border-left:0px solid;border-right:0px;width:10%"><?= $votos ?>
<td style="border-left:0px solid;border-right:solid #ccc 1px;;width:60%"><img src="imagenes/poll.gif" width='<?php echo(100*round($votos/$total_votos,2)); ?>' height='20'>
<?php echo(100*round($votos/$total_votos,2)); ?>%
</td>
</tr>
<?
}
?>
</tbody>
</table>