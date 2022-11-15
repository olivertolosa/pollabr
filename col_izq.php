<!-- countdown :
<iframe src="includes/countdown/main.html" width="220" height="300" scrolling="no" frameBorder="0"></iframe>
-->
<?php

include 'eventos_ultimos.php';
print "<br>";
//include 'mona_album.php';
//print "<br>";
include 'nacho.php';
//print "<br>";
//include 'apuestasd_disponibles.php';
print "<br>";

//si el usuario tiene equipos favoritos poner los resultados
if (isset($id_usuario)){
   $query="SELECT count(*) as cuantos FROM equipos_favoritos WHERE id_usuario='$id_usuario'";
//   print "q=$query"; exit();
   $stmt = $db->query($query);
   $row=$stmt->fetch(PDO::FETCH_ASSOC);
   $cuantos=$row['cuantos'];

   if ($cuantos>0){
      include 'resultados_favoritos.php';
      print "<br>";
   }
}
//include 'widget_fifa.php';
//print "<br>";
//include 'widget_dimayor.php';
//print "<br>";
//include 'estadisticas.php';
print "<br>";
//include 'widget_resultados.php';


?>
