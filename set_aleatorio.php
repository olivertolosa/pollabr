<?

//debe estar cargado el id del partido y el id del evento en las variables $id_partido y $id_evento respectivamente

//obtener el valor tope para marcadores aleatorios
   $query_max="SELECT max_aleatorio FROM eventos WHERE id_evento='$id_evento'";
//print "<br>q=$query_max<br>";
   $stmt_max = $db->query($query_max);
   $row_max = $stmt_max->fetch(PDO::FETCH_ASSOC);
   $max_aleatorio=$row_max['max_aleatorio'];

//poner marcadores aleatorios para los q no apostaron
   $query_usuarios="SELECT id_usuario FROM usuarios WHERE id_usuario IN (SELECT id_usuario FROM usuariosxevento WHERE id_evento='$id_evento')";
//print "q=$query_usuarios<br>";
   foreach($db->query($query_usuarios) as $row_usuarios) {
   	  $id_usuario=$row_usuarios['id_usuario'];
      $query_apuesta="SELECT * FROM apuestas WHERE id_usuario='$id_usuario' AND id_partido='$id_partido'";
//print "q=$query_apuesta<br>";
      $stmt_apuesta = $db->query($query_apuesta);
      if ($stmt_apuesta->rowCount()==0){
         $hoy=date('Y-m-d H:i');
         $marcador1=rand(0,$max_aleatorio);
         $marcador2=rand(0,$max_aleatorio);
   	     $query_update="INSERT INTO apuestas VALUES('$id_usuario','$id_partido','$marcador1','$marcador2','$hoy','1')";
//   	     print "q=$query_update<br>";
   	     $db->query($query_update);
      }
   }
?>