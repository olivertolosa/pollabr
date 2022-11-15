<?
function randomPassword($pwlen) {

    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < $pwlen; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

if (isset($_POST['usuario'])){   $usuario=$_POST['usuario'];
   $email=$_POST['email'];

   //validar que los datos conincidan
   $query="SELECT * FROM usuarios WHERE usuario='$usuario' AND email='$email'";
   $stmt=$db->query($query);
   if ($stmt->rowCouhnt()==0){   	   $msg="<span class=\"msg_error\">Los datos son incorrectos</span>";
   }else{   	   //resetear la clave y mandarla por correo
   	   $pw=randomPassword(8);
   	   $pwmd5=md5($pw);
   	   $query="UPDATE usuarios SET password='$pwmd5' WHERE usuario='$usuario'";
//   	   print "q=$query<br>";
   	   $db->query($query);
   	   include 'function_correo.php';
   	   $nombre="Notificación de la polla";
       $from="lapolla@lapolla.com";
       $subject="Cambio de clave";

       $mensaje="Estimado usuario<br><br>Esta es su nuev clave para el ingreso a la polla
       <br><br>$pw
       <br><br>Recomendamos ingresar a la configuración de su cuenta y cambiar la clave.
      <br><br>La polla.";

      $respuesta=envio_correo($email,$nombre,$from,$subject,$mensaje);

      $msg="<span class=\"msg_ok\">Se ha enviado una nueva clave a su correo</span>";

   }

}

?>


<center>
<br>
<?
if (isset($msg))
   print $msg."<br><br>";
?>
<form name="recuperar_clave" class="form-wrapper" action="index.php?accion=recuperar_clave" method="POST" onsubmit="return validar_form(); return false;">
<table class="tabla_simple">
<tr>
   <td>Nombre de usuario
   <td title="Solamente se aceptan letras y números"><input type="text" size="40" name="usuario" value="<?= $usuario ?>" pattern="[a-z,A-Z,0-9,á,é,í,ó,ú,Á,É,Í,Ó,Ú,' ',-]*" required>
<tr>
   <td>Correo Elctrónico
   <td title="Solo usar números y letras"><input type="email" name="email" sieze="40" value="<?= $email ?>" required>
<tr>
   <td colspan="2" style="text-align: center;"><input type="submit" class="submit" value="Resetear clave">
</form>

</table>
</center>