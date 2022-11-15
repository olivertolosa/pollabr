
<?php

$id_usuario=$_GET['id_usuario'];
$id_evento=$_GET['id_evento'];
?>
<center>

<?php
if (isset($_SESSION['msg'])){
   echo $_SESSION['msg'];
   unset ($_SESSION['msg']);
}
?>
<form name="modifcar_usuario" action="editar_usuarioxevento_procesar.php" method="POST">
<table class="tabla_simple">
<?php

$query="SELECT u.usuario,u.nombre,uxe.validado,u.saldo FROM usuarios as u, usuariosxevento as uxe
           WHERE u.id_usuario=:id_usuario
           AND u.id_usuario=uxe.id_usuario
           AND uxe.id_evento=:id_evento";
//print "q=$query<br>";
$stmt= $db->prepare($query);
$stmt->bindParam(':id_usuario',$id_usuario);
$stmt->bindParam(':id_evento',$id_evento);
$stmt->execute();
$row=$stmt->fetch(PDO::FETCH_ASSOC);
$usuario=$row['usuario'];
$nombre=$row['nombre'];
$validado=$row['validado'];
$saldo=$row['saldo'];


//validar si es admin
$query="SELECT validado FROM usuariosxevento WHERE id_usuario=:id_usuario ANd id_evento=:id_evento";
$stmt= $db->prepare($query);
$stmt->bindParam(':id_usuario',$id_usuario);
$stmt->bindParam(':id_evento',$id_evento);
$stmt->execute();
$row=$stmt->fetch(PDO::FETCH_ASSOC);
$validado=$row['validado'];


//averiguar si el evento maneja validación de usuarios
$query="SELECT conf_usuarios FROM eventos WHERE id_evento=:id_evento";
//print "q=$query";
$stmt= $db->prepare($query);
$stmt->bindParam(':id_evento',$id_evento);
$stmt->execute();
$row=$stmt->fetch(PDO::FETCH_ASSOC);
$conf_usuarios=$row['conf_usuarios'];


//obtener el valor de la inscripción
require_once "includes/class_evento.php";
$eventoobj=new evento($db);
$valor=$eventoobj->get_valor($id_evento);

if ($saldo<$valor and $validado==0) $disabled=" DISABLED";


$saldo=number_format($saldo,0,',','.');

require_once "includes/class_usuario.php";
$usr=new usuario($id_usuario);
$imagen=$usr->get_imagen($id_usuario);



?>
<tr>
   <td>Avatar
   <td style="text-align:center"><img src="<?php echo $imagen; ?>" style="max-width:140px; max-height:140px">
<tr>
   <td>Usuario
   <td><?php echo $usuario; ?>
<tr>
   <td>Nombre
   <td><?php echo $nombre; ?>
<?php /*<tr>    //habilitar si se manejan saldos
  <td>Saldo
  <td>$<?php echo $saldo; ?>   */?>
<? if ($conf_usuarios){?>

<tr>
   <td>Validado
   <td><input type="checkbox" name="validado"<?php if ($validado) print " CHECKED"; ?>>
   <input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>">
   <input type="hidden" name="id_evento" value="<?php echo $id_evento; ?>">

<tr>
   <td colspan="2" style="text-align: center;">   <input type="submit" class="submit" value="Cambiar"<?php echo $disabled; ?>>
<?
}
?>
</form>
</table>
<br><br>
<a href="index.php?accion=evento_admin&id_evento=<?php echo $id_evento; ?>&accion2=listar_usuarios">Volver a la lista de usuarios</a>
</center>