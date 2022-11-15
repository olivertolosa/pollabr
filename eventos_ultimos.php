<table class="tabla_simple_pequena">
<tr>
   <th>Últimos eventos
<?php
//tabla con últimos eventos creados
$query="SELECT id_evento,evento,publica FROM eventos WHERE activo='1' ORDER BY id_evento DESC offset 0 rows fetch next 5 rows only";
foreach($db->query($query) as $row){
    $id_evento_ultimos=$row['id_evento'];
    $evento=$row['evento'];
    $publico=$row['publica'];
    print "<tr><td>";
    if ($publico)
       print "<a href=\"index.php?accion=ingreso_evento&id_evento=$id_evento_ultimos\" alt=\"click para ingresar\">$evento</a>";
    else
       print "$evento";
}

//tabla con bolsas
$query="SELECT id_bolsa,nombre_bolsa FROM bolsas WHERE activo='1'";
foreach($db->query($query) as $row){   $id_bolsa=$row['id_bolsa'];
   $nombre_bolsa=$row['nombre_bolsa'];
   print "<tr><td><a href=\"index.php?accion=bolsa_reporte_acciones&id_bolsa=$id_bolsa\">Bolsa $nombre_bolsa	</a>";
}

?>
</table>