
<?
   session_start();
   if (isset($_SESSION['msg'])){
      print "<br><br>";
      echo $_SESSION['msg'];
      unset($_SESSION['msg']);
   }

   include 'simple-php-captcha.php';
   $_SESSION['captcha'] = simple_php_captcha();
   $img_captcha=$_SESSION['captcha']['image_src'];

?>

<script language="JavaScript">
function validarclave(fieldname) {

//Inicializar variables
var errorMsg = "";
var space  = " ";
fieldname   = document.forms.registro.clave1;
fieldvalue  = fieldname.value;
fieldlength = fieldvalue.length;


//No debe contener espacios
if (fieldvalue.indexOf(space) > -1) {
     errorMsg += "\nLa clave no puede contener espacios.\n";
}

//La clave debe incluir un número
//if (!(fieldvalue.match(/\d/))) {
//     errorMsg += "\nLa clave debe incluir un número.\n";
//}

//La clave debe incluir una letra
//if (!(fieldvalue.match(/[a-zA-Z]+/))) {
//     errorMsg += "\nLa clave debe incluir una letra.\n";
//}

//La clave debe incluir una letra mayúscula
//if (!(fieldvalue.match(/[A-Z]/))) {
//     errorMsg += "\nLa clave debe incluir una letra mayúscula.\n";
//}

//La clave debe incluir una letra minúsucla
//if (!(fieldvalue.match(/[a-z]/))) {
//     errorMsg += "\nLa clave debe incluir una letra minúsucla.\n";
//}
//La clave debe incluir un caracter especial
//if (!(fieldvalue.match(/\W+/))) {
//     errorMsg += "\nLa clave debe incluir un caracter especial - #,@,%,!\n";
//}

//La clave debe tener al menos 6 caracteres
if (!(fieldlength >= 6)) {
     errorMsg += "\nLa clave debe tener al menos 6 caracteres.\n";
}


//If there is aproblem with the form then display an error
if (errorMsg != ""){
     msg = "______________________________________________________\n\n";
     msg += "Por favor corrija los errores en la clave.\n";
     msg += "______________________________________________________\n";
     errorMsg += alert(msg + errorMsg + "\n\n");
     fieldname.focus();
     return false;
}
if (document.forms.registro.clave1.value!=document.forms.registro.clave2.value){
     alert ("La clave no coincide");
     return false;
}

if (!document.forms.registro.acepto.checked){
     alert ("Debe aceptar el reglamento!!!");
     return false;
}
     return true;
}

</script>

<script type="text/javascript">
$(document).ready(function(){
   $("#user").change(function(){
      $("#message1").html("<img src='imagenes/loading.gif'/> Validando...");

      var username=$("#user").val();

      $.ajax({
          type:"post",
          url:"check_user.php",
          data:"user="+username,
          success:function(data){          	//alert (data);
               if(data==0){
                  $("#message1").html("<img src='imagenes/ok.png' style='height:25px;width:25px;' title='Usuario Disponible'/>Usuario Disponible");
               }
               else{
                  $("#message1").html("<img src='imagenes/error.png'  style='height:25px;width:25px;' title='Usuario No Disponible'/>Usuario No Disponible");
               }
          }
      });
   });

   $("#captcha").change(function(){   	    var captcha=$("#captcha").val();
        $.ajax({
          type:"post",
          url:"check_captcha.php",
          data:"captcha="+captcha,
          success:function(data){
          	//alert (data);
               if(data==1){
                  $("#message5").html("<img src='imagenes/ok.png' style='height:25px;width:25px;' title='Código Ok'/>Código Ok");
                  $("#submit").prop('disabled', false);
               }
               else{
                  $("#message5").html("<img src='imagenes/error.png'  style='height:25px;width:25px;' title='Código erróneo'/>Código erróneo");
                  $("#submit").prop('disabled', true);
               }
          }
      });

   });
});
</script>


<center>


<form  class="form-wrapper" action="usuario_nuevo_procesar.php" method="POST" name="registro" onsubmit="return validarclave(document.forms.registro.clave1);">
<table style="width: 480px" class="tabla_simple">

<input type="hidden" name="regusuario" value="1">
      <tr>
        <th colspan="3">Detalle de Usuario</td>
      <tr>
        <td style="width:110px";>Usuario
          <td style="width:140px;" title="Solo se permiten minúsculas y números"><input type="text" name="usuario" id="user" pattern="[a-z,0-9]*" required>
          <td style="border-left:0px; width: 170px;"><div id="message1"></div>
      <tr>
        <td>Nombre Completo
          <td><input type="text" title="Solo se permiten mayúsculas, minúsculas y espacio" name="nombre" pattern="[a-z,A-Z,,'á','é','í','ó','ú´','Á','É','Í','Ó','Ú',,'ñ','Ñ',' ']*" required>
          <td style="border-left:0px;><div id="message2"></div>
      <tr>
        <td>E-mail
          <td><input type="email" name="email" required title="Ingrese un email válido">
          <td style="border-left:0px; ><div id="message2"></div>

      <tr>
         <td>Clave
         <td><input type="password" name="clave1" required>
         <td style="border-left:0px;"><div id="message3"></div>
      <tr>
         <td>Confirmar Clave
         <td style="text-align:center"><input type="password" name="clave2">
         <td style="border-left:0px; "><div id="message4"></div>
      <tr>
         <td>No soy un robot
         <td style="text-align:center"><img src="<? print $img_captcha; ?>"><br><input type="text" name="captcha" id="captcha" pattern="[a-z,A-Z,0-9]*" style="width: 100px;">
         <td style="border-left:0px; "><div id="message5"></div>
      <tr>
         <td colspan="3" style="text-align: center;"><input type="checkbox" name="acepto">He leído y acepto el <a href="index.php?accion=reglamento">reglamento</a> en su totalidad.
      <tr>
          <td colspan="3" style="text-align: center;"><input type="submit" id="submit" value="Registrarse" disabled>
    </tr>
</table>


</form>

* Si requiere ayuda sobre algún ítem coloque el mouse sobre él.
