<script>

setTimeout(function(){
   window.location.reload(1);
},900000);

</script>

<?

require_once 'includes/Open-Connection.php';
require_once 'function_movimiento_plata.php';

date_default_timezone_set ('America/Bogota');

//deshabilitar partidos según la fecha
//seleccionar partido q está por empezar
$fecha=date('Y-m-d');
$hora=date('H:i');
$minutos=substr($hora,strpos($hora,":")+1,2);
$hora=substr($hora,0,2);

$time=($hora*60)+$minutos;

print "<br><br>ejecución en $fecha - $hora:$minutos<br>";

$archivo_log=fopen('logs/log_cron_deshabilitar_'.$fecha.'.log','a+');
fwrite ($archivo_log,"***********************************************\r\n");
fwrite ($archivo_log," ejecución en $fecha - $hora:$minutos\r\n");




$query="SELECT id_partido,hora,id_evento,tipo_e,id_equipo1,id_equipo2 FROM partidos WHERE fecha='$fecha' and editable='1'";
//print "<br>q=$query<br><br>";
$stmt = $db->query($query);

if ($stmt->rowCount()==0){	print "No hay partidos para bloquear<br>";
	fwrite ($archivo_log, "No hay partidos para bloquear\r\n");
}else{	print "Si hay partidos para revisar!!!!<br>";
	fwrite ($archivo_log, "Si hay partidos para revisar!!!!\r\n");
}

while ($row=$stmt->fetch(PDO::FETCH_ASSOC)){
   $id_partido=$row['id_partido'];
   $hora_partido=$row['hora'];
   $id_evento=$row['id_evento'];
   $tipo_e=$row['tipo_e'];

   print "Revisando partido $id_partido que inicia a las $hora_partido...tipo_e:$tipo_e<br>";
   fwrite ($archivo_log, "Revisando partido $id_partido que inicia a las $hora_partido...tipo_e:$tipo_e\r\n");

   $bloquear=false;


   //validar si el partido está programada para iniciar dentro de los próximos 15 mins
   $minutos_partido=substr($hora_partido,strpos($hora_partido,":")+1,2);
   $hora_partido=substr($hora_partido,0,2);

   $time_partido=($hora_partido*60)+$minutos_partido;

   if ($hora==$hora_partido){   	      print "Partido en la misma hora<br>";
         fwrite ($archivo_log, "Partido en la misma hora\r\n");
      if (($minutos+15)>= $minutos_partido){
			$bloquear=true;
      }


   }else if (($hora+1)==$hora_partido){
		print "Partido en la hora siguiente<br>";
		fwrite ($archivo_log, "Partido en la hora siguiente\r\n");
		if (($minutos+15-60)>= $minutos_partido){
			$bloquear=true;
      }

   }

   if ($bloquear){
	$id_evento=$row['id_evento'];
   	$query_u="UPDATE partidos SET editable='0' WHERE id_partido='$id_partido'";
	$result_u = $db->query($query_u);


	//armar lista de posibles partidos de duelo
	$partidos_duelos[]='p-'.$id_partido;

	include 'set_aleatorio.php';

	print "<br>Lock al partido $id_partido<br>";
	fwrite ($archivo_log,"<br>Lock al partido $id_partido\r\n");

	//marcar el partido como iniciado en la BD
	$query_j="INSERT INTO partidos_iniciados VALUES('$id_partido')";
	$result_j = $db->query($query_j);

    //si es un partido de bolsa eliminar las puntas
    if ($tipo_e=='b'){    	$id_eq1=$row['id_equipo1'];
    	$id_eq2=$row['id_equipo2'];        $query_p="DELETE FROM bolsa_puntas WHERE id_equipo='$id_eq1' AND id_bolsa='$id_evento'";
         fwrite ($archivo_log,"<br>q:$query_p\r\n");
        $result_p = $db->query($query_p);
        $query_p="DELETE FROM bolsa_puntas WHERE id_equipo='$id_eq2' AND id_bolsa='$id_evento'";
        print "<br>q:$query_p<br>";
        fwrite ($archivo_log,"<br>q:$query_p\r\n");
        $result_p = $db->query($query_p);
        print "<br>Puntas Eliminadas para equipos $id_eq1 y $id_eq2<br>";
        fwrite ($archivo_log,"<br>Puntas Eliminadas para equipos $id_eq1 y $id_eq2\r\n");
    }else{    	print "<br>no borré puntas de equipos $id_eq1 y $id_eq2<br>";       	fwrite ($archivo_log,"<br>no borré puntas de equipos $id_eq1 y $id_eq2\r\n");
    }

   }

   print "<br>id_partido=$id_partido <br>time=$time ---  time_partido=$time_partido<br>";
   //verificar si el partido inicia entre 120 y 135 minutos para notificar al usuario si no ha hecho apuesta
   if (($time+120)<$time_partido and ($time+135)>$time_partido and $tipo_e=='e'){
      $query_noti="SELECT email FROM usuarios
                 WHERE id_usuario IN (SELECT id_usuario FROM usuariosxevento WHERE id_evento='$id_evento')
                 AND id_usuario NOT IN (SELECT id_usuario FROM apuestas WHERE id_partido='$id_partido')";
      foreach($db->query($query_noti) as $row_noti) {
			  $correo=$row_noti['email'];
      	  if ($correo!="")
      	     if (!strstr($emails,$correo))
      	        $emails.=$correo.",";
      }

   }
}

if (strlen($emails)>0){
   $emails=substr($emails,0,strlen($emails)-1);
   print "enviar correo a $emails<br>";
   $emails_array=split(",",$emails);
   include 'function_correo.php';
  foreach($emails_array as $email){
      $nombre="Notificaciones de ElGolGanador";
      $from="recordatorios@elgolganador.com";
      $subject="Partidos sin marcador registrado";

      $mensaje="Estimado usuario<br><br>Hay partidos por cerrar en los que usted aún no ha registrado su marcador.
      <br><br>Por favor ingrese a <a href=\"www.elgolganador.com\">ElGolGanador</a> y registre sus marcadores, de lo contrario se le asignará un marcador aleatorio
      <br><br>La polla.";

      $respuesta=envio_correo($email,$nombre,$from,$subject,$mensaje);
//   print "resp=$respuesta*****<br>";
      ($respuesta)? print "envio ok" : print "envio paila";

      fwrite ($archivo_log,"Notificación enviada a *$email*\n");
   }
}

//deshabilitar las apuestasd
$query="SELECT id_apuesta,fecha,hora FROM apuesta_directa WHERE fecha='$fecha' and editable='1'";

//print "q=$query<br>";
$stmt = $db->query($query);

if ($stmt->rowCount()==0){	print "No hay apuestas directas para bloquear<br>";
	fwrite ($archivo_log, "No hay apuestas directas para bloquear\r\n");
}else{	print "Si hay apuestas directas para revisar!!!!<br>";
	fwrite ($archivo_log, "Si hay apuestas directas para revisar!!!!\r\n");
}

while ($row=$stmt->fetch(PDO::FETCH_ASSOC)){
   $id_apuesta=$row['id_apuesta'];
   $hora_partido=$row['hora'];

   print "Revisando apuesta directa $id_apuesta que inicia a las $hora_partido...<br>";
   fwrite ($archivo_log, "Revisando apuesta directa $id_apuesta que inicia a las $hora_partido...\r\n");

   $bloquear=false;

   //validar si el partido está programada para iniciar dentro de los próximos 15 mins
   $minutos_partido=substr($hora_partido,strpos($hora_partido,":")+1,2);
   $hora_partido=substr($hora_partido,0,2);

   $time_partido=($hora_partido*60)+$minutos_partido;

   if ($hora==$hora_partido){   	     print "Partido en la misma hora<br>";
         fwrite ($archivo_log, "Partido en la misma hora\r\n");
      if (($minutos+15)>= $minutos_partido){
			$bloquear=true;
      }
   }else if (($hora+1)==$hora_partido){
        print "Partido en la hora siguiente<br>";
		fwrite ($archivo_log, "Partido en la hora siguiente\r\n");
		if (($minutos+15-60)>= $minutos_partido){
			$bloquear=true;
      }

   }

   if ($bloquear){
	 $query_u="UPDATE apuesta_directa SET editable='0' WHERE id_apuesta='$id_apuesta'";
	 $result_u = $db->query($query_u);
	 print "Bloqueada apuesta directa $id_apuesta.<br>";
	 fwrite ($archivo_log, "Bloqueada apuesta directa $id_apuesta.\r\n");

	 //armar lista de posibles partidos de duelo
	 $partidos_duelos[]='d-'.$id_apuesta;
   }
}

//eliminar duelos no aceptados
print"<br><br> revisando duelos para:<br>";
print_r($partidos_duelos);
foreach($partidos_duelos as $id_partido){

     $query="SELECT * FROM duelos WHERE id_partido='$id_partido'";
print "q=$query<br>";
     $stmt = $db->query($query);
     while ($row=$stmt->fetch(PDO::FETCH_ASSOC)){     	$id_duelo=$row['id_duelo'];
        $id_partido=$row['id_partido'];
        $id_usuario1=$row['id_usuario1'];
        $id_usuario2=$row['id_usuario2'];
        $ap1=$row['ap1'];
        $ap2=$row['ap2'];
        $declinar=$row['declinar'];
        $monto=$row['monto'];


	    //determinar el estado del duelo
		   $estado='';
	       if ($declinar==1){
	       	  $estado="Solicitando declinar";
	       	  $est=3;
	       }else if ($id_usuario1==$id_usuario AND $id_usuario2==0){
	          $estado="Invitación Abierta";
	          $est=0;
	       }else if($id_usuario1==$id_usuario AND $id_usuario2!=0 AND $ap2==''){
	       	  $estado="Por aceptar";
	       	  $est=1;
	       }else if ($ap1!='' AND $ap2!=''){
	       	  $estado="Aceptado";
	       	  $est=2;
	       }
	    print "<br>estado=$estado<br>";

	    if ($estado==0 or $estado==1){	       $query_delete="UPDATE duelos SET estado='8' WHERE id_duelo='$id_duelo'";
	       $stmt_delete = $db->query($query_delete);
	       print "eliminando duelo $id_duelo<br>";
	       fwrite ($archivo_log, "Eliminando duelo $id_duelo creado por $id_usuario1\r\n");

	       //devolver la plata al jugador 1
	       $query_devolver="UPDATE usuarios SET saldo=saldo+$monto WHERE id_usuario='$id_usuario1'";
	       $stmt_devolver = $db->query($query_devolver);
	       movimiento_plata($id_usuario1,$monto,"+","Duelo Eliminado por falta de contrincante: $id_duelo");
	    }


     }

}


fwrite ($archivo_log,"**************************************\n");
fclose($archivo_log);

if (!isset($_SESSION['usuario_polla']))
   include 'includes/Close-Connection.php'


?>
