<?php

session_start();
include 'includes/_Policy.php';
require 'includes/Open-Connection.php';
require_once 'audit.php';
audit_max();

//print_r($_POST);


$id_equipo=$_POST['id_equipo'];
$grupo=$_POST['grupo_equipos'];
$equipo=$_POST['equipo'];
$equipols=$_POST['equipols'];


//validar si existe otro equipo llamado igual en el mismo grupo
$query="SELECT * FROM equipos WHERE equipo='$equipo' AND id_grupo_equipos='$grupo'";
$stmt = $db->query($query);
if ($stmt->rowCount()>0){   $_SESSION['msg']="<span class=\"msg_error\">Ya existe otro equipo llamado igual</span>";
   $redirect="index.php?accion=equipo_nuevo";
}else{

$query="INSERT INTO equipos VALUES('','$equipo','$equipols','','','$grupo')";
//print "query=$query<br>";

$db->query($query);
$id_equipo_new=$db->lastInsertId();

//ver si se modificó la imagen
if (file_exists("uploads/".$id_equipo.".png"))
   $extension=".png";
else if (file_exists("uploads/".$id_equipo.".PNG"))
   $extension=".PNG";
else if (file_exists("uploads/".$id_equipo.".jpg"))
    $extension=".jpg";
else if (file_exists("uploads/".$id_equipo.".gif"))
    $extension=".gif";
else if (file_exists("uploads/".$id_equipo.".JPG"))
    $extension=".JPG";
else if (file_exists("uploads/".$id_equipo.".bmp"))
    $extension=".bmp";
else if (file_exists("uploads/".$id_equipo.".BMP"))
    $extension=".BMP";

$file="uploads/".$id_equipo.$extension;

if (file_exists($file)){
//      print "copiando $file --> imagenes/$id_item-$i$extension<br> ";
    if (copy($file,'imagenes/logos_equipos/'.$id_equipo_new.$extension)){
       	//borrar el archivo de la carpeta uploads
       	unlink ($file);
//            print "copia ok<br>";
      }else{
//             print "copia paila<br>";
      }
}
$_SESSION['msg']="<span class=\"msg_ok\">Equipo Creado</span>";
$redirect="index.php?accion=editar_equipo&id_equipo=$id_equipo_new";
}

if (!headers_sent() && $msg == '') {

      header('Location: '.$redirect);
}
?>
