<?php

session_start();
include 'includes/_Policy.php';
require 'includes/Open-Connection.php';


$id_pais=$_POST['id_pais'];
$pais=$_POST['pais'];
$activo=$_POST['activo'];
($activo)? $activo=1 : $activo=0;


$query="UPDATE paises SET pais='$pais',activo='$activo' WHERE id_pais='$id_pais'";
//print "query=$query<br>";
$db->query($query);

//ver si se modificó la imagen
if (file_exists("uploads/p".$id_pais.".png"))
   $extension=".png";
else if (file_exists("uploads/p".$id_pais.".PNG"))
   $extension=".PNG";
else if (file_exists("uploads/p".$id_pais.".jpg"))
    $extension=".jpg";
else if (file_exists("uploads/p".$id_pais.".JPG"))
    $extension=".JPG";
else if (file_exists("uploads/p".$id_pais.".bmp"))
    $extension=".bmp";
else if (file_exists("uploads/p".$id_pais.".BMP"))
    $extension=".BMP";
else if (file_exists("uploads/p".$id_pais.".gif"))
    $extension=".gif";

$file="uploads/p".$id_pais.$extension;

//print "archivo upload:$file<br>";


if (file_exists($file)){
    //borrar la imagen existente
    include 'common.php';
    $extension2=extension_imagen($id_pais);
//    print "extension=$extension2<br>";
    $imagen1="imagenes/banderas/".$id_pais.$extension2;


    if (file_exists($imagen1)){
//       print "borrando $imagen1<br>";
       unlink($imagen1);
    }

    if (copy($file,'imagenes/banderas/'.$id_pais.$extension)){
       	//borrar el archivo de la carpeta uploads
       	unlink ($file);
//            print "copia ok<br>";
      }else{
//             print "copia paila<br>";
      }
}
$_SESSION['msg']="<span class=\"msg_ok\">País Modificado</span>";
require 'includes/Close-Connection.php';

if (!headers_sent() && $msg == '') {
    $redirect="index.php?accion=pais_editar&id_pais=$id_pais";
      header('Location: '.$redirect);
}
?>
