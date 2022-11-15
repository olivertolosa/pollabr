<?php

session_start();
//include 'includes/_Policy.php';
require 'includes/Open-Connection.php';
require_once 'audit.php';
audit_max();

include 'includes/class_liga.php';
$liga=new liga($db);

$id_liga=$_POST['id_liga'];

$url=$liga->get_linkLS($id_liga);
include 'carga_equipos_ls.php';
$equipos_ls=carga_equipos_ls($url,FALSE);
$num_equipos_cargados=sizeof($equipos_ls);

//print "num_cargados=$num_equipos_cargados<br>";
$cargados=0;
for ($i=1 ; $i<=$num_equipos_cargados ; $i++){   //validar si el equipo ya existe en la bd
   $query="SELECT equipoLS,equipoLS2,equipoLS3 FROM equipos WHERE equipoLS='$equipos_ls[$i]' or equipoLS2='$equipos_ls[$i]' or equipoLS3='$equipos_ls[$i]'";
   $stmt = $db->query($query);
   if ($stmt->rowCount()==0){	//si el equipo no está en la bd...insertarlo
      $query="INSERT INTO equipos values('','$equipos_ls[$i]','$equipos_ls[$i]','','','$id_liga')";
//      print "query=$query<br>";
      $stmt = $db->query($query);
      $cargados++;
   }else{//   	   print "equipo $equipos_ls[$i] ya está en la BD<br>";
   }
}


$_SESSION['msg']="<span class=\"msg_ok\">$cargados equipos cargados</span>";
require 'includes/Close-Connection.php';

$redirect="index.php?accion=grupo-equipos_detalle&id_grupo=$id_liga";

if (!headers_sent() && $msg == '') {
     header('Location: '.$redirect);
}
?>
