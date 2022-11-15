<?php

session_start();
//include 'includes/_Policy.php';
require 'includes/Open-Connection.php';
require_once 'audit.php';
audit_max();

//print_r($_POST);

function id_partido_nuevo(){
   //calcular el id
   $query="SELECT max(id_partido)as p FROM partidos";
   $stmt=$db->query($query);
   $row=$stmt->fetch(PDO::FETCH_ASSOC);
   $id_originales=$row['p'];

   $query="SELECT max(id_partido)as pc FROM partidos_clon";
   $stmt=$db->query($query);
   $row=$stmt->fetch(PDO::FETCH_ASSOC);
   $id_clones=$row['pc'];
	($id_originales>$id_clones) ? $id_partido=$id_originales+1 : $id_partido=$id_clones+1;

	return $id_partido;
}


$evento=$_POST['evento'];
if (isset($_POST['admin'])) $admin=$_POST['admin'];
$id_evento=$_POST['id_evento'];
$max_usuarios=$_POST['max_usuarios'];
$top_premios=$_POST['top_premios'];
$conf_usuarios=$_POST['conf_usuarios'];
($conf_usuarios) ? $conf_usuarios=1 : $conf_usuarios=0;
$porcentaje_premios=$_POST['porcentaje_premios'];
$valor=$_POST['valor'];
$max_marcador=$_POST['max_marcador'];
$max_aleatorio=$_POST['max_aleatorio'];
$publica=$_POST['publica'];
($publica) ? $publica=1 : $publica=0;
$num_rondas=$_POST['num_rondas'];
$descripcion=$_POST['descripcion'];
$activo=$_POST['activo'];
($activo)? $activo=1: $activo=0;
$plantilla=$_POST['plantilla'];
$tipo_premios=$_POST['tipo_premios'];
$porcentaje=$_POST['porcentaje'];

if ($tipo_premios=="sin_premios"){
    $porcentaje_premios='';
    $top_premios='';
    $porcentaje='';
}else if ($tipo_premios=="fijo"){   	$porcentaje='';
}

$accion2=$_POST['accion2'];


//verificar valor de la plantilla x si hay que hacer modificaciones
$query="SELECT plantilla FROM eventos WHERE id_evento='$id_evento'";
$stmt=$db->query($query);
$row=$stmt->fetch(PDO::FETCH_ASSOC);
$plantilla_ori=$row['plantilla'];

if ($plantilla_ori==0 and $plantilla!=0){
	 //borrar los partidos existentes
	 $query="DELETE FROM partidos WHERE id_evento='$id_evento'";
	 $stmt=$db->query($query);

	 //Incluir en partidos_clon los partidos del evento original
	 $query="SELECT id_partido FROM partidos WHERE id_evento='$plantilla'";
     foreach($db->query($query) as $row){
		  $id_partido=$row['id_partido'];
		  $id_partido_nuevo=id_partido_nuevo();
		  $query2="INSERT INTO partidos_clon VALUES ('$id_partido_nuevo','$id_partido','$id_evento')";
		  $db->query($query2);
	 }

	 //incluir los registros de gruposxevento
	 $query="SELECT id_equipo,num_ronda,grupo FROM gruposxevento WHERE id_evento='$plantilla'";
	 foreach($db->query($query) as $row){
		 $id_equipo=$row['id_equipo'];
		 $num_ronda=$row['num_ronda'];
		 $grupo=$row['grupo'];
		 $query2="INSERT INTO gruposxevento VALUES ('$id_evento','$id_equipo','$num_ronda','$grupo','0','0','0','0','0','0','0','0')";
		 $db->query($query2);
	 }
}

$query="UPDATE eventos set evento='$evento', ";
if (isset($_POST['admin'])) $query.="admin='$admin',";
$query.="max_usuarios='$max_usuarios',valor='$valor',top_premios='$top_premios',conf_usuarios='$conf_usuarios',num_rondas='$num_rondas',descripcion='$descripcion',
          tipo_premios='$tipo_premios',porcentaje='$porcentaje',porcentaje_premios='$porcentaje_premios',max_marcador='$max_marcador',max_aleatorio='$max_aleatorio',
          publica='$publica',activo='$activo',plantilla='$plantilla'
          WHERE id_evento='$id_evento'";
//print "query=$query<br>";
$db->query($query);

//procesar los nombres de las rondas y si manejan grupos
for ($i=1 ;  $i<=$num_rondas ; $i++){   $label_ronda=$_POST['ronda'.$i];
   $grupo=$_POST['gruposronda'.$i];

   //validar si ya existe un registro
   $query2="SELECT * FROM rondasxevento WHERE id_evento='$id_evento' AND num_ronda='$i'";
//print "q=$query2<br>";
   $stmt2=$db->query($query2);
   if ($stmt2->rowCount()>0){   	   $query="UPDATE rondasxevento SET nombre='$label_ronda',grupos='$grupo' WHERE id_evento='$id_evento' AND num_ronda='$i'";
   }else{   	   $query="INSERT INTO rondasxevento (id_evento,num_ronda,nombre,grupos) VALUES ('$id_evento','$i','$label_ronda','$grupo')";
   }
   $result = $db->query($query);

/*   //validar si hay eventos que usen este como plantilla y en caso tal modificarlo tambien
   $query_plantilla="SELECT id_evento FROM eventos WHERE plantilla='$id_evento'";
   $result_plantilla = mysql_query($query_plantilla) or die(mysql_error());
   while ($row_plantilla=mysql_fetch_assoc($result_plantilla)){
   	       $id_evento_clon=$row_plantilla['id_evento'];
           //validar si ya existe un registro
           $query_clon2="SELECT * FROM rondasxevento WHERE id_evento='$id_evento_clon' AND num_ronda='$i'";
print "q=$query_clon2<br>";
           $result_clon2 = mysql_query($query_clon2) or die(mysql_error());
           if (mysql_num_rows($result_clon2)>0){
   	   	      $query_clon="UPDATE rondasxevento SET nombre='$label_ronda',grupos='$grupo' WHERE id_evento='$id_evento_clon' AND num_ronda='$i'";
   	       }else{   	   	      $query_clon="INSERT INTO rondasxevento (id_evento,num_ronda,nombre,grupos) VALUES ('$id_evento_clon','$i','$label_ronda','$grupo')";
   	       }
   	       print "q=$query_clon<br>";
           $result_clon = mysql_query($query_clon) or die(mysql_error());
   }*/

}



//borrar las rondas mayores a num_rondas para no dejar basura
$query="DELETE FROM rondasxevento WHERE num_ronda>'$num_rondas' and id_evento='$id_evento'";
$db->query($query);



//ver si se modificó la imagen
if (file_exists("uploads/e".$id_evento.".png"))
   $extension=".png";
else if (file_exists("uploads/e".$id_evento.".PNG"))
   $extension=".PNG";
else if (file_exists("uploads/e".$id_evento.".jpg"))
    $extension=".jpg";
else if (file_exists("uploads/e".$id_evento.".JPG"))
    $extension=".JPG";
else if (file_exists("uploads/e".$id_evento.".jpeg"))
    $extension=".jpeg";
else if (file_exists("uploads/e".$id_evento.".JPEG"))
    $extension=".JPEG";
else if (file_exists("uploads/e".$id_evento.".bmp"))
    $extension=".bmp";
else if (file_exists("uploads/e".$id_evento.".gif"))
    $extension=".gif";
else if (file_exists("uploads/e".$id_evento.".BMP"))
    $extension=".BMP";

$file="uploads/e".$id_evento.$extension;

//print "archivo upload:$file<br>";


if (file_exists($file)){

    //borrar la imagen existente
if (file_exists("imagenes/logos_eventos".$id_evento.".png"))
   $extension2=".png";
else if (file_exists("imagenes/logos_eventos".$id_evento.".PNG"))
   $extension2=".PNG";
else if (file_exists("imagenes/logos_eventos".$id_evento.".jpg"))
    $extension2=".jpg";
else if (file_exists("imagenes/logos_eventos".$id_evento.".JPG"))
    $extension2=".JPG";
else if (file_exists("imagenes/logos_eventos".$id_evento.".jpeg"))
    $extension2=".jpeg";
else if (file_exists("imagenes/logos_eventos".$id_evento.".JPEG"))
    $extension2=".JPEG";
else if (file_exists("imagenes/logos_eventos".$id_evento.".bmp"))
    $extension2=".bmp";
else if (file_exists("imagenes/logos_eventos".$id_evento.".BMP"))
    $extension2=".BMP";//    print "extension=$extension2<br>";
$imagen1="imagenes/logos_eventos/".$id_evento.$extension2;


    if (file_exists($imagen1)){
//       print "borrando $imagen1<br>";
       unlink($imagen1);
    }

    if (copy($file,'imagenes/logos_eventos/'.$id_evento.$extension)){
       	//borrar el archivo de la carpeta uploads
       	unlink ($file);
//            print "copia ok<br>";
      }else{
//             print "copia paila<br>";
      }
}



$_SESSION['msg']="<span class=\"msg_ok\">Evento Modificado</span>";
require 'includes/Close-Connection.php';

if ($accion2=="parametros")
   $redirect="index.php?accion=evento_admin&id_evento=$id_evento&accion2=parametros";
else
   $redirect="index.php?accion=evento_detalle&id_evento=$id_evento";

//print "id_grupo=$id_grupo<br>redirect=$redirect<br>";

if (!headers_sent() && $msg == '') {
     header('Location: '.$redirect);
}
?>
