<?
///validar que el usuario si haga parte del evento
include 'seguridad.php';

$id_evento=$_REQUEST['id_evento'];
$id_usuario=$_REQUEST['id_usuario'];

$confirmado=$_POST['confirmado'];

$query="SELECT * FROM eventos WHERE id_evento='$id_evento'";
//print "q=$query<br>";
$stmt=$db->query($query);
$row=$stmt->fetch(PDO::FETCH_ASSOC);

$evento=$row['evento'];
$id_admin=$row['admin'];
$descripcion=$row['descripcion'];
$descripcion=nl2br($descripcion);
$max_usuarios=$row['max_usuarios'];
if ($max_usuarios==0) $max_usuarios="Ilimitado";
$admin=$row['admin'];
$top_premios=$row['top_premios'];
if ($top_premios==0) $top_premios="No";
$conf_usuarios=$row['conf_usuarios'];
$valor=$row['valor'];
($valor==0)? $valor="Gratis!!!" : $valor="$".number_format($valor,0,'.','.');
$max_marcador=$row['max_marcador'];
$max_aleatorio=$row['max_aleatorio'];
$num_rondas=$row['num_rondas'];
$porcentaje_premios=$row['porcentaje_premios'];
$publica=$row['publica'];
($publica)? $publica="Si" : $publica="No";
$activo=$row['activo'];
$tipo_premios=$row['tipo_premios'];


//obtener el login del admin
$query="SELECT usuario FROM usuarios WHERE id_usuario='$id_admin'";
$stmt=$db->query($query);
$row=$stmt->fetch(PDO::FETCH_ASSOC);

$admin=$row['usuario'];

?>

<center>
<h2>Reglas de juego del evento<br><?= $evento ?></h2>
<table class="tabla_con_encabezado">
<tr>
   <th>Descripción del evento
   <td><?= $descripcion ?>
<tr>
   <th>Administrador
   <td><?= $admin ?>
<tr>
   <th>Máximo de usuarios permitido
   <td><?= $max_usuarios ?>
<tr>
   <th>Marcador máximo permitido
   <td><?= $max_marcador ?>
<tr>
   <th>Marcador máximo aleatorio <br>(para usuarios que no registran marcador)
   <td><?= $max_aleatorio ?>
<tr>
   <th>Valor
   <td><?= $valor ?>
<tr>
   <th>Premios
   <td>
<?
       print "$top_premios";
       if ($top_premios!="No"){       	  print" Primeros lugares:<br>";       	  $premios_array=split(',',$porcentaje_premios);       	  for ($i=1 ; $i<=$top_premios ; $i++ ){       	  	  $j=$i-1;       	  	  print "<br>$i. ";
       	  	  if ($tipo_premios=="fijo") print "$".number_format($premios_array[$j],0,"",".");
       	  	  else if ($tipo_premios=="porcentaje") print "$premios_array[$j]%";

       	  }
          print "<br>Lor porcentajes correspondel al total del valor recaudado";
       }


?>
<tr>
   <th>Evento público
   <td><?= $publica ?>
<tbody>
<?



?>
</tbody>
</table>
</center>
<br>

