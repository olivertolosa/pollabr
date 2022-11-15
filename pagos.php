<?php

session_start();
include 'includes/_Policy.php';
?>
<h2> Pagos recibidos</h2>

<?
//obtener la lista de admins
$query="SELECT id_usuario FROM administradores";
$result = mysql_query($query) or die(mysql_error());
while ($row=mysql_fetch_assoc($result)){   $id_admin=$row['id_usuario'];
   //obtener el nombre del administrador
   $query_nombre="SELECT nombre FROM usuarios WHERE id_usuario='$id_admin'";
   $result_nombre = mysql_query($query_nombre) or die(mysql_error());
   $row_nombre=mysql_fetch_assoc($result_nombre);
   $nombre_admin=$row_nombre['nombre'];
   mysql_free_result($result_nombre);
   print "<h3>$nombre_admin</h3>\n";
   print "<table border=\"1\">\n";
   //ver los pagos recibidos por cada admin
   $query_pagos="SELECT u.nombre,a.fecha,a.pago
           FROM usuarios as u, auditoria as a
           WHERE a.id_admin='$id_admin'
           AND a.id_usuario=u.id_usuario
           ORDER BY u.nombre ASC";
//print "q=$query_pagos<br>";
   $result_pagos=mysql_query($query_pagos) or die(mysql_error());
   $plata_recibida=0;

   while ($row_pagos=mysql_fetch_assoc($result_pagos)){   	   $nombre=$row_pagos['nombre'];
   	   $fecha=$row_pagos['fecha'];
   	   $pago=$row_pagos['pago'];
   	   print "<tr><td>$nombre<td>$fecha<td>$pago</tr>\n";
   	   ($pago) ? $plata_recibida++ : $plata_recibida--;

   }
   print "</table>";
   print "total recibido: $". number_format(($plata_recibida*5000),0,'.','.');
   print "<br><br>";


}

?>
