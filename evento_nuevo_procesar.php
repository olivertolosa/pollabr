<?php

session_start();
//include 'includes/_Policy.php';
require 'includes/Open-Connection.php';
require 'debug.php';

$debug=false;

if ($debug){	print_r($_REQUEST);
}

require_once 'audit.php';
audit_max();


$id_evento=$_POST['id_evento'];

$evento=$_POST['evento'];
$admin=$_POST['admin'];
$max_usuarios=$_POST['max_usuarios'];
$top_premios=$_POST['top_premios'];
$conf_usuarios=$_POST['conf_usuarios'];
($conf_usuarios) ? $conf_usuarios=1 : $conf_usuarios=0;
$valor=$_POST['valor'];
$porcentaje_premios=$_POST['porcentaje_premios'];
if ($porcentaje=='') $porcentaje=0;
$max_marcador=$_POST['max_marcador'];
$max_aleatorio=$_POST['max_aleatorio'];
$publica=$_POST['publica'];
($publica) ? $publica=1 : $publica=0;
$num_rondas=$_POST['num_rondas'];
$descripcion=$_POST['descripcion'];
$plantilla=$_POST['plantilla'];
$tipo_premios=$_POST['tipo_premios'];
$porcentaje=$_POST['porcentaje'];

if ($tipo_premios=="sin_premios"){
    $porcentaje_premios=0;
    $top_premios=0;
    $porcentaje=0;
}else if ($tipo_premios=="fijo"){
   	$porcentaje=0;
}



//validar que el evento no exista
$query="SELECT evento FROM eventos WHERE evento='$evento' AND activo='1'";
if ($debug) print "q=$query<br>";
$stmt = $db->query($query);
if ($stmt->rowCount()>0){
	 $_SESSION['msg']="Error: Ya existe un evento activo con ese nombre!!";
    $redirect="index.php?accion=evento_nuevo";
    header('Location: '.$redirect);
    exit();
}

$query="INSERT INTO eventos VALUES(NULL,'$evento','$descripcion','$admin','$max_usuarios','$valor','$tipo_premios','$porcentaje',
           '$top_premios','$conf_usuarios','$porcentaje_premios','$max_marcador','$max_aleatorio','$publica','$num_rondas','$plantilla','1')";
if ($debug) print "query=$query<br>";
$db->query($query);
$id_evento_new=$db->lastInsertId();

//print_r($_POST);

//procesar los nombres de las rondas
for ($i=1 ;  $i<=$num_rondas ; $i++){
   $label_ronda=$_POST['ronda'.$i];
   $grupo=$_POST['gruposronda'.$i];

//   ($grupo)? $grupo=1 : $grupo=0;

   //validar si ya existe un registro
   $query2="SELECT * FROM rondasxevento WHERE id_evento='$id_evento_new' AND num_ronda='$i'";

   $result2 = $db->query($query2);
   if ($result2->rowCount()>0){
   	   $query="UPDATE rondasxevento SET nombre='$label_ronda',grupo='$grupo' WHERE id_evento='$id_evento_new' AND num_ronda='$i	'";
   }else{
   	   $query="INSERT INTO rondasxevento (id_evento,num_ronda,nombre,grupos) VALUES ('$id_evento_new','$i','$label_ronda','$grupo')";
   }
if ($debug) print "q=$query<br>";
   	$result = $db->query($query);
}



//ver si se modificó la imagen
if (file_exists("uploads/e".$id_evento.".png"))
   $extension=".png";
else if (file_exists("uploads/e".$id_evento.".PNG"))
   $extension=".PNG";
else if (file_exists("uploads/e".$id_evento.".jpg"))
    $extension=".jpg";
else if (file_exists("uploads/e".$id_evento.".JPG"))
    $extension=".JPG";
else if (file_exists("uploads/e".$id_evento.".JPEG"))
    $extension=".JPEG";
else if (file_exists("uploads/e".$id_evento.".jpeg"))
    $extension=".jpeg";
else if (file_exists("uploads/e".$id_evento.".bmp"))
    $extension=".bmp";
else if (file_exists("uploads/e".$id_evento.".BMP"))
    $extension=".BMP";

$file="uploads/e".$id_evento.$extension;

if (file_exists($file)){
//      print "copiando $file --> imagenes/$id_item-$i$extension<br> ";
    if (copy($file,'imagenes/logos_eventos/'.$id_evento_new.$extension)){
       	//borrar el archivo de la carpeta uploads
       	unlink ($file);
//            print "copia ok<br>";
      }else{
//             print "copia paila<br>";
      }
}


$_SESSION['msg']="<span class=\"msg_ok\">Evento Creado</span>";
require 'includes/Close-Connection.php';

$redirect="index.php?accion=evento_detalle&id_evento=$id_evento_new";

if (!headers_sent() && $msg == '') {
     header('Location: '.$redirect);
}
?>
