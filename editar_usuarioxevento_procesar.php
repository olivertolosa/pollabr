<?php

session_start();
include 'includes/_Policy.php';
require 'includes/Open-Connection.php';
require 'includes/class_usuario.php';
require 'includes/class_evento.php';
require_once 'audit.php';
require_once 'function_movimiento_plata.php';
date_default_timezone_set('America/Bogota');

audit_max();


$id_usuario=$_POST['id_usuario'];
$validado=$_POST['validado'];
($validado)? $validado=1 : $validado=0;
$id_evento=$_POST['id_evento'];

$usrobj=new usuario($id_usuario);
$eventoobj=new evento($db);



$valor=$eventoobj->get_valor($id_evento);

$saldo=$usrobj->get_saldo($id_usuario);

if ($saldo<$valor and $validado==1){   $_SESSION['msg']="<span class=\"msg_error\">Saldo insuficiente</span>";
}else{
   //verificar si se está quitando marca de validado
   $query="SELECT validado FROM usuariosxevento WHERE id_evento='$id_evento' AND id_usuario='$id_usuario'";
   $stmt=$db->query($query);
   $row=$stmt->fetch(PDO::FETCH_ASSOC);
   $validado_ori=$row['validado'];

   if ($validado_ori==1 AND $validado==0){
       audit ($_SESSION['usuario_polla'], "eliminar validado a usuario","usuario:$id_usuario, valor=$validado $query ".$_SERVER['REMOTE_ADDR']." ".$_SERVER['HTTP_X_FORWARDED_FOR']);

	   //actualizar saldo
	    $saldo=$saldo+$valor;
		$query="UPDATE usuarios SET saldo='$saldo' WHERE id_usuario='$id_usuario'";
		$stmt=$db->query($query);
		movimiento_plata($id_usuario,$valor,"+","Retiro en polla $id_evento...se reembolsa inscripción");
		movimiento_plata(0,$valor,"-","Retiro y reembolso de usuario $id_usuario en polla $id_evento");

		$query="UPDATE usuariosxevento SET validado='0' WHERE id_usuario='$id_usuario' and id_evento='$id_evento'";
		//print "query=$query<br>";
		$db->query($query);


		$_SESSION['msg']="<span class=\"msg_ok\">Usuario Modificado</span>";
   }else if ($validado==1){
	   audit ($_SESSION['usuario_polla'], "Cambiar validado a usuario","usuario:$id_usuario, valor=$validado".$_SERVER['REMOTE_ADDR']." ".$_SERVER['HTTP_X_FORWARDED_FOR']);


	   //actualizar saldo
	    $saldo=$saldo-$valor;
		$query="UPDATE usuarios SET saldo='$saldo' WHERE id_usuario='$id_usuario'";
		$stmt=$db->query($query);
		movimiento_plata($id_usuario,$valor,"-","Registro en polla $id_evento");
		movimiento_plata(0,$valor,"+","Registro de usuario $id_usuario en polla $id_evento");

		$query="UPDATE usuariosxevento SET validado='1' WHERE id_usuario='$id_usuario' and id_evento='$id_evento'";
		//print "query=$query<br>";
		$db->query($query);


		$_SESSION['msg']="<span class=\"msg_ok\">Usuario Modificado</span>";
	}else{		$_SESSION['msg']="<span class=\"msg_warn\">No se realizaron acciones</span>";
	}

}


require 'includes/Close-Connection.php';

if (!headers_sent() && $msg == '') {
    $redirect="index.php?accion=evento_admin&id_evento=$id_evento&accion2=editar_usuario&id_usuario=$id_usuario";
      header('Location: '.$redirect);
}
?>
