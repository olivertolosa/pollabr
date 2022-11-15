<?php

session_start();
include 'includes/_Policy.php';
require_once 'audit.php';
audit_max();
//print_r($_POST);
//print_r($_SESSION);

$id_usuario=$_SESSION['usuario_polla'];

include 'includes/Open-Connection.php';

$tipo_voto=$_POST['tipo_voto'];

if ($tipo_voto=="select"){   $voto=$_POST['voto'];
   $query="INSERT INTO encuesta_votos values('$id_usuario','$voto')";
//   print "poner un voto por :$voto";

}else if ($tipo_voto=="texto"){   $voto=$_POST['propuesta'];
   $query="INSERT INTO encuesta_nombres VALUES('$voto')";
   $result = mysql_query($query) or die(mysql_error());
   $query="INSERT INTO encuesta_votos values('$id_usuario','$voto')";
  // print "poner como alternativa $voto y sumarle un voto";
}
  $result = mysql_query($query) or die(mysql_error());


   include 'includes/Close-Connection.php';
   $redirect="index.php";

   $_SESSION['msg']="Gracias por su voto!!!";

if (!headers_sent() && $msg == '') {
      header('Location: '.$redirect);
}

?>
