<?
//validar si vienen mensajes de respuesta
if (isset($_SESSION['email'])){	$email=$_SESSION['email'];
	unset($_SESSION['email']);}

?>
<p>Por favor escriba el correo electrónico de la persona a la que desea invitar</p>
<form name="notificaciones" class="form-wrapper" method="POST" action="evento_invitar_procesar.php">
<table class="tabla_simple">
<tr>
   <th>Destinatario
   <td><input type="email" name="email" value="<?= $email ?>">
   <input type="hidden" name="id_evento" value="<?= $id_evento ?>">
<tr>
   <input type="hidden" name="id_evento" value="<?= $id_evento ?>">
   <td colspan="2"><center><input type="submit" class="submit" value="Enviar"></center>
</table>
</form>

