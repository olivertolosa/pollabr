<?php

//print_r($_POST);
//exit();

session_start();
$email=$_POST['e-mail'];
$titulo=$_POST['titulo'];
$msg=$_POST['mensaje'];
$ip=$_SERVER['REMOTE_ADDR'];


if (!isset($_POST) or !isset($_POST['e-mail']) or $_POST['e-mail']==''
       or !isset($_POST['titulo']) or $_POST['titulo']==''
       or !isset($_POST['mensaje']) or $_POST['mensaje']==''){  //si algo viene en blanco pueden estar intentado acceder a esta pag directo...no procesar   	$redirect="index.php?accion=contacto";
   	$_SESSION['msg']="<span class=\"msg_error\">Error procesando mensaje</span>";
   	if (!headers_sent()) {
     header('Location: '.$redirect);
    }
}else{


include 'function_correo.php';

$nombre="Notificaciones ElGolGanador";
$from="contacto@elgolganador.com";
$subject="Formulario de contacto de ELGolGanador";



$mensaje="Se ha recibido un formulario de contacto con el siguiente mensaje:
      <br><br>Asunto: $titulo
      <br><br>$msg
      <br><br>Escrito por $email
      <br><br>ElGolGanador.
      <br><br>Desde: ".$ip;

      $respuesta=envio_correo("otolosa@gmail.com",$nombre,$from,$subject,$mensaje);

$_SESSION['msg']="<span class=\"msg_ok\">Mensaje procesado</span>";

$redirect="index.php";

if (!headers_sent()) {
     header('Location: '.$redirect);
}
}

?>
