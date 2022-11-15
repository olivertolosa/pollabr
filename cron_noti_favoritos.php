<?

//envía un correo a cada usuario si es que se han registrado apuestas directas de sus equipos favoritos

require_once 'includes/Open-Connection.php';

$mostrar=$_GET['mostrar'];

date_default_timezone_set ('America/Bogota');


$nombre="Notificaciones ElGolGanador";
$from="recordatorios@elgolganador.com";
$subject="Apuestas directas que te pueden interesar";

require_once 'function_correo.php';

require_once 'includes/class_equipo.php';
$equipoobj=new equipo($db);
print "<div style=\"width:600\">";


//seleccionar la fecha de la útima notificación
$query="SELECT valor FROM parametros WHERE parametro='ultima_apuestad_notificada'";
$stmt = $db->query($query);
$row=$stmt->fetch(PDO::FETCH_ASSOC);
$ultima_apuestad_notificada=$row['valor'];

print "última apuesta notificada:$ultima_apuestad_notificada<br>";


//seleccionar todos los usuarios que tienen equipos favoritos
$query="SELECT id_usuario,email,nombre FROM usuarios
          WHERE recibir_correos='1'
	      AND  id_usuario IN (SELECT DISTINCT id_usuario FROM equipos_favoritos)";
//print "q=$query<br>";
foreach($db->query($query) as $row){
		$texto_mensaje="";
   //por cada usuario validar si tiene partidos sin apuesta
	$id_usuario=$row['id_usuario'];
	$email=$row['email'];
	$nombre_usuario=$row['nombre'];
	$nombre_usuario=substr($nombre_usuario,0,strpos($nombre_usuario," "));
	$nombre_usuario=ucfirst(strtolower($nombre_usuario));

	//armar la lista de equipos favoritos
	$query_favoritos="SELECT id_equipo FROM equipos_favoritos WHERE id_usuario='$id_usuario'";
	$favoritos="";
	$i=1
	foreach($db->query($query_favoritos) as $row_favorito){	   $id_equipo=$row_favorito['id_equipo'];
	   if ($i==40){	   	  print "<br>";
	   	  $i=0;
	   	}

	   	$favoritos.=$id_equipo.",";
	}

	$favoritos=substr($favoritos,0,strlen($favoritos)-1);

	print "Revisando usuario $id_usuario - $nombre_usuario:<br>Favoritos:$favoritos<br>";

	//seleccionar las apuestas directas donde están los equipos favoritos del usuario

	$query_partidos="SELECT id_apuesta,id_equipo1,id_equipo2,fecha,hora FROM apuesta_directa
	         WHERE (id_equipo1 IN ($favoritos) or id_equipo2 IN ($favoritos))
	         AND pagado='0'
	         AND id_apuesta>$ultima_apuestad_notificada
	         AND id_apuesta NOT IN (SELECT id_apuesta FROM apuestasd_usuario WHERE id_usuario='$id_usuario')";
//print "q=$query_partidos<br><br>";

	$texto_mensaje="$nombre_usuario <br><br>Se han incluido apuestas directas de tus equipos favoritos que te pueden interesar:
			          <table style=\"border: 3px solid #F2E9E9;border-collapse:collapse;border-spacing: 7px;\">";


   $i=1;
   foreach($db->query($query_partidos) as $row_partidos) {
	   $id_apuesta=$row_partidos['id_apuesta'];
		$id_equipo1=$row_partidos['id_equipo1'];
		$id_equipo2=$row_partidos['id_equipo2'];
		$fecha=$row_partidos['fecha'];
		$hora=$row_partidos['hora'];
		$hora=substr($hora,0,5);


		($i % 2==0) ? $bg_color="#F2E9E9" : $bg_color="#FFFFFF";


		$texto_mensaje.="<tr style=\"border: 0px solid black;background-color: $bg_color\">
		                      <td style=\"padding: 7px;\"><img src=\"http://www.elgolganador.com/".$equipoobj->get_imagen($id_equipo1)."\" style=\"width:55px;height:55px;\" title=\"$equipos_array[$id_equipo1]\">
									 <td style=\"padding: 7px;\">$equipos_array[$id_equipo1]
		                      <td style=\"padding: 7px;\">  Vs
									 <td style=\"padding: 7px;\"><img src=\"http://www.elgolganador.com/".$equipoobj->get_imagen($id_equipo2)."\" style=\"width:55px;height:55px;\" title=\"$equipos_array[$id_equipo2]\">
									 <td style=\"padding: 7px;\">$equipos_array[$id_equipo2]
									 <td style=\"padding: 7px;\">$fecha-$hora";

		$i++;
	}
	if ($i>1){
		$texto_mensaje.="$i </table>";
			print "<br><br>mail :";
        	print "$texto_mensaje";
		    $respuesta=envio_correo($email,$nombre,$from,$subject,$texto_mensaje);
	}else{	   print "Nada que enviar<br><br>";
	}




}

//actualizar el id de la última notificación
$query="SELECT max(id_apuesta) as id_apuesta FROM apuesta_directa";
$stmt = $db->query($query);
$row=$stmt->fetch(PDO::FETCH_ASSOC);
$ultima_apuestad_notificada=$row['id_apuesta'];

$query="UPDATE parametros SET valor='$ultima_apuestad_notificada' WHERE parametro='ultima_apuestad_notificada'";
$stmt = $db->query($query);
//print "q=$query<br>";

print "</dvi>";

if (!isset($_SESSION['usuario_polla']))
   include 'includes/Close-Connection.php';

?>