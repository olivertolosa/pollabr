<?
require_once 'includes/_Policy.php';

require_once 'includes/Open-Connection.php';

require_once 'includes/class_usuario.php';


$id_evento=$_GET['id_evento'];
if (isset($_GET['validado']))
   $validado=$_GET['validado'];
else
   $validado=-1;

//print_r ($_GET);

//averiguar si el evento maneja validación de usuarios
   $query="SELECT conf_usuarios FROM eventos WHERE id_evento=:id_evento";
//print "q=$query";
	$stmt= $db->prepare($query);
	$stmt->bindParam(':id_evento',$id_evento);
	$stmt->execute();
   $row=$stmt->fetch(PDO::FETCH_ASSOC);
   $conf_usuarios=$row['conf_usuarios'];

//print "conf_usuarios=$conf_usuarios<br>validado=$validado<br>";
?>
<center>
<table class="tabla_con_encabezado">
<thead>
<tr>
   <th>#<th>login<th>Nombre
<?
if ($id_evento>0 and $conf_usuarios)
   print "<th>Validado\n";
?>
</thead>
<tbody>
<?php

$query="SELECT e.id_usuario,u.nombre FROM usuariosxevento as e, usuarios as u WHERE e.id_evento=:id_evento AND e.id_usuario=u.id_usuario";

   if ($conf_usuarios and ($validado==0 or $validado==1)){
      $query.=" AND validado=:validado";
      //print "conf=$conf_usuarios<br>";
   }
  $query.=" ORDER BY nombre ASC";
//print "q=$query<br>";

$stmt= $db->prepare($query);
$stmt->bindParam(':id_evento',$id_evento);
if ($conf_usuarios and ($validado==0 or $validado==1)) $stmt->bindParam(':validado',$validado);
$stmt->execute();
$cadena="";
//$num=$stmt->rowCount();
//print "num=$num<br>";
$i=1;


require_once 'includes/class_usuario.php';


while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
   $id_usuario=$row['id_usuario'];
   $user=new usuario($id_usuario);
   $usuario=$user->usuario;
   $nombre=$user->nombre;

   $img=$user->get_imagen();

   ($i%2==0) ? $class="tabla-fila-par" : $class="tabla-fila-impar";

   print "<tr class=\"$class\"><td>$i

   <td><a href=\"index.php?accion=evento_admin&id_evento=$id_evento&accion2=editar_usuario&id_usuario=$id_usuario\"><img class=\"img_thumb\"  width=\"45\" height=\"45\" src=\"$img\">$user->usuario
              <td>";



   print "           $nombre";
if ($id_evento>0 and $conf_usuarios){
   //validar si el usuario fue validado en este evento
   $query_usuario="SELECT * FROM usuariosxevento WHERE id_usuario=:id_usuario AND id_evento=:id_evento AND validado='1'";
   $stmt= $db->prepare($query,array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
   $stmt->bindParam(':id_usuario',$id_usuario);
   $stmt->bindParam(':id_evento',$id_evento);
   $stmt->execute();

   ($stmt_usuario->rowCount()==1) ? $pago="Si" : $pago="No";
   print "<td style=\"text-align: center;\">$pago\n";
}
   $i++;
}

?>
</tbody>
</table>
</center>
<?
//require_once 'includes/Close-Connection.php';
?>