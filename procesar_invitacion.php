<?php

session_start();
require_once 'audit.php';
audit_max();

   $email=$_GET['email'];
   $key=$_GET['id_invitacion'];

//validar si hay un usuario logueado
if (isset($_SESSION['usuario_polla'])){  //hay usuario logueado
//print "SI hay usuario logueado";   $id_usuario=$_SESSION['usuario_polla'];
   //si es el mismo usuario logueado presentar la pag de ingreso al evento
   //obtener el email del usuario logueado y el de la invitacion

   $query="SELECT email FROM usuarios WHERE id_usuario='$id_usuario'";
   $stmt = $db->query($query);
   $row = $stmt->fetch(PDO::FETCH_ASSOC);
   $email_usuario_logueado=$row['email'];

   $query="SELECT email,id_evento FROM invitaciones WHERE key_invitacion='$key'";
//print "q=$query<br>";
   $stmt = $db->query($query);
   $row = $stmt->fetch(PDO::FETCH_ASSOC);
   $email_invitacion=$row['email'];
   $id_evento=$row['id_evento'];


   //si los emails coninciden proceder con las validaciones
   if ($email_usuario_logueado==$email_invitacion){        //validar si el usuario ya está registrado en este evento
        $query="SELECT id_usuario FROM usuariosxevento WHERE id_usuario='$id_usuario' AND id_evento='$id_evento'";
        $stmt = $db->query($query);
        if ($stmt->rowCount()==1){           	$msg="<span class=\"msg_error\">Ya está participando en este evento</span>";
        }else{        	//el usuario está logueado y aún no participa en el evento        	$invitacion=true;            include 'evento_ingreso.php';
        }

   }else{
      print "<span class=\"msg_error\">Hay otro usuario logueado en este momento</span>";
   }

}else{  //el usuario no está logueado
    //validar si hay usuario registrado con ese correo
    $query="SELECT id_usuario FROM usuarios WHERE email='$email'";
    $stmt = $db->query($query);
    if ($stmt->rowCount()==1){ //si hay un usuario registrado --> Pedirle que se loguee    	 print "<span class=\"msg_warn\">Por favor ingrese para poder tramitar su invitación</span>";
    	 $_SESSION['invitacion']=true;
    	 include 'login.php';
    }else{    	 print "<span class=\"msg_warn\">Por favor registrese para poder tramitar su invitación</span>";
    	 $_SESSION['invitacion']=true;
    	 include 'registro.php';
    }

}

?>
