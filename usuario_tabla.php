<?
   if (isset($_REQUEST['nombre']))
      $cad_query="WHERE nombre LIKE '%".$_REQUEST['nombre']."%'";
   else if (isset($_REQUEST['usuario']))
      $cad_query="WHERE usuario LIKE '%".$_REQUEST['usuario']."%'";

   require_once 'includes/Open-Connection.php';
?>
<table class="tabla_con_encabezado">
<thead>
<tr>
   <th class="hidden-xs">#<th>login<th>Nombre<th>Saldo<th>Estad<th>Eventos<th>Inactivos
</thead>
<tbody>
<?php

//armar la lista de eventos activos
$query="SELECT id_evento FROM eventos WHERE activo='1'";
$stmt = $db->query($query);

if ($stmt->rowCount()==0){
   $eventos_activos='0';  //se pone 0 como valor por defecto para evitar error de sintaxis con cadena vacia
}else{
	while ($row=$stmt->fetch(PDO::FETCH_ASSOC)){
	 $eventos_activos.=$row['id_evento'].",";
   }
   $eventos_activos=substr($eventos_activos,0,strlen($eventos_activos)-1);
}

//armar la lista de eventos inactivos
$query="SELECT id_evento FROM eventos WHERE activo='0'";
$stmt = $db->query($query);

if ($stmt->rowCount()==0){
   $eventos_inactivos='0';  //se pone 0 como valor por defecto para evitar error de sintaxis con cadena vacia
}else{
	while ($row=$stmt->fetch(PDO::FETCH_ASSOC)){
	 $eventos_inactivos.=$row['id_evento'].",";
   }
   $eventos_inactivos=substr($eventos_inactivos,0,strlen($eventos_inactivos)-1);
}


//print "inactivos=$eventos_inactivos<br>";
require_once 'includes/class_usuario.php';


$query="SELECT id_usuario,usuario,nombre,saldo FROM usuarios $cad_query";
$query.=" ORDER BY nombre ASC";
//print "q=$query<br>";




$stmt = $db->query($query);
$cadena="";
$num=$stmt->rowCount();
//print "num=$num<br>";
$i=1;
while($row=$stmt->fetch(PDO::FETCH_ASSOC)){	
   $id_usuario=$row['id_usuario'];
   $user=new usuario($id_usuario);
   $usuario=$row['usuario'];
   $nombre=$row['nombre'];
   $saldo=$row['saldo'];
   $saldo=number_format($saldo,0,'','.');

   //calcular el número de eventos activos en los que participa
   $query2="SELECT * FROM usuariosxevento WHERE id_usuario='$id_usuario' AND id_evento IN ($eventos_activos)";
//print "q2=$query2<br>";
   $stmt2 = $db->query($query2);
   $eventos=$stmt2->rowCount();

   //calcular el número de eventos inactivos en los que participa
   $query3="SELECT * FROM usuariosxevento WHERE id_usuario='$id_usuario' AND id_evento IN ($eventos_inactivos)";
//print "q3=$query3<br>";
   $stmt3 = $db->query($query3);
   $eventos_inac=$stmt3->rowCount();



//   ($pago)? $pago="Si" : $pago="No";

   ($i%2==0) ? $class="tabla-fila-par" : $class="tabla-fila-impar";

   print "<tr class=\"$class\">
           <td class=\"hidden-xs\">$i
           <td><a href=\"index.php?accion=editar_usuario&id_usuario=$id_usuario\">$usuario
              <td>";
//construir ruta a la imagen
//$extension=extension_imagen_usuario($id_usuario);

$img=$user->get_imagen();


   print "<img class=\"img_thumb\" width=\"45\" height=\"45\" src=\"$img\">  $nombre<td style=\"text-align: right;\">\$$saldo
            <td style=\"vertical-align:middle;text-align:center;cursor:pointer\"><img src=\"imagenes/stats_p.png\" style=\"width:45px;height:45px;\" onclick=\"showDialog($id_usuario)\"></a>
   <td style=\"text-align: center;\">$eventos\n";
   print "           <td style=\"text-align: center;\">$eventos_inac\n";
   $i++;
}

?>
</tbody>
</table>