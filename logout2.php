<?
session_start();

require_once 'includes/Open-Connection.php';
require 'audit.php';
$ip=$_SERVER['REMOTE_ADDR'];
audit ($_SESSION['usuario_polla'],"Logout v2","ip: $ip");
//require_once 'includes/Close-Connection.php';


//print "***************saliendo****************";

unset($_SESSION['usuario_polla']);
unset($_SESSION['admin']);
unset($_SESSION['administra_polla']);
unset($_SESSION['cambia_clave']);
unset($_SESSION['msg']);
unset($_SESSION['id_album']);


//print "voy a redirigir";
if (!headers_sent()) {
   $redirect="index.php";
   header('Location: '.$redirect);
}else{   print "no pude redirigir";
}

?>
