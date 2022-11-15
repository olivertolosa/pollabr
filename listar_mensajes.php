<?
session_start();
include 'includes/_Policy.php';

?>
<center>
<?
if (isset($_SESSION['msg'])){   echo $_SESSION['msg']."<br><br>";
   unset($_SESSION['msg']);
}
?>
<table class="tabla_con_encabezado">
<thead>
<tr>
   <th>#<th>Título<th>Fecha
</thead>
<tbody>
<?php

$i=1;
//armar la lista de mensajes
$query="SELECT id_mensaje,fecha,titulo FROM mensajes ORDER BY fecha DESC";
foreach($db->query($query) as $row) {	 $id_mensaje=$row['id_mensaje'];
	 $fecha=$row['fecha'];
	 $titulo=$row['titulo'];

     ($i%2==0) ? $class="tabla-fila-par" : $class="tabla-fila-impar";

     print "<tr class=\"$class\"><td>$id_mensaje<td><a href=\"index.php?accion=editar_mensaje&id_mensaje=$id_mensaje\">$fecha
              <td>$titulo";

     $i++;
}

?>
</tbody>
</table>
</center>