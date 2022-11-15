<?php

session_start();
include 'includes/_Policy.php';
require 'includes/Open-Connection.php';


$id_usuario=$_POST['id_usuario'];
$usuario=$_POST['usuario'];
$nombre=$_POST['nombre'];
$es_admin=$_POST['admin'];
$email=$_POST['email'];
$recibir_correos=$_POST['recibir_correos'];
require_once 'audit.php';
audit_max();

($recibir_correos)? $recibir_correos=1 : $recibir_correos=0;


//auditoria para saber quien marco que un usuario pago
//averiguar si el usuario no había pagado y fue marcado como pagó
date_default_timezone_set('America/Bogota');
/*if ($pago==1){
   $query="SELECT * FROM usuarios WHERE id_usuario='$id_usuario' and pago='0'";
   print "query=$query<br>";
   $result = mysql_query($query) or die(mysql_error());
   if (mysql_num_rows($result)==1){
      $id_admin=$_SESSION['usuario_polla'];
      $hoy=date("Y-m-d G:i");   	  $query="INSERT INTO auditoria VALUES ('$id_admin','$id_usuario','$hoy','1')";
//   	  print "query=$query<br>";
   	  $result = mysql_query($query) or die(mysql_error());
   }
}else{  //ver si se desmarcó el pago de un usuario   $query="SELECT * FROM usuarios WHERE id_usuario='$id_usuario'";
   	  print "query=$query<br>";
   $result = mysql_query($query) or die(mysql_error());
   if (mysql_num_rows($result)==1){
      $id_admin=$_SESSION['usuario_polla'];
      $hoy=date("Y-m-d G:i");
   	  $query="INSERT INTO auditoria VALUES ('$id_admin','$id_usuario','$hoy','0')";
   	  print "query=$query<br>";
   	  $result = mysql_query($query) or die(mysql_error());
   }

}*/


$query="UPDATE usuarios SET usuario='$usuario',nombre='$nombre',email='$email',recibir_correos='$recibir_correos' WHERE id_usuario='$id_usuario'";
//print "query=$query<br>";
$stmt = $db->query($query);

//marca de adminsitrador
if ($es_admin){ //marcado como admin-->validar si ya está o sino incluirlo
   $query="SELECT * FROM administradores WHERE id_usuario='$id_usuario'";
   $stmt = $db->query($query);
   if ($stmt->rowCount()==0){
      $query="INSERT INTO administradores VALUES ('$id_usuario')";
      $db->query($query);
   }
}else{ //Marcado como no admin--> Validar si era admin para removerlo   $query="SELECT * FROM administradores WHERE id_usuario='$id_usuario'";
   $stmt = $db->query($query);
   if ($stmt->rowCount()>0){
      $query="DELETE FROM administradores WHERE id_usuario='$id_usuario'";
      $db->query($query);
   }


}

$_SESSION['msg']="<span class=\"msg_ok\">Usuario Modificado</span>";
require 'includes/Close-Connection.php';

if (!headers_sent() && $msg == '') {
    $redirect="index.php?accion=editar_usuario&id_usuario=$id_usuario";
      header('Location: '.$redirect);
}
?>
