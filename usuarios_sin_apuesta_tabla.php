<?php
//if (!isset($id_partido)){
   session_start();

   $id_usuario=$_SESSION['usuario_polla'];
   require_once 'includes/Open-Connection.php';



   $id_partido=$_GET['id_partido'];
   require_once 'includes/class_partido.php';
   require_once 'includes/class_equipo.php';
	require_once 'includes/class_usuario.php';

   $equipoobj=new equipo($db);
   $partidoobj=new partido($db);


   $callajax=1;
   $plantilla=$_GET['plantilla'];
   $id_evento=$_GET['id_evento'];

//}
?>
<link rel="stylesheet" type="text/css" href="css/polla.css" />
<?


//traducir el id del partido si se está usando plantilla
if ($plantilla!=0){
      $id_part=$partidoobj->get_id_partido_original_from_clon($id_partido);
}else{
    $id_part=$id_partido;
}

$query="SELECT * FROM partidos WHERE id_partido=:id_part";
//print "<br>query_datos_partido=$query<br>";
$stmt= $db->prepare($query,array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$stmt->bindParam(':id_part',$id_part);
$stmt->execute();;
$num=$stmt->rowCount();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
   $id_partido=$row['id_partido'];
   $id_equipo1=$row['id_equipo1'];
   $id_equipo2=$row['id_equipo2'];


   //averiguar los nombres de los equipos
   $nombre_equipo1=$equipoobj->get_nombre($id_equipo1);
	$nombre_equipo2=$equipoobj->get_nombre($id_equipo2);


	if ($plantilla!=0){
      $id_partido=$partidoobj->get_id_partido_clon_from_original($id_partido,$id_evento);
   }

   print "<div style=\"position: relative; \"><table id=\"tabla$id_partido\" style=\"display: block; \" class=\"tabla_con_encabezado\">\n";
   print "<tr><th colspan=\"3\" style=\"text-align:center\">".$nombre_equipo1." vs ".$nombre_equipo2."\n";
?>
<tr>
   <th>#<th style="text-align:center">Usuario<th style="text-align:center">Notificar
<?php



$query_usuario="SELECT DISTINCT id_usuario,usuario
        FROM usuarios
        WHERE id_usuario NOT IN(SELECT id_usuario FROM apuestas
           WHERE id_partido=:id_partido)
        AND id_usuario IN (SELECT id_usuario FROM usuariosxevento WHERE id_evento=:id_evento)
        ORDER BY usuario ASC";
//print "q=$query_usuario<br>";

$result_usuario= $db->prepare($query_usuario,array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$result_usuario->bindParam(':id_partido',$id_partido);
$result_usuario->bindParam(':id_evento',$id_evento);
$result_usuario->execute();


$cadena="";
$num=$result_usuario->rowCount();

if ($num==0){
	print "<tr><td colspan=\"3\" style=\"text-align:center;\">No hay usuarios sin apuesta para este partido\n";
}else{
print "num=$num<br>";
$i=1;
//lista para correo
$lista_correo="";
while($row_usuario=$result_usuario->fetch(PDO::FETCH_ASSOC)){
   $id_usuario=$row_usuario['id_usuario'];
   $usuarioobj=new usuario($id_usuario);
   //$usuario=$row_usuario['usuario'];
   //$nombre=$row_usuario['nombre'];
//   $lista_correo.=$usuario.";";

   print "<tr><td>$i<td><img class=\"img_thumb\" style=\"max-width:75px;max-height:75px\" src=\"".$usuarioobj->get_imagen($id_usuario)."\">$usuarioobj->usuario";


print "<td style=\"text-align:center\"><a href=\"index.php?accion=evento_admin&id_evento=$id_evento&accion2=notificaciones&destino=$id_usuario\"><img src=\"imagenes/email.png\" style=\"width:40px; height:40px\"></a>";
   $i++;
}
}
print "</table></div>";
}

include 'includes/Close-Connection.php';
?>