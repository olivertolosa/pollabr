<table border="1">
<?php
include 'includes/Open-Connection.php';

$query="SELECT * FROM maquinas WHERE id_maquina IN (SELECT id_maquina FROM componentes_servicios WHERE id_tipo_componente='2' or id_tipo_componente='3' and rol='1' and activo='1')";
$result=$database->db_query($query);
 while ($row=$database->db_fetch_assoc($result)){ 	 $id_maquina=$row['id_maquina'];
 	 $nom_maquina=$row['nombre_maquina'];

 	 $q2="SELECT s.nombre FROM servicios as s, componentes_servicios as cs
 	      WHERE s.id_servicio=cs.id_servicio AND (cs.id_tipo_componente='2' or id_tipo_componente='3' and rol='1')
 	      AND cs.id_maquina='$id_maquina'";
 	   $result2=$database->db_query($q2);
 	   while ($row2=$database->db_fetch_assoc($result2)){
 	       $servicio=$row['nombre'];
 	       print "<tr><td>$nom_maquina<td>$servicio\n";
       }


 }


include 'includes/Close-Connection.php';
?>

</table>