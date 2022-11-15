<?php
$grupo=$_GET['grupo'];
$id_evento=$_GET['id_evento'];
$num_ronda=$_GET['num_ronda'];
include 'includes/Open-Connection.php';

   print "<table class=\"tabla_simple\" style=\"display: block; max-height: 500px; overflow-y: scroll;width:280;\">\n";

   $query="SELECT e.id_equipo,e.equipo
           FROM equipos as e, equiposxevento as exe
           WHERE e.id_equipo NOT IN (SELECT id_equipo FROM gruposxevento WHERE id_evento=:id_evento AND num_ronda=:num_ronda)
           AND exe.id_evento='$id_evento'
           AND exe.id_equipo=e.id_equipo
           ORDER BY equipo ASC";

	$stmt= $db->prepare($query);
	$stmt->bindParam(':id_evento',$id_evento);
	$stmt->bindParam(':num_ronda',$num_ronda);
	$stmt->execute();


	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
       $id_equipo=$row['id_equipo'];
       $equipo=$row['equipo'];
       $equipo=$equipo;
      // detectar la extensión de la bandera
      if (file_exists("imagenes/logos_equipos/".$id_equipo.".png"))
         $extension=".png";
      else if (file_exists("imagenes/logos_equipos/".$id_equipo.".PNG"))
         $extension=".PNG";
      else if (file_exists("imagenes/logos_equipos/".$id_equipo.".jpg"))
          $extension=".jpg";
      else if (file_exists("imagenes/logos_equipos/".$id_equipo.".GIF"))
         $extension=".GIF";
      else if (file_exists("imagenes/logos_equipos/".$id_equipo.".gif"))
          $extension=".gif";
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
