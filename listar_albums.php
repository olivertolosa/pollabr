<?
session_start();
include 'includes/_Policy.php';
?>

<center>
<table class="tabla_simple">
<tr>
   <th>Id<th colspan="2">Album
<?php

$query="SELECT * FROM albums WHERE activo='1' ORDER BY album ASC";
$stmt = $db->query($query);
$cadena="";
if ($stmt->rowCount()==0){
   print "<tr><td colspan=\"2\"><center>No se encontraron albums activos</center></td>\n";;
}else{
   $i=1;
   while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
      $id_album=$row['id_album'];
      $album=$row['album'];

    if (file_exists("imagenes/albums/".$id_album.".png"))
       $extension2=".png";
    else if (file_exists("imagenes/albums/".$id_album.".PNG"))
       $extension2=".PNG";
    else if (file_exists("imagenes/albums/".$id_album.".jpg"))
       $extension2=".jpg";
    else if (file_exists("imagenes/albums/".$id_album.".JPG"))
       $extension2=".JPG";
    else if (file_exists("imagenes/albums/".$id_album.".bmp"))
       $extension2=".bmp";
   else if (file_exists("imagenes/albums/".$id_album.".BMP"))
       $extension2=".BMP";
//    print "extension=$extension2<br>";
    $logo="imagenes/albums/".$id_album.$extension2;

      ($i%2==0) ? $class="tabla-fila-par" : $class="tabla-fila-impar";
      print "<tr class=\"$class\"><td width=\"40\">$id_album
              <td><div class=\"img_thumb\"><img src=\"$logo\" style=\"max-width:65px;max-height:65px\"></div>
              <td>&nbsp;&nbsp;<a href=\"index.php?accion=album_detalle&id_album=$id_album\">$album</a></td>\n";
       $i++;
   }
}
?>
</table>
</center>
