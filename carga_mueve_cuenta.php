<?php

session_start();
include 'includes/_Policy.php';

require 'includes/Open-Connection.php';
include 'includes/class_usuario.php';
include 'audit.php';
audit_max();

$id_usuario_admin=$_SESSION['usuario_polla'];

$id_usuario=$_REQUEST['id'];

$usr=new usuario($id_usuario);
$imagen=$usr->get_imagen();

$usuario=$usr->usuario;
$nombre=$usr->nombre;
$saldo=$usr->get_saldo();
$saldo=number_format($saldo,0,'.','.');

//validar si es admin
$es_admin=false;
$query="SELECT * FROM administradores WHERE id_usuario='$id_usuario_admin'";
$stmt = $db->query($query);
if ($stmt->rowCount()>0){
   $es_admin=true;
}

if (!$es_admin){  print "Acceso no autorizado";
  exit();
}



?>

<link rel="stylesheet" type="text/css" href="css/polla.css" />


<center>
<div>
<form name="acreditar" method="POST" action="acreditar_cuenta.php">
   <table class="tabla_simple">
      <th>Id<td><? echo $id_usuario; ?>
      <tr><th>Usuario<td style="text-align:center"><img src="<? echo $imagen ?>" style="max-width:80px;max-height:80px">
                                              <br><? echo $usuario; ?>

      <tr><th>Saldo actual
          <td>$<? echo $saldo; ?>
      <tr><th>Operación
          <td><SELECT name="operacion">
               <option>Acreditar
               <option>Debitar
              </SELECT>
      <tr><th>Monto
          <td><input type="number" name="monto" min="1" style="width:100px" required>
      <tr><th>Operación
          <td><input type="text" name="descripcion" style="width:150px" required>
          <input type="hidden" name="id_usuario" value="<? echo $id_usuario; ?>">
      <tr><td colspan="2" style="text-align:center"><input type="submit" value="Realizar operación">
</form>
</div>

</center>
