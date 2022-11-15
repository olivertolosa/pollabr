<?

//envía un correo a cada usuario si es que tiene partidos sin apuesta en las siguientes 24 horas

include 'includes/Open-Connection.php';

$mostrar=$_GET['mostrar'];

date_default_timezone_set ('America/Bogota');


$nombre="Notificaciones ElGolGanador";
$from="recordatorios@elgolganador.com";
$subject="Partidos sin marcador registrado";

include 'function_correo.php';


//armar la lista de eventos activos
$query="SELECT id_evento FROM eventos WHERE activo='1'";
foreach($db->query($query) as $row) {
	 $eventos_activos.=$row['id_evento'].",";
}
$eventos_activos=substr($eventos_activos,0,strlen($eventos_activos)-1);

$equipos_array=array();

function ya_revisado($id_equipo){
   global $equipos_array;

	for ($i=0 ; $i<sizeof($equipos_array) ; $i++){
	   if ($equipos_array[$i]==$id_equipo){
	      return true;
	   }
	}
	return false;
}

require_once 'includes/class_equipo.php';
$equipoobj=new equipo($db);

//seleccionar todos los usuarios que tienen eventos activos
$query="SELECT DISTINCT u.id_usuario,u.email,u.nombre FROM usuariosxevento as uxe, usuarios as u
          WHERE uxe.id_evento IN ($eventos_activos)
			 AND uxe.id_usuario=u.id_usuario";
//print "q=$query<br>";
foreach($db->query($query) as $row) {
		$texto_mensaje="";
   //por cada usuario validar si tiene partidos sin apuesta
	$id_usuario=$row['id_usuario'];
	$email=$row['email'];
	$nombre_usuario=$row['nombre'];
	$nombre_usuario=substr($nombre_usuario,0,strpos($nombre_usuario," "));
	$nombre_usuario=ucfirst(strtolower($nombre_usuario));

	$query_partidos="SELECT id_partido,id_equipo1,id_equipo2,fecha,hora FROM partidos
	         WHERE id_evento IN (SELECT id_evento FROM usuariosxevento WHERE id_usuario='$id_usuario' AND id_evento IN ($eventos_activos))
				AND id_partido NOT IN (SELECT DISTINCT id_partido FROM apuestas WHERE id_usuario='$id_usuario')
				AND (fecha=CURDATE() OR (fecha>CURDATE() AND fecha<=NOW()+INTERVAL 1 DAY AND hora < CURTIME()))";
//print "q=$query_partidos<br><br>";
   $i=1;
   foreach($db->query($query_partidos) as $row_partidos) {
	   $id_partido=$row_partidos['id_partido'];
		$id_equipo1=$row_partidos['id_equipo1'];
		$id_equipo2=$row_partidos['id_equipo2'];
		$fecha=$row_partidos['fecha'];
		$hora=$row_partidos['hora'];
		$hora=substr($hora,0,5);

		($i % 2==0) ? $bg_color="#F2E9E9" : $bg_color="#FFFFFF";
		if ($texto_mensaje==""){
		   $texto_mensaje="$nombre_usuario <br><br><p>Los siguientes partidos cierran dentro de poco.<br><br>
			           No te quedes sin registrar tus marcadores</p>.<br><br>
			          <table style=\"border: 3px solid #F2E9E9;border-collapse:collapse;border-spacing: 7px;\">";
		}
		if (!ya_revisado($id_equipo1)){
				$equipos_array[$id_equipo1]=$equipoobj->get_nombre($id_equipo1);
		}
		if (!ya_revisado($id_equipo2)){
				$equipos_array[$id_equipo2]=$equipoobj->get_nombre($id_equipo2);
		}


		$texto_mensaje.="<tr style=\"border: 0px solid black;background-color: $bg_color\">
		                      <td style=\"padding: 7px;\">$i
									 <td style=\"padding: 7px;\"><img src=\"http://www.elgolganador.com/".$equipoobj->get_imagen($id_equipo1)."\" style=\"width:55px;height:55px;\" title=\"$equipos_array[$id_equipo1]\">
									 <td style=\"padding: 7px;\">$equipos_array[$id_equipo1]
		                      <td style=\"padding: 7px;\">  Vs
									 <td style=\"padding: 7px;\"><img src=\"http://www.elgolganador.com/".$equipoobj->get_imagen($id_equipo2)."\" style=\"width:55px;height:55px;\" title=\"$equipos_array[$id_equipo2]\">
									 <td style=\"padding: 7px;\">$equipos_array[$id_equipo2]
									 <td style=\"padding: 7px;\">$fecha-$hora";

		$i++;
	}
	if ($texto_mensaje!=""){
		$texto_mensaje.="</table>";
		if ($mostrar) print "<br><br>$texto_mensaje<br><br>";
		$respuesta=envio_correo($email,$nombre,$from,$subject,$texto_mensaje);
	}


}

include 'includes/Open-Connection.php';

?>