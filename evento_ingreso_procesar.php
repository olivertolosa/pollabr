<?php

session_start();
include 'includes/_Policy.php';
require 'includes/Open-Connection.php';
require_once 'audit.php';
audit_max();


$id_evento=$_POST['id_evento'];
$id_usuario=$_SESSION['usuario_polla'];
$confirmado=$_POST['confirmado'];

//print_r($_POST);

if ($confirmado!=1){
   $_SESSION['msg']="<span class=\"msg_error\">No fue posible el registro</span>";
   $redirect="index.php?accion=ingreso_evento&id_evento=$id_evento";
}else{
   //validar si el evento es publico o si el usuario tiene invitación
   $query="SELECT publica FROM eventos WHERE id_evento=:id_evento";
   $stmt= $db->prepare($query);
   $stmt->bindParam(':id_evento',$id_evento);
   $stmt->execute();
   $row = $stmt->fetch(PDO::FETCH_ASSOC);
   $publica=$row['publica'];
   if ($publica){  //el evento es publico...se puede ingresar libremente      //validar si no estaba registrado en este evento
      $query="SELECT * FROM usuariosxevento WHERE id_evento=:id_evento AND id_usuario=:id_usuario";
//print "q=$query<br>";
      $stmt= $db->prepare($query);
   	  $stmt->bindParam(':id_evento',$id_evento);
   	  $stmt->bindParam(':id_usuario',$id_usuario);
      $stmt->execute();
      if ($stmt->rowCount()>0){         $_SESSION['msg']="<span class=\"msg_error\">Ya se encuentra registrado en este evento</span>";
      }else{         $query="INSERT INTO usuariosxevento VALUES(:id_usuario,:id_evento,'0','0','0','0','0')";
         $stmt= $db->prepare($query);
   	  	 $stmt->bindParam(':id_evento',$id_evento);
   	  	 $stmt->bindParam(':id_usuario',$id_usuario);
      	 $stmt->execute();
         $_SESSION['msg']="<span class=\"msg_ok\">Registro exitoso</span>";

         //validar si hay invitación pendiente y eliminarla
         $query="DELETE FROM invitaciones WHERE id_evento=:id_evento AND
              email=(SELECT email FROM usuarios WHERE id_usuario=:id_usuario)";
//print "query=$query<br>";
		 $stmt= $db->prepare($query);
   	  	 $stmt->bindParam(':id_evento',$id_evento);
   	  	 $stmt->bindParam(':id_usuario',$id_usuario);
      	 $stmt->execute();
      }
   }else{   	   //validar la invitación
			$query="select email FROM usuarios WHERE id_usuario=:id_usuario";
			$stmt= $db->prepare($query);
   	  	 	$stmt->bindParam(':id_usuario',$id_usuario);
      	 	$stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
			$email=$row['email'];

			$query="SELECT * FROM invitaciones WHERE email=:email";
			$stmt= $db->prepare($query,array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
   	  	 	$stmt->bindParam(':email',$email);
      	 	$stmt->execute();
			if ($stmt->rowCount()>=1){  //si tiene invitación
			   $query="INSERT INTO usuariosxevento VALUES(:id_usuario,:id_evento,'0','0','0','0','0')";
      			$stmt= $db->prepare($query);
   	  	 		$stmt->bindParam(':id_evento',$id_evento);
   	  	 		$stmt->bindParam(':id_usuario',$id_usuario);
      	 		$stmt->execute();

               $_SESSION['msg']="<span class=\"msg_ok\">Registro exitoso</span>";

            //validar si hay invitación pendiente y eliminarla
               $query="DELETE FROM invitaciones WHERE id_evento=:id_evento AND
                   email=(SELECT email FROM usuarios WHERE id_usuario=:id_usuario)";
			   $stmt= $db->prepare($query);
   	  	 		$stmt->bindParam(':id_evento',$id_evento);
   	  	 		$stmt->bindParam(':id_usuario',$id_usuario);
      	 		$stmt->execute();
			   //print "q=$query";
			}else{
				$_SESSION['msg']="<span class=\"msg_error\">No se encontró una invitación para este evento</span>";
			}
   }
//print_r ($_SESSION);

   $redirect="index.php?accion=ingreso_evento&id_evento=$id_evento";
}


if (!headers_sent()) {
     header('Location: '.$redirect);
}
?>
