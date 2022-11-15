<?
session_start();
include 'includes/_Policy.php';

?>
<center>
<br>
<form name="usuario_nuevo" class="form-wrapper"action="usuario_nuevo_procesar.php" method="POST">
<table class="tabla_simple">
<tr>
   <td>Usuario
   <td><input type="text" class="form-text" size="10" name="usuario" required pattern="[a-z,A-Z,0-9]*">
<tr>
   <td>Nombre Completo
   <td><input type="text" class="form-text" name="nombre" required pattern="[a-z,A-Z,,'á','é','í','ó','ú´','Á','É','Í','Ó','Ú',,'ñ','Ñ',' ']*">
<tr>
   <td>Administrador
   <td><input type="checkbox" name="admin">
<tr>
   <td colspan="2" style="text-align: center;">   <input type="submit" class="submit" value="Crear Usuario">

<tr>
</table>
</form>
</center>