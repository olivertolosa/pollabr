<?php
session_start();
include 'includes/_Policy.php';
require 'includes/Open-Connection.php';
require_once 'audit.php';
audit_max();

$destino=$_POST['destino'];
$mensaje_original=$_POST['mensaje'];

//print_r($_POST);



include 'function_correo.php';

if ($destino==-1){  //el correo va para todos   //armar la lista con los participantes
   $query="SELECT email FROM usuarios WHERE email!='' AND recibir_correos='1' AND last_login>'0000-00-00 00:00:00' AND email NOT LIKE '%banrep.gov.co%'";
   foreach($db->query($query) as $row) {
   	  //$emails_array[]=$row['email'];
   	  $destinatarios[]=$row['email'];
   }

}else{   $query="SELECT email FROM usuarios WHERE id_usuario='$destino' AND recibir_correos='1'";
   $stmt = $db->query($query);
   $row = $stmt->fetch(PDO::FETCH_ASSOC);
   $destinatarios[]=$row['email'];
}


//print_r($emails_array);

foreach($destinatarios as $email){
      $nombre="Notificaciones de ElGolGanador";
      $from="admin@elgolganador.com";
      $subject="Mensaje de El Gol Ganador";

//      $mensaje="Estimado usuario<br><br>El administrador de ElGolGanador le ha enviado el siguiente mensaje
      $mensaje="$mensaje_original";

      $mensaje=utf8_decode($mensaje);

//      $destinatario=implode(',',$destinatarios);

      $respuesta=envio_correo($email,$nombre,$from,$subject,$mensaje);
}
require 'includes/Close-Connection.php';

if (!headers_sent() && $msg == '') {
    $_SESSION['destino']=$destino;
    $_SESSION['msg']="<span class=\"msg_ok\">Notificacion enviada</span>";
    $_SESSION['mensaje']=$mensaje_original;
    $redirect="index.php?accion=notificaciones";
      header('Location: '.$redirect);
}
?>
