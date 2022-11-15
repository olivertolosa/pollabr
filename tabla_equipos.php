<?php
$id_grupo=$_GET['id_grupo'];
$id_evento=$_GET['id_evento'];
$id_bolsa=$_GET['id_bolsa'];
include 'includes/Open-Connection.php';

   print "<table class=\"tabla_simple\" style=\"display: block; max-height: 500px; overflow-y: scroll;width:280;\">\n";

if (isset($_GET['id_evento'])){
   $query="SELECT id_equipo,equipo FROM equipos
           WHERE id_equipo NOT IN
           ( SELECT id_equipo FROM equiposxevento WHERE id_evento=:id_evento)";
  if ($id_grupo!=0) $query.=" AND id_grupo_equipos=:id_grupo ";
}else if (isset($_GET['id_bolsa'])){
   $query="SELECT id_equipo,equipo FROM equipos
           WHERE id_equipo NOT IN
           (SELECT DISTINCT id_equipo FROM bolsa_acciones WHERE id_bolsa=:id_bolsa)
           AND id_grupo_equipos=:id_grupo ";
}
  $query.="ORDER BY equipo ASC";

$stmt= $db->prepare($query);
if (isset($_GET['id_evento'])){	$stmt->bindParam(':id_evento',$id_evento);
	if ($id_grupo!=0)  $stmt->bindParam(':id_grupo',$id_grupo);
}else if (isset($_GET['id_bolsa'])){
	$stmt->bindParam(':id_bolsa',$id_bolsa);
	$stmt->bindParam(':id_grupo',$id_grupo);
}
$stmt->execute();


	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
       $id_equipo=$row['id_equipo'];
       $equipo=$row['equipo'];
//       $equipo=utf8_encode($equipo);
      // detectar la extensión de la bandera
      if (file_exists("imagenes/logos_equipos/".$id_equipo.".png"))
         $extension=".png";
      else if (file_exists("imagenes/logos_equipos/".$id_equipo.".PNG"))
         $extension=".PNG";
      else if (file_exists("imagenes/logos_equipos/".$id_equipo.".jpg"))
          $extension=".jpg";
      else if (file_exists("imagenes/logos_equipos/".$id_equipo.".JPG"))
         $extension=".JPG";
      else if (file_exists("imagenes/logos_equipos/".$id_equipo.".bmp"))
          $extension=".bmp";
      else if (file_exists("imagenes/logos_equipos/".$id_equipo.".BMP"))
          $extension=".BMP";

      $imagen=$id_equipo.$extension;

       print "<tr ><td style=\"vertical-align:middle;\"><span class=\"lista_equipos\"><a href=\"javascript:inc_equipo($id_equipo,1)\"><img style=\"vertical-align:middle\" src=\"imagenes/logos_equipos/$imagen\" width=\"40\" height=\"40\">&nbsp;&nbsp;$equipo</a></span></td>\n";
    }
   print "</table>";

include 'includes/Close-Connection.php';
?>
