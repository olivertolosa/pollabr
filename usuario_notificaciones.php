<?
//validar si vienen mensajes de respuesta
if (isset($_SESSION['destino'])){   $destino=$_SESSION['destino'];
   $mensaje=$_SESSION['mensaje'];
   $breaks = array("<br />","<br>","<br/>");
   $mensaje = str_ireplace($breaks, "\r\n", $mensaje);
   $msg=$_SESSION['msg']."<br>";
   unset($_SESSION['destino']);
   unset($_SESSION['mensaje']);
   unset($_SESSION['msg']);
}

if (isset($_GET['destino'])){   $destino=$_GET['destino'];
}


?>

<h2>Notificaciones</h2>

<?
print "$msg";
?>

<form name="notificaciones" class="form-wrapper" method="POST" action="usuario_notificaciones_procesar.php">
<table class="tabla_simple">
<tr>
   <th>Destinatario
   <td><SELECT name="destino">
          <option value="-1">Todos</option>
<?php

//armar la lista con los participantes
$query="SELECT id_usuario,usuario FROM usuarios WHERE email!='' AND recibir_correos='1' ORDER BY usuario ASC";
foreach($db->query($query) as $row) {	$id_usuario=$row['id_usuario'];
	$usuario=$row['usuario'];
	print "<option value=\"$id_usuario\"";
	if ($id_usuario==$destino) print " SELECTED";
	print ">$usuario</option>\n";
}

?>
</SELECT>
<tr>
   <th>Mensaje
   <td><textarea cols="50" rows="8" name="mensaje"><?= $mensaje ?></textarea>
<tr>
   <input type="hidden" name="id_evento" value="<?= $id_evento ?>">
   <td colspan="2"><center><input type="submit" class="submit" value="Enviar"></center>
</table>
</form>

