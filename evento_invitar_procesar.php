<?php
session_start();
$id_usuario=$_SESSION['usuario_polla'];
include 'includes/_Policy.php';
require 'includes/Open-Connection.php';
require_once 'audit.php';
audit_max();

$email=$_POST['email'];
$id_evento=$_POST['id_evento'];



//validar que el usuario no esté participando ya en el evento
$query="SELECT id_usuario FROM usuarios WHERE email='$email'";
$stmt = $db->query($query);
if ($stmt->rowCount()==1){ //hay un usuario registrado con ese correo
   $row=$stmt->fetch(PDO::FETCH_ASSOC);
   $id_usr=$row['id_usuario'];
   $query="SELECT id_usuario FROM usuariosxevento WHERE id_usuario='$id_usr' AND id_evento='$id_evento'";
   $stmt = $db->query($query);
   if ($stmt->rowCount()==1){ //el usuario está participando en el evento      $invitar=false;
      $_SESSION['msg']="<span class=\"msg_error\">El usuario asociado a ese correo ya está participando en el evento</span>";
      $_SESSION['email']=$email;
   }else{      $invitar=true;
   }

}else{	$invitar=true;
}

if ($invitar){

//print_r($_POST);

//obtener el nombre del evento para ponerlo en el asunto
$query="SELECT evento FROM eventos WHERE id_evento='$id_evento'";
$stmt = $db->query($query);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$nombre_evento=$row['evento'];

//guardar la invitación en la tabla de invitaciones
$key=substr(md5(rand()), 0, 12);
$query="INSERT INTO invitaciones VALUES ('$key','$id_usuario','$id_evento','$email',CURDATE())";
$db->query($query);

//print "query=$query<br>";


include 'function_correo.php';

//print_r($emails_array);

$nombre="Notificación de ElGolGanador";
$from="invitaciones@elgolganador.com";
$subject="Invitación al evento $nombre_evento";

$mensaje="Estimado usuario<br><br>El administrador del evento <span style=\"font-style: italic; font-weight:bold\">\"$nombre_evento\"</span>
       le ha enviado una participación para participar en el mismo.
      <br><br>Si desea participar por favor haga clic en el siguiente enlace.
      <br><br><a href=\"http://www.elgolganador.com/index.php?accion=procesar_invitacion&id_invitacion=$key&email=$email\">Aceptar la invitación</a>
      <br><br>La polla.";

$respuesta=envio_correo($email,$nombre,$from,$subject,$mensaje);
   	  $_SESSION['msg']="<span class=\"msg_ok\">Notificacion enviada</span>";

}
require 'includes/Close-Connection.php';

if (!headers_sent() && $msg == '') {
    $redirect="index.php?accion=evento_admin&id_evento=$id_evento&accion2=invitar";
      header('Location: '.$redirect);
}
?>
