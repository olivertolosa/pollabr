<?php
session_start();
include 'includes/_Policy.php';

require 'includes/Open-Connection.php';
require_once 'function_movimiento_plata.php';
include 'audit.php';
audit_max();

$id_usuario_admin=$_SESSION['usuario_polla'];

$id_usuario=$_REQUEST['id_usuario'];

//validar si es admin
$es_admin=false;
$query="SELECT * FROM administradores WHERE id_usuario='$id_usuario_admin'";
$stmt = $db->query($query);
if ($stmt->rowCount()>0){
   $es_admin=true;
}

if (!$es_admin){
  $redirect="index.php";
  $_SESSION['msg']="<span class=\"msg_eror\">Operaci&oacute;n no autorizada</span>";
}else{  $monto=$_POST['monto'];
  $operacion=$_POST['operacion'];
  $descripcion=$_POST['descripcion'];

  if ($operacion=="Acreditar"){     $query="UPDATE usuarios SET saldo=saldo+$monto WHERE id_usuario='$id_usuario'";
//     print "q=$query<br>";
     $stmt = $db->query($query);
     movimiento_plata($id_usuario,$monto,"+","Admin acredita cuenta: $descripcion",1);
     $_SESSION['msg']="<span class=\"msg_ok\">Operaci&oacute;n realizada</span>";
  }else if ($operacion=="Debitar"){  	 //validar el saldo antes de debitar
  	 $query="SELECT saldo FROM usuarios WHERE id_usuario='$id_usuario'";
  	 $stmt = $db->query($query);
  	 $row=$stmt->fetch(PDO::FETCH_ASSOC);
  	 $saldo=$row['saldo'];

  	 if ($monto>$saldo)
  	     $_SESSION['msg']="<span class=\"msg_error\">Saldo insuficiente</span>";
  	 else{  	    $query="UPDATE usuarios SET saldo=saldo-$monto WHERE id_usuario='$id_usuario'";
  	    $stmt = $db->query($query);
  	    movimiento_plata($id_usuario,$monto,"-","Admin debita cuenta: $descripcion",1);
  	    $_SESSION['msg']="<span class=\"msg_ok\">Operaci&oacute;n realizada</span>";
  	 }
  }

  //print "<br>q=$query<br>";

  $redirect="index.php?accion=editar_usuario&id_usuario=$id_usuario";



}

include 'includes/Close-Connection.php';


if (!headers_sent() && $msg == '') {
      header('Location: '.$redirect);
}

?>

