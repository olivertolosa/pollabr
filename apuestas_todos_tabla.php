<?php

//if (!isset($id_partido)){
   session_start();
   require_once 'includes/Open-Connection.php';

   $id_usuario=$_SESSION['usuario_polla'];

   $id_partido=$_GET['id_partido'];
   require_once 'includes/class_partido.php';
   require_once 'includes/class_equipo.php';

   $eq=new equipo($db);
   $partidoobj=new partido($db);

   $callajax=1;
   $plantilla=$_GET['plantilla'];
   $id_evento=$_GET['id_evento'];

//}



//traducir el id del partido si se está usando plantilla
if ($plantilla!=0){
      $id_part=$partidoobj->get_id_partido_original_from_clon($id_partido);
}else{
    $id_part=$id_partido;
}

$query="SELECT * FROM partidos WHERE id_partido='$id_part'";
//print "<br>query_datos_partido=$query<br>";
$stmt=$db->query($query);
$row=$stmt->fetch(PDO::FETCH_ASSOC);

//$id_partido=$row['id_partido'];
$id_equipo1=$row['id_equipo1'];
$id_equipo2=$row['id_equipo2'];
$goles1=$row['goles1'];
$goles2=$row['goles2'];
$fecha=$row['fecha'];
$hora=$row['hora'];
$grupo=$row['grupo'];

if ($goles1==-1) $goles1="-";
if ($goles2==-1) $goles2="-";



print "<table id=\"tabla$id_partido\" style=\"display: block; \" class=\"tabla_con_encabezado\">\n";
print "<tr><th>#<th>Usuario<th>".$eq->get_nombre($id_equipo1)."<th>".$eq->get_nombre($id_equipo2)."<th>Ptos\n";
print "<tr><td>&nbsp;<td>RESULTADO<td style=\"text-align: center;\">$goles1<td style=\"text-align: center;\">$goles2<td>\n";


//validar si el usuario ya registro marcador
$query_marcador="SELECT u.usuario,u.id_usuario,a.equipo1,a.equipo2,a.aleatorio FROM apuestas as a, usuarios as u
                    WHERE a.id_partido='$id_partido'
                    AND u.id_usuario=a.id_usuario
                    ORDER BY u.usuario ASC";
//   print "<br>q=$query_marcador<br>";
$stmt_marcador = $db->query($query_marcador);
$i=1;
while ($row_marcador=$stmt_marcador->fetch(PDO::FETCH_ASSOC)){
         $nombre_usuario=$row_marcador['usuario'];
         $marcador1=$row_marcador['equipo1'];
         $marcador2=$row_marcador['equipo2'];
         $id_usuario=$row_marcador['id_usuario'];
         $aleatorio=$row_marcador['aleatorio'];

        //verificar si el usuario está validado (si aplica)
        $mostrar_apuesta_usuario=TRUE;
        $query_validado="SELECT conf_usuarios FROM eventos where id_evento='$id_evento'";
        $stmt_validado=$db->query($query_validado);
        $row_validado=$stmt_validado->fetch(PDO::FETCH_ASSOC);

        if ($row_validado['conf_usuarios']==1){
         	  mysql_free_result($result_validado);
         	  $query_validado="SELECT id_usuario FROM usuariosxevento WHERE id_evento='$id_evento' and id_usuario='$id_usuario' and validado='1'";
//        	  print "q=$query_validado";
        	  $stmt_validado=$db->query($query_validado);
              if ($stmt_validado->rowCount()==0){
                  $mostrar_apuesta_usuario=FALSE;
              }

         }

         if ($mostrar_apuesta_usuario){


             //setear el fondo de la casilla para indicar si acertó o no
             $bg1="#F78181";
             $bg2="#F78181";
             if ($goles1==$marcador1)
                $bg1="#81F781";
             if ($goles2==$marcador2)
                $bg2="#81F781";

            //calcular los puntos obtenidos
           $puntos=0;
            if ($goles1!="-"){
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
            $class="";
            if ($id_usuario==$_SESSION['usuario_polla']) $class="fila-usuario";
            print "<tr class=\"$class\"><td>$i<td style=\"width: 150px;";
            if ($aleatorio){
               print "background: url(imagenes/random.png);background-repeat:no-repeat;background-position:98% 50%;\" title=\"Marcador Aleatorio";
            }

            print "\">$nombre_usuario
                 <td width=\"20\" bgcolor=\"$bg1\" style=\"text-align: center;\">$marcador1
                 <td width=\"20\" bgcolor=\"$bg2\" style=\"text-align: center;\">$marcador2
                 <td style=\"text-align: center;\">$puntos\n";

            $i++;
         }
}
   print "</table>";

if ($callajax)
   require 'includes/Close-Connection.php';
?>
