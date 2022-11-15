<?php
session_start();
include 'includes/_Policy.php';
require 'includes/Open-Connection.php';

require_once 'audit.php';
audit_max();


$id_usuario=$_POST['id_usuario'];
$clave=htmlspecialchars($_POST['clave1']);
$nombre=htmlspecialchars($_POST['nombre']);
$email=htmlspecialchars($_POST['email']);
$recibir_correos=$_POST['recibir_correos'];
//print_r($_POST);

($recibir_correos)? $recibir_correos=1 : $recibir_correos=0;

//remover bandera que obliga a cambio de clave
if ($clave!="")
   unset($_SESSION['cambia_clave']);


$query="UPDATE usuarios SET nombre='$nombre',recibir_correos='$recibir_correos' WHERE id_usuario='$id_usuario'";
$db->query($query);

//print "q=$query<br>";

if ($email!=""){   $query="UPDATE usuarios SET email='$email' WHERE id_usuario='$id_usuario'";
   $db->query($query);
}


if ($clave!=""){   $clave=md5($clave);   $query="UPDATE usuarios SET password='$clave' WHERE id_usuario='$id_usuario'";
//print "q=$query<br>";
  $db->query($query);
}


//ver si se modificó la imagen
if (file_exists("uploads/u".$id_usuario.".png"))
   $extension=".png";
else if (file_exists("uploads/u".$id_usuario.".PNG"))
   $extension=".PNG";
else if (file_exists("uploads/u".$id_usuario.".jpg"))
    $extension=".jpg";
else if (file_exists("uploads/u".$id_usuario.".JPG"))
    $extension=".JPG";
else if (file_exists("uploads/u".$id_usuario.".jpeg"))
    $extension=".jpeg";
else if (file_exists("uploads/u".$id_usuario.".JPEG"))
    $extension=".JPEG";
else if (file_exists("uploads/u".$id_usuario.".bmp"))
    $extension=".bmp";
else if (file_exists("uploads/u".$id_usuario.".BMP"))
    $extension=".BMP";
else if (file_exists("uploads/u".$id_usuario.".gif"))
    $extension=".gif";
else if (file_exists("uploads/u".$id_usuario.".GIF"))
    $extension=".GIF";

$file="uploads/u".$id_usuario.$extension;

//print "archivo upload:$file<br>";


if (file_exists($file)){

    //borrar la imagen existente
if (file_exists("imagenes/logos_usuarios/".$id_usuario.".png"))
   $extension=".png";
else if (file_exists("imagenes/logos_usuarios/".$id_usuario.".PNG"))
   $extension=".PNG";
else if (file_exists("imagenes/logos_usuarios/".$id_usuario.".jpg"))
    $extension=".jpg";
else if (file_exists("imagenes/logos_usuarios/".$id_usuario.".JPG"))
    $extension=".JPG";
else if (file_exists("imagenes/logos_usuarios/".$id_usuario.".jpeg"))
    $extension=".jpeg";
else if (file_exists("imagenes/logos_usuarios/".$id_usuario.".JPEG"))
    $extension=".JPEG";
else if (file_exists("imagenes/logos_usuarios/".$id_usuario.".bmp"))
    $extension=".bmp";
else if (file_exists("imagenes/logos_usuarios/".$id_usuario.".BMP"))
    $extension=".BMP";
else if (file_exists("uploads/u".$id_usuario.".gif"))
    $extension=".gif";
else if (file_exists("uploads/u".$id_usuario.".GIF"))
    $extension=".GIF";
//print "extension=$extension<br>";
$imagen1="imagenes/logos_usuarios/".$id_usuario.$extension;


    if (file_exists($imagen1)){
//       print "borrando $imagen1<br>";
       unlink($imagen1);
    }

    if (copy($file,'imagenes/logos_usuarios/'.$id_usuario.$extension)){
       	//borrar el archivo de la carpeta uploads
       	unlink ($file);
//            print "copia ok<br>";
      }else{
//             print "copia paila<br>";
      }
}


$_SESSION['msg']="<span class=\"msg_ok\">Datos modificados</span>";

//setear el id del usuario si no se ha hecho

if (!isset($_SESSION['usuario_polla'])){   $_SESSION['usuario_polla']="$id_usuario";
}

require 'includes/Close-Connection.php';
if (!headers_sent() && $msg == '') {
    $redirect="index.php?accion=micuenta";
      header('Location: '.$redirect);
}
?>
