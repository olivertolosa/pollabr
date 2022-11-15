<?
session_start();
include 'includes/_Policy.php';

require_once 'includes/class_equipo.php';
$eq=new equipo($db);

if (!isset($_SESSION['cambia_clave'])){
   include 'includes/_Policy.php';
}


$query="SELECT * FROM usuarios WHERE id_usuario='$id_usuario'";

$stmt=$db->query($query);
$row=$stmt->fetch(PDO::FETCH_ASSOC);

$usuario=$row['usuario'];
$nombre=$row['nombre'];
$email=$row['email'];
$recibir_correos=$row['recibir_correos'];
$saldo=$row['saldo'];
$saldo=number_format($saldo,0,'','.');



if (isset($_SESSION['mensaje'])){	$mensaje=$_SESSION['mensaje'];
	$_SESSION['mensaje']="";
	//imprimir mensaje si es que se está realizando modificación
    print "<center>$mensaje</center>";
}

?>
<script language="JavaScript">
function validarclave(fieldname) {



//Inicializar variables
var errorMsg = "";
var space  = " ";
fieldname   = document.forms.modificar.clave1;
fieldvalue  = fieldname.value;
fieldlength = fieldvalue.length;

if (document.forms.modificar.clave1.value=="" && document.forms.modificar.clave2.value=="")//no está modificando la clave...solo el nombre
   return true;

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
if (document.forms.modificar.clave1.value!=document.forms.modificar.clave2.value){
     alert ("La clave no coincide");
     return false;
}
     return true;
}

</script>

<link href="css/uploadfile.css" rel="stylesheet">
<script src="includes/jquery.uploadfile.min.js"></script>

<? /* http://hayageek.com/docs/jquery-upload-file.php.....--> upload script */?>

<script>
$(document).ready(function(){
       <?// if (!$mobile) print "\$.fn.bootstrapBtn = \$.fn.button.noConflict();"; ?>

	   $("#fileuploader").uploadFile({
	      url:"upload_file.php?id_usuario=<? echo $id_usuario; ?>",
	      fileName:"Filedata",
	      acceptFiles:"image/*",
	      dataType: "json",
          previewHeight: "100px",
          previewWidth: "100px",
          maxFileCount: 1,
          dragDrop: false,
          uploadStr: "Cargar nueva imagen",
          showFileCounter: false,
          showFileSize: false,
          showProgress: false,
          showPreview:false,
          showStatusAfterSuccess: false,
          onSuccess:function(files,data,xhr,pd){
              //files: list of files
              //data: response from server
              //xhr : jquer xhr object
             console.log(data);
             resp=JSON.parse(data);
             nombre=resp.name;
             $("#imagen_subida").attr("src","uploads/"+nombre);
           },
	   });
});

</script>


<center>
<?
echo $_SESSION['msg'];
unset ($_SESSION['msg']);

$upl_data="id_usuario=$id_usuario";
//if (!$mobile)
  // include 'includes/upload.php';


//validar si tiene imagen subida
require_once 'common.php';
$extension=extension_imagen_usuario($id_usuario);

$rnd=rand();

if ($extension){
   $img="imagenes/logos_usuarios/$id_usuario$extension?$rnd";
}else{
   $img="imagenes/person_placeholder.png";
}

 ?>


<form class="form-wrapper" action="micuenta_procesar.php" method="POST" name="modificar" onsubmit="return validarclave(document.forms.modificar.clave1);">

<table class="tabla_simple">
<input type="hidden" name="id_usuario" value="<?= $id_usuario ?>">
      <tr>
        <th height="45" colspan="3" valign="top">Detalle de Usuario</td>
          </tr>
      <tr>
        <th height="37" align="left" valign="middle" border="0">Nombre
<? if ($mobile) print "<tr>"; ?>
          <td><input type="text" name="nombre" value="<?= $nombre ?>"required pattern="[a-z,A-Z,,'á','é','í','ó','ú´','Á','É','Í','Ó','Ú',,'ñ','Ñ',' ']*">
<? if (!$mobile){?>
         <td rowspan="5">Avatar<br><div id="demo-portrait">
	<center><img src="<?= $img ?>" width="120" height="120" id="imagen_subida">
	<br><div id="fileuploader">Cargar nueva imagen</div></center>
</div>
<?
}
?>

      <tr>
         <th>Clave
<? if ($mobile) print "<tr>"; ?>
         <td><input type="password" name="clave1">
      <tr>
         <th>Confirmar Clave
<? if ($mobile) print "<tr>"; ?>
         <td><input type="password" name="clave2">
      <tr>
         <th>Correo electrónico
<? if ($mobile) print "<tr>"; ?>
         <td><input type="email" name="email" value="<?= $email ?>"  size="20">
      <tr>
<?php /*         <th>Saldo
<? if ($mobile) print "<tr>"; ?>
         <td>$<? echo $saldo ?>
      <tr>  */?>
         <th>Recibir notificaciones de administradores
<? if ($mobile) print "<tr>"; ?>
         <td style="text-align:center"><input type="checkbox" name="recibir_correos"<? if ($recibir_correos) print " CHECKED"; ?>>
<? if ($mobile){
?>
    <tr><td style="line-height:2px;">&nbsp;
    <tr><td style="text-align:center;"><img src="<?= $img ?>" width="120" height="120" id="imagen_subida">


<?}?>

      <tr>
          <td colspan="3"  style="text-align: center;"><input type="submit" value="Modificar Datos">
    </tr>
</table>
</form>
</center>
<? if ($mobile){ ?>
    * No es posible modificar el avatar desde un dispositivo móvil
<?}?>
