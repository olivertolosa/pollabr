<?php

session_start();
include 'includes/_Policy.php';
require 'includes/Open-Connection.php';


$id_equipo=$_POST['id_equipo'];
$grupo=$_POST['grupo_equipos'];
$equipo=$_POST['equipo'];
$equipo=mysql_escape_string($equipo);
$equipols=$_POST['equipols'];
$equipols=mysql_escape_string($equipols);
$equipols2=$_POST['equipols2'];
$equipols2=mysql_escape_string($equipols2);
$equipols3=$_POST['equipols3'];
$equipols3=mysql_escape_string($equipols3);


$query="UPDATE equipos SET equipo='$equipo',equipoLS='$equipols',equipoLS2='$equipols2',equipols3='$equipols3',id_grupo_equipos='$grupo' WHERE id_equipo='$id_equipo'";
//print "query=$query<br>";
$db->query($query);

//ver si se modificó la imagen
if (file_exists("uploads/".$id_equipo.".png"))
   $extension=".png";
else if (file_exists("uploads/".$id_equipo.".PNG"))
   $extension=".PNG";
else if (file_exists("uploads/".$id_equipo.".jpg"))
    $extension=".jpg";
else if (file_exists("uploads/".$id_equipo.".JPG"))
    $extension=".JPG";
else if (file_exists("uploads/".$id_equipo.".bmp"))
    $extension=".bmp";
else if (file_exists("uploads/".$id_equipo.".BMP"))
    $extension=".BMP";
else if (file_exists("uploads/".$id_equipo.".gif"))
    $extension=".gif";

$file="uploads/".$id_equipo.$extension;

//print "archivo upload:$file<br>";


if (file_exists($file)){
    //borrar la imagen existente
    include 'common.php';
    $extension2=extension_imagen($id_equipo);
//    print "extension=$extension2<br>";
    $imagen1="imagenes/logos_equipos/".$id_equipo.$extension2;


    if (file_exists($imagen1)){
//       print "borrando $imagen1<br>";
       unlink($imagen1);
    }

    if (copy($file,'imagenes/logos_equipos/'.$id_equipo.$extension)){
       	//borrar el archivo de la carpeta uploads
       	unlink ($file);
//            print "copia ok<br>";
      }else{
//             print "copia paila<br>";
      }
}
$_SESSION['msg']="<span class=\"msg_ok\">Equipo Modificado</span>";
require 'includes/Close-Connection.php';

if (!headers_sent() && $msg == '') {
    $redirect="index.php?accion=editar_equipo&id_equipo=$id_equipo";
      header('Location: '.$redirect);
}
?>
