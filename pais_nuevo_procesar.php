<?php

session_start();
include 'includes/_Policy.php';
require 'includes/Open-Connection.php';
require_once 'audit.php';
audit_max();

//print_r($_POST);


$id_pais=$_POST['id_pais'];
$pais=$_POST['pais'];



$query="INSERT INTO paises VALUES('','$pais','1')";
//print "query=$query<br>";

$db->query($query);
$id_pais_new=$db->lastInsertId();

//ver si se modificó la imagen
if (file_exists("uploads/p".$id_pais.".png"))
   $extension=".png";
else if (file_exists("uploads/p".$id_pais.".PNG"))
   $extension=".PNG";
else if (file_exists("uploads/p".$id_pais.".jpg"))
    $extension=".jpg";
else if (file_exists("uploads/p".$id_pais.".gif"))
    $extension=".gif";
else if (file_exists("uploads/p".$id_pais.".JPG"))
    $extension=".JPG";
else if (file_exists("uploads/p".$id_pais.".bmp"))
    $extension=".bmp";
else if (file_exists("uploads/p".$id_pais.".BMP"))
    $extension=".BMP";

$file="uploads/p".$id_pais.$extension;

if (file_exists($file)){
      print "copiando $file --> imagenes/$id_item-$i$extension<br> ";
    if (copy($file,'imagenes/banderas/'.$id_pais_new.$extension)){
       	//borrar el archivo de la carpeta uploads
       	unlink ($file);
            print "copia ok<br>";
      }else{
             print "copia paila<br>";
      }
}
$_SESSION['msg']="<span class=\"msg_ok\">Pais Creado</span>";
$redirect="index.php?accion=pais_editar&id_pais=$id_pais_new";


if (!headers_sent() && $msg == '') {

     // header('Location: '.$redirect);
}
?>
