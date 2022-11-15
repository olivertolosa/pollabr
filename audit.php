<?php
function audit($id_usuario,$accion,$parametros){
   global $db;

   date_default_timezone_set ('America/Bogota');

   $fecha=date('Y-m-d');
   $hora=date('H:i');
   $minutos=substr($hora,strpos($hora,":")+1,2);
//   $hora=substr($hora,0,2);

//   $time=($hora*60)+$minutos;

   $archivo_log=fopen('logs/log_audit_'.$fecha.'.log','a+');

   //obtener el usuario
   if ($id_usuario==0){   	   $usuario="ElGolGanador";
   }else{
      require_once 'includes/class_usuario.php';
      $usr=new usuario($id_usuario);
      $usuario=$usr->usuario;
   }

   fwrite ($archivo_log, utf8_decode("$fecha-$hora - $usuario - $accion - $parametros\r\n"));
   fclose($archivo_log);

}

function audit_carga($accion){
   global $db;

   date_default_timezone_set ('America/Bogota');

   $fecha=date('Y-m-d');
   $hora=date('H:i');
   $minutos=substr($hora,strpos($hora,":")+1,2);
//   $hora=substr($hora,0,2);

//   $time=($hora*60)+$minutos;

   $archivo_log=fopen('logs/log_carga_'.$fecha.'.log','a+');

   fwrite ($archivo_log, utf8_decode("$fecha-$hora - $accion \r\n"));
   fclose($archivo_log);

}

function audit_max(){
   global $db;

  $ip=$_SERVER['REMOTE_ADDR'];

  if (isset($_REQUEST['password'])){     $_REQUEST['password']="xxxxxxxx";
  }

   $parametros=json_encode($_REQUEST);
   $id_usuario=$_SESSION['usuario_polla'];
   $pag=$_SERVER['PHP_SELF'];

   date_default_timezone_set ('America/Bogota');

   $fecha=date('Y-m-d');
   $hora=date('H:i:s');
   $minutos=substr($hora,strpos($hora,":")+1,2);
//   $hora=substr($hora,0,2);

//   $time=($hora*60)+$minutos;

   $archivo_log=fopen('logs/fullhd/log_audit_max_'.$fecha.'.log','a+');


   //obtener el usuario
   if ($id_usuario==0){
   	   $usuario="ElGolGanador";
   }else{      require_once 'includes/class_usuario.php';
      $usr=new usuario($id_usuario);
      $usuario=$usr->usuario;
   }

   $parametros= addslashes ($parametros);

   $query="INSERT INTO audit_full VALUES('$fecha','$hora','$id_usuario','$ip','$pag','$parametros')";
   $db->query($query);

   fwrite ($archivo_log, utf8_decode("$fecha-$hora - $usuario - $ip - $pag - $accion - $parametros\r\n"));
   fclose($archivo_log);

}

function audit_saldos($id_usuario,$saldo){
   global $db;

   date_default_timezone_set ('America/Bogota');

   $fecha=date('Y-m-d');
   $hora=date('H:i');
   $minutos=substr($hora,strpos($hora,":")+1,2);
//   $hora=substr($hora,0,2);

//   $time=($hora*60)+$minutos;

   $archivo_log=fopen('logs/log_saldosh_'.$fecha.'.log','a+');

   //obtener el usuario
   if ($id_usuario==0){
   	   fwrite ($archivo_log, utf8_decode("\r\n\r\n***********************************************************************\r\n"));
   }else{
      require_once 'includes/class_usuario.php';
      $usr=new usuario($db);
      $usuario=$usr->get_usuario($id_usuario);
      fwrite ($archivo_log, utf8_decode("$fecha-$hora - $usuario - $saldo\r\n"));
   }


   fclose($archivo_log);

}

?>
