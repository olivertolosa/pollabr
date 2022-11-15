<?php

session_start();
//include 'includes/_Policy.php';
require 'includes/Open-Connection.php';
require_once 'audit.php';
audit_max();


$usuario=$_POST['usuario'];
$usuario=strtolower($usuario);
$nombre=$_POST['nombre'];
$es_admin=$_POST['admin'];
$regusuario=$_POST['regusuario'];
$clave=$_POST['clave1'];
$email=$_POST['email'];
if ($clave!="")
   $clave=md5($clave);
$captcha=$_POST['captcha'];



//doble validación de captcha x si acaso
if (strtolower($captcha)!=strtolower($_SESSION['captcha']['code'])){   $_SESSION['msg']="<span class=\"msg_error\">Captcha Incorrecto!!</span>";
   $redirect="index.php?accion=registro";
   header('Location: '.$redirect);
   exit();
}

//validar que los datos traen parámetros
if ($usuario=='' or $nombre=='' or $clave==''){   $_SESSION['msg']="<span class=\"msg_error\">Datos incompletos</span>";
   $redirect="index.php?accion=registro";
   header('Location: '.$redirect);
   exit();
}
//validar que el usuario no esté registrado
$query="SELECT * FROM usuarios WHERE usuario='$usuario'";
$stmt=$db->query($query);
if ($stmt->rowCount()>0){   $_SESSION['msg']="<span class=\"msg_error\">el usuario ya existe!!</span>";
   $redirect="index.php?accion=registro";
   header('Location: '.$redirect);
   exit();
}

$fecha=date('Y-m-d H:i');


$query="INSERT INTO usuarios VALUES(NULL,:usuario,:clave,:nombre,:email,:fecha,'1','0')";
//print "query=$query<br>";
$stmt= $db->prepare($query);
$stmt->bindParam(':usuario',$usuario);
$stmt->bindParam(':clave',$clave);
$stmt->bindParam(':nombre',$nombre);
$stmt->bindParam(':email',$email);
$stmt->bindParam(':fecha',$fecha);
$stmt->execute();
$id_usuario= $db->lastInsertId();;


//marca de adminsitrador
if ($es_admin){ //marcado como admin-->validar si ya está o sino incluirlo
      $query="INSERT INTO administradores VALUES ('$id_usuario')";
      $db->query($query);
}
$_SESSION['msg']="Usuario Creado";
require 'includes/Close-Connection.php';

//si es un autoregistro enviar a página de inicio
//de lo contrario a página de admin de usuario
if ($regusuario){	$_SESSION['usuario_polla'] = "$id_usuario";    $redirect="index.php";
}else{
    $redirect="index.php?accion=editar_usuario&id_usuario=$id_usuario";
}

if (!headers_sent() && $msg == '') {
     header('Location: '.$redirect);
}
?>
