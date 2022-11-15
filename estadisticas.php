<table class="tabla_simple_pequena">
<tbody>
<tr>
   <th colspan="2">Estadísticas
<?php

//total de usuarios
$query="SELECT count(*) as num_usuarios FROM usuarios";
$stmt = $db->query($query);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$num_usuarios=$row['num_usuarios'];

//total de eventos
$query="SELECT count(*) as num_eventos FROM eventos";
$stmt = $db->query($query);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$num_eventos=$row['num_eventos'];

//total de eventos activos
$query="SELECT count(*) as num_eventos FROM eventos WHERE activo='1'";
$stmt = $db->query($query);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$num_eventos_activos=$row['num_eventos'];

$query="SELECT count(*) as num_bolsas FROM bolsas WHERE activo='1'";
$stmt = $db->query($query);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$num_bolsas_activos=$row['num_bolsas'];

$num_eventos_activos+=$num_bolsas_activos;

//total de partidos
$query="SELECT count(*) as num_partidos FROM partidos";
$stmt = $db->query($query);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$num_partidos=$row['num_partidos'];

//total de ligas
$query="SELECT count(*) as num_partidos FROM grupos_equipos";
$stmt = $db->query($query);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$num_ligas=$row['num_partidos'];


//total de equipos
$query="SELECT count(*) as num_partidos FROM equipos";
$stmt = $db->query($query);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$num_equipos=$row['num_partidos'];


    print "<tr><th>Usuarios<td style=\"text-align:right\">$num_usuarios
           <tr><th>Eventos<td style=\"text-align:right\">$num_eventos
           <tr><th>Eventos Activos<td style=\"text-align:right\">$num_eventos_activos
           <tr><th>Partidos<td style=\"text-align:right\">$num_partidos
           <tr><th>Ligas<td style=\"text-align:right\">$num_ligas
           <tr><th>Equipos<td style=\"text-align:right\">$num_equipos";

?>
</tbody>
</table>