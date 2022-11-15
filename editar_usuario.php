<?
session_start();
include 'includes/_Policy.php';

include 'evento_funciones.php';

$id_usuario=$_GET['id_usuario'];
?>
<center>
<?
echo $_SESSION['msg'];
unset ($_SESSION['msg']);
?>
<br>
<form name="modifcar_usuario"  class="form-wrapper" action="editar_usuario_procesar.php" method="POST">
<table class="tabla_simple">
<?php

include 'includes/Open-Connection.php';

require_once "includes/class_usuario.php";
$usr=new usuario($id_usuario);
$imagen=$usr->get_imagen();

$query="SELECT * FROM usuarios WHERE id_usuario='$id_usuario'";
$stmt = $db->query($query);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$usuario=$usr->usuario;
$nombre=$usr->nombre;
$pago=$row['pago'];
$email=$row['email'];
$saldo=$row['saldo'];
$saldo=number_format($saldo,0,'.','.');
$recibir_correos=$row['recibir_correos'];

//validar si es admin
$query="SELECT * FROM administradores WHERE id_usuario='$id_usuario'";
$stmt = $db->query($query);
if ($stmt->rowCount()>0){
   $es_admin=true;
}


?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
<script type="text/javascript">

function showDialog(id) {

    $('<div>').dialog({
        modal: true,
        open: function () {
            $(this).load('carga_mueve_cuenta.php?id='+id);
        },
        close: function(event, ui) {
                $(this).remove();
            },
        height: 480,
        width: 400,
        title: 'Acreditar / Debitar',
        position: { my: 'top', at: 'top+50' },
    });

    return false;
}

</script>
<tr>
   <td>Avatar
   <td style="text-align:center"><img src="<?= $imagen ?>" style="max-width:140px; max-height:140px">
<tr>
   <td>Id
   <td><?= $id_usuario ?>
<tr>
   <td>Usuario
   <td><input type="text" class="form-text" size="10" name="usuario" value="<?= $usuario ?>" pattern="[a-z,A-Z,0-9]*">
<tr>
   <td>Nombre
   <td><input type="text" class="form-text" name="nombre" value="<?= $nombre ?>" pattern="[a-z,A-Z,,'á','é','í','ó','ú´','Á','É','Í','Ó','Ú',,'ñ','Ñ',' ']*">
<tr>
   <td>E-mail
   <td><input type="email" class="form-text" name="email" value="<?= $email ?>">

<tr>
   <td>Saldo
   <td>$<? echo $saldo; ?>
<tr>
   <td>Recibir Correos
   <td><input type="checkbox" name="recibir_correos"<? if ($recibir_correos) print " CHECKED"; ?>>
<tr>
   <td>Administrador
   <td><input type="checkbox" name="admin"<? if ($es_admin) print " CHECKED"; ?>>
   <input type="hidden" name="id_usuario" value="<?= $id_usuario ?>">
<tr>
   <td>Eventos Administrados
   <td>
<?
  $query="SELECT id_evento,evento FROM eventos WHERE admin='$id_usuario' and activo='1' ORDER BY evento ASC";
  foreach($db->query($query) as $row) {	 $evento=$row['evento'];
     $id_evento=$row['id_evento'];
     print"<a href=\"index.php?accion=evento_admin&id_evento=$id_evento&accion2=parametros\">$evento</a><br>\n";
  }
?>
<tr>
   <td>Eventos en los que participa
   <td>
<?
  $query="SELECT e.id_evento,e.evento FROM eventos as e,usuariosxevento as uxe
             WHERE e.id_evento=uxe.id_evento AND uxe.id_usuario='$id_usuario'  and e.activo='1' ORDER BY evento ASC";
  foreach($db->query($query) as $row) {	 $evento=$row['evento'];
     $id_evento=$row['id_evento'];
     $participando=validar_inscripcion($id_usuario,$id_evento);
     if ($participando==1){        $class="msg_ok";
        $title="Validado";
     }else if ($participando==2){        $class="msg_warn";
        $title="Esperando Validación";
     }
     print"<a href=\"index.php?accion=evento_admin&id_evento=$id_evento&accion2=editar_usuario&id_usuario=$id_usuario\">$evento</a>&nbsp;&nbsp;<span class=\"$class\" title=\"$title\">&nbsp;</span><br>\n";
  }
?>
<tr><td colspan="2" style="text-align: center;"><input type="submit" class="submit" value="Cambiar">

          </form>
<tr><td colspan="2"><form name="resetear_clave"  class="form-wrapper" method="POST" action="resetear_clave.php" >
   <table  class="tabla_simple" style="width:100%">
      <tr>
             <td style="text-align: center;">   <input type="button" value="Acreditar/Debitar" onclick="showDialog(<? echo $id_usuario ?>)">
             <td style="text-align: center;"><a href="index.php?accion=usuario_finanzas&id_usuario=<? echo $id_usuario; ?>"><input type="button" class="submit" value="Movimiento$"></a>
             <td style="text-align: center;">   <input type="submit" class="submit" value="Resetear Clave" onclick="return confirm('Está seguro de resetear la calve?')";>
          <input type="hidden" name="id_usuario" value="<?= $id_usuario ?>">

   </table></form>

</table>
</center>