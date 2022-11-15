<?
//si el usuario está logueado tomar el dato del email
$flag_email=false;
$query="SELECT email FROM usuarios WHERE id_usuario='$id_usuario'";
$stmt = $db->query($query);
if ($stmt->rowCount()>0){   $row = $stmt->fetch(PDO::FETCH_ASSOC);
   $email=$row['email'];
   $flag_email=true;
}

  if (isset($_SESSION['msg'])){
   echo $_SESSION['msg'];
   unset ($_SESSION['msg']);
   print "<br><br>";
  }

?>
<form  class="form-wrapper" action="contacto_procesar.php" method="POST" name="contacto">
<table class="tabla_simple">

     <tr>
        <th><label class="requiredlabel">Correo Electrónico</label>
          <td style="width:240px;">
              <div class="required"> <input type="email" name="e-mail" <? if ($flag_email){ print "value=\"$email\" "; } ?>required></div>

      <tr>
        <th><label class="requiredlabel">Motivo del contacto</label>
             <td title="Solo se permiten letras, numeros, espacio y el signo ?"><input type="text"  name="titulo" pattern="[a-z,A-Z,,'á','é','í','ó','ú´','Á','É','Í','Ó','Ú',' ','?',1-9]*" required>
          </div>
      <tr>
        <th><label class="requiredlabel">Mensaje</label>
       <td title="Solo se permiten letras, numeros, espacio y el signo ?">
       <textarea cols="30" rows="5" name="mensaje" pattern="[a-z,A-Z,,'á','é','í','ó','ú´','Á','É','Í','Ó','Ú',' ','?']*" required></textarea>
     <tr>
          <td colspan="2" style="text-align: center;"><input type="submit" class="submit" value="Enviar">
</table>


</form>

Si requiere ayuda sobre algún ítem coloque el mouse sobre él.
