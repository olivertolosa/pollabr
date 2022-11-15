<?
///validar que el usuario si haga parte del evento
include 'seguridad.php';

?>
<center>
<div class="table-responsive">
<table class="tabla_con_encabezado table table-condensed">
<?php
$mostrar_resultados=false;
if (isset($_GET['id_usuario'])){   $id_usuario=$_GET['id_usuario'];
   //validar que el usuario si esté participando en el evento
   $query="SELECT id_usuario FROM usuariosxevento WHERE id_evento=:id_evento AND id_usuario=:id_usuario";
   $stmt= $db->prepare($query,array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
   $stmt->bindParam(':id_usuario',$id_usuario);
   $stmt->bindParam(':id_evento',$id_evento);
   $stmt->execute();

   if ($stmt->rowCount()==0){   	   print "<span class=\"msg_error\">El usuario no está participando en el evento</span>";
   }else{
      require_once 'includes/class_usuario.php';
      $usuario=new usuario($id_usuario);
      $user=$usuario->get_usuario($id_usuario);
      print "<h3>Resultados para el usuario $user<h3>";
      $mostrar_resultados=true;
   }
}else{   $mostrar_resultados=true;
}

if ($mostrar_resultados){

	$id_evento=$_REQUEST['id_evento'];

	require_once 'includes/class_equipo.php';
   require_once 'includes/class_partido.php';
	require_once 'includes/class_evento.php';

	$eventobj=new evento($db);
   $eq=new equipo($db);
   $partidoobj=new partido($db);

   $grupo_tabla="";


   $puntos_totales=0;

   //obtener el número de rondas del evento
   $num_rondas=$eventobj->get_numrondas($id_evento);



   //validar si el evento es clon de otro
	$plantilla=$eventobj->tiene_plantilla($id_evento);


	//print "num_rondas=$num_rondas";

   $primer_salto=FALSE;  //flag para saber si hay que poner el salto entre las tablas


   for ($ronda=1 ; $ronda<=$num_rondas ; $ronda++){

   //  poner la marca de la ronda
      $nombre_ronda=$eventobj->get_nombre_ronda($id_evento,$ronda);


      if ($plantilla!=0){
         $id_event=$plantilla;
      }else{
         $id_event=$id_evento;
      }

      $query="SELECT * FROM partidos WHERE id_evento=:id_event AND ronda=:ronda AND goles1>=0 ORDER BY fecha ASC";
      $stmt= $db->prepare($query,array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	  $stmt->bindParam(':id_event',$id_event);
	  $stmt->bindParam(':ronda',$ronda);
	  $stmt->execute();

      if ($stmt->rowCount()>0){
			if (!$primer_salto)
            $primer_salto=TRUE;
         else
            print "<tr><td colspan=\"11\">&nbsp";
      print "<tr> <th colspan=\"11\" style=\"text-align: center;\"><strong>$nombre_ronda</strong>\n";
      print "<tr><th colspan=\"4\" style=\"text-align: center;\">Equipo1<th colspan=\"4\" style=\"text-align: center;\">Equipo2<th>Fecha<th>Hora<th>Puntos obtenidos\n";

   while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
		$id_partido=$row['id_partido'];
      $id_equipo1=$row['id_equipo1'];
      $id_equipo2=$row['id_equipo2'];
      $goles1=$row['goles1'];
      $goles2=$row['goles2'];
      $fecha=$row['fecha'];
      $hora=$row['hora'];
      $grupo=$row['grupo'];
      $hora=substr($hora,0,strlen($hora)-3);

   //print "grupo=$grupo";

   //poner la marca para separación de grupo
      if ($grupo!=$grupo_tabla){
        print "<tr><td colspan=\"11\">&nbsp";
        print "<tr> <td colspan=\"11\" style=\"text-align: center;\"><b>$grupo</b>\n";
        print "<tr><th colspan=\"4\" style=\"text-align: center;\">Equipo1<th colspan=\"4\" style=\"text-align: center;\">Equipo2<th>Fecha<th>Hora<th>Puntos obtenidos\n";

        $grupo_tabla=$grupo;
      }


      //averiguar los nombres de los equipos
      $nombre_equipo1=$eq->get_nombre($id_equipo1);
      $nombre_equipo2=$eq->get_nombre($id_equipo2);


		//traducir el id del partido si se está usando plantilla
      if ($plantilla!=0){
         $id_partido=$partidoobj->get_id_partido_clon_from_original($id_partido,$id_evento);
      }

		//validar si el usuario ya registro marcador
      $query_marcador="SELECT equipo1,equipo2,aleatorio FROM apuestas WHERE id_partido=:id_partido AND id_usuario=:id_usuario";
//print "<br>q=$query_marcador<br>params:<br>id_partido:$id_partido<br>id_usuario:$id_usuario<br>";
      $stmt_marcador= $db->prepare($query_marcador,array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	  $stmt_marcador->bindParam(':id_partido',$id_partido);
	  $stmt_marcador->bindParam(':id_usuario',$id_usuario);
	  $stmt_marcador->execute();

      $marcador1="-1";
      $marcador2="-1";
      if ($stmt_marcador->rowCount()>0){      	   $row=$stmt_marcador->fetch(PDO::FETCH_ASSOC);
      	   $marcador1=$row['equipo1'];
      	   $marcador2=$row['equipo2'];

      	   //print "<br>marcador1:$marcador1<br>marcador2:$marcador2<br>";
      	   $aleatorio=$row['aleatorio'];
      }

		($marcador1==-1) ? $marcador1_display="-" : $marcador1_display=$marcador1;
		($marcador2==-1) ? $marcador2_display="-" : $marcador2_display=$marcador2;

      //setear el fondo de la casilla para indicar si acertó o no
      $bg1="#F78181";
      $bg2="#F78181";
      if ($goles1==$marcador1){
          $bg1="#81F781";
      }
      if ($goles2==$marcador2){
          $bg2="#81F781";
      }

      print "<tr><td style=\"width: 150px;vertical-align:middle;";
      if ($aleatorio){
         print "background: url(imagenes/random.png);background-repeat:no-repeat;background-position:95% 50%;\" title=\"Marcador Aleatorio";
      }
      print "\";>$nombre_equipo1
              <td><div class=\"img_thumb\"><img src=\"".$eq->get_imagen($id_equipo1)."\" title=\"$nombre_equipo1\" height=\"55\" width=\"55\"></div>
              <td style=\"vertical-align:middle;\" title=\"Marcador del partido\" style=\"text-align: center;\">$goles1
              <td style=\"vertical-align:middle; text-align: center;background-color: $bg1\" width=\"20\" title=\"Mi Marcador\" >$marcador1_display
              <td><div class=\"img_thumb\"><img src=\"".$eq->get_imagen($id_equipo2)."\" title=\"$nombre_equipo2\" height=\"55\" width=\"55\"></div>
              <td style=\"vertical-align:middle;\">$nombre_equipo2
              <td style=\"vertical-align:middle;\" title=\"Marcador del partido\" style=\"text-align: center;\">$goles2
              <td style=\"vertical-align:middle; text-align: center;background-color: $bg2\" title=\"Mi Marcador\" style=\"text-align: center;background-color: $bg2\">$marcador2_display
              <td style=\"vertical-align:middle;\">$fecha
              <td style=\"vertical-align:middle;\">$hora\n";
      //calcular los puntos obtenidos
      $puntos=0;
      if ($marcador1!=-1){
   	     //validar ganador
    	  if ($goles1>$goles2){
   	    	   if ($marcador1>$marcador2)
   	    	      $puntos+=5;
      	  }else if ($goles1<$goles2){
     	  	   if ($marcador1<$marcador2)
   	  	      $puntos+=5;
    	  }else{
    	  	   if ($marcador1==$marcador2)
    	  	      $puntos+=5;
    	  }

      	  //validar marcadores
     	  if ($goles1==$marcador1)
    	     $puntos+=5;
    	  if ($goles2==$marcador2)
    	     $puntos+=5;

      }
      $puntos_totales+=$puntos;
      print "<td style=\"vertical-align:middle;text-align: center;\">$puntos\n";

      }
   }
   //print "</table><br><br>";
   }

   print "<tr><td colspan=\"10\">Puntos totales<td colspan=\"2\" style=\"text-align: center;\">$puntos_totales\n";
}
?>
</table>
</div>

</center>



