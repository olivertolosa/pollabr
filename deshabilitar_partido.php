<?

include 'includes/Open-Connection.php';

//deshabilitar partido x linea de comandos o tarea programada
parse_str(implode('&', array_slice($argv, 1)), $_GET);
$id_partido=$_GET['id_partido'];


//deshabilitar partidos según la fecha
//seleccionar partido q está por empezar
/*$fecha=date('Y-m-d');
$hr=date('H');
$hr--;
if ($hr==10)
   $hora="11:00:00";
else
   $hora="13:45:00";
$query="SELECT id_partido FROM partidos WHERE fecha='$fecha' and hora='$hora'";
print "q=$query";

$result = mysql_query($query) or die(mysql_error());
$row=mysql_fetch_assoc($result);
$id_partido=$row['id_partido']; */

if ($id_partido>0){
   $query="UPDATE partidos SET editable='0' WHERE id_partido='$id_partido'";
   //print "q=$query";
   $stmt = $db->query($query);

   //poner marcadores en 0 para los q no apostaron
   $query_usuarios="SELECT id_usuario FROM usuarios";
   foreach($db->query($query) as $row_usuarios) {   	  $id_usuario=$row_usuarios['id_usuario'];      $query_apuesta="SELECT * FROM apuestas WHERE id_usuario='$id_usuario' AND id_partido='$id_partido'";
//print "q=$query_apuesta<br>";
      $result_apuesta = $db->query($query);
      if ($result_apuesta->rowCount()==0){
         $hoy=date('Y-m-d H:i');
         //calcular valores aleatorios para los marcadores
         $rnd1=rand(0,4);
         $rnd2=rand(0,4);
   	     $query_update="INSERT INTO apuestas VALUES('$id_usuario','$id_partido','$rnd1','$rnd2','$hoy','1')";
   	     //print "q=$query_update<br>";
   	     $db->query($query_update);
      }
   }
}else{
   print "no hay partido";
}
include 'includes/Close-Connection.php'


?>
