<?
session_start();
include 'includes/_Policy.php';
require_once 'includes/class_equipo.php';

require_once 'includes/class_partido.php';

$partido=new partido($db);

$eq=new equipo($db);
$id_partido=$_REQUEST['id_partido'];
$id_evento=$_REQUEST['id_evento'];

$query="SELECT * FROM partidos WHERE id_partido=:id_partido";
$stmt= $db->prepare($query,array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$stmt->bindParam(':id_partido',$id_partido);
$stmt->execute();

$row=$stmt->fetch(PDO::FETCH_ASSOC);
$num=$stmt->rowCount();
//print "num=$num<br>";

$id_equipo1=$row['id_equipo1'];
$id_equipo2=$row['id_equipo2'];
$fecha=$row['fecha'];
$hora=$row['hora'];
$hora=substr($hora,0,5);
$editable=$row['editable'];
$ronda=$row['ronda'];
$tipo_e=$row['tipo_e'];
$id_evento=$row['id_evento'];
$goles1=$row['goles1'];
$goles2=$row['goles2'];
$marcaval=$row['marcaval'];

//validar si el partido está en progreso
$enprogreso=$partido->en_progreso($id_partido);


?>

<link rel="stylesheet" href="css/customSelectBox.css" />
<link rel="stylesheet" href="css/jquery.jscrollpane.css" />
<style type="text/css">
/* COUNTRY SELECTBOX */
/* This should be a sprite... but this is quicker for examples sake */
dd span, .selectedValue span {
	position: relative;
	top: 0px;
	display: block;
	height: 25px;
	width: 25px;
	float: left;
	clear: right;
}
<?
//crear las clases CSS para cada equipo
if ($tipo_e=='e')
   $query="SELECT id_equipo,equipo FROM equipos WHERE id_equipo IN(SELECT id_equipo FROM equiposxevento WHERE id_evento=:id_evento)ORDER BY equipo ASC";
else if ($tipo_e=='b')
   $query="SELECT id_equipo,equipo FROM equipos WHERE id_equipo IN(SELECT id_equipo FROM bolsa_acciones WHERE id_bolsa=:id_bolsa)ORDER BY equipo ASC";

$stmt= $db->prepare($query);
if ($tipo_e=='e') $stmt->bindParam(':id_evento',$id_evento);
else if ($tipo_e=='b') $stmt->bindParam(':id_bolsa',$id_bolsa);
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
   $id_equipo=$row['id_equipo'];
   $equipo=$row['equipo'];

   print "
dd span.equipo_$id_equipo, .selectedValue span.equipo_$id_equipo {
	background: url(\"".$eq->get_imagen($id_equipo)."\") no-repeat;
	background-size:25px 25px;
}
";
}

?>
</style>

<form name="mod_partido" action="partido_detalle_procesar.php" method="POST">
<center>
<?
echo $_SESSION['msg'];
print "<br><br>";
unset($_SESSION['msg']);
?>

<table class="tabla_simple">

<?php



?>
   <th>Id<th>Equipo1<th>Equipo2

<?


   print "<tr><td>$id_partido
             <td><select class=\"custom\" name=\"eq1\">";



   if ($tipo_e=='e')
      $queryeq="SELECT id_equipo,equipo FROM equipos WHERE id_equipo IN(SELECT id_equipo FROM equiposxevento WHERE id_evento=:id_evento)ORDER BY equipo ASC";
   else if ($tipo_e=='b')
      $queryeq="SELECT id_equipo,equipo FROM equipos WHERE id_equipo IN(SELECT id_equipo FROM bolsa_acciones WHERE id_bolsa=:id_bolsa)ORDER BY equipo ASC";

   $resulteq= $db->prepare($queryeq);
	if ($tipo_e=='e') $resulteq->bindParam(':id_evento',$id_evento);
	else if ($tipo_e=='b') $resulteq->bindParam(':id_bolsa',$id_bolsa);
	$resulteq->execute();


   while($roweq=$resulteq->fetch(PDO::FETCH_ASSOC)){
   	   $id_equipo=$roweq['id_equipo'];
   	   $nombre_eq=$roweq['equipo'];
       print "<option class=\"equipo_$id_equipo\" value=\"$id_equipo\" class=\"usa\"";
       if ($id_equipo==$id_equipo1) print " SELECTED";
       print ">$nombre_eq\n";
   }
   print "<td><SELECT class=\"custom\"  name=\"eq2\">";
   if ($tipo_e=='e')
      $query="SELECT id_equipo,equipo FROM equipos WHERE id_equipo IN(SELECT id_equipo FROM equiposxevento WHERE id_evento=:id_evento)ORDER BY equipo ASC";
   else if ($tipo_e=='b')
      $query="SELECT id_equipo,equipo FROM equipos WHERE id_equipo IN(SELECT id_equipo FROM bolsa_acciones WHERE id_bolsa=:id_bolsa)ORDER BY equipo ASC";

   $resulteq= $db->prepare($queryeq);
	if ($tipo_e=='e') $resulteq->bindParam(':id_evento',$id_evento);
	else if ($tipo_e=='b') $resulteq->bindParam(':id_bolsa',$id_bolsa);
	$resulteq->execute();


   while($roweq=$resulteq->fetch(PDO::FETCH_ASSOC)){
   	   $id_equipo=$roweq['id_equipo'];
   	   $nombre_eq=$roweq['equipo'];
       print "<option class=\"equipo_$id_equipo\" value=\"$id_equipo\"";
       if ($id_equipo==$id_equipo2) print " SELECTED";
       print ">$nombre_eq\n";
   }


   //ronda solo para eventos polla
   if ($tipo_e=='e'){
   print "<tr><th>Ronda";
      print "<td colspan=\"2\" style=\"text-align:center\"><SELECT name=\"ronda\">";
      //seleccionar los nombres de las rondas disponibles
      $query="SELECT num_ronda,nombre FROM rondasxevento WHERE id_evento=:id_evento";

      $stmt= $db->prepare($query);
	  $stmt->bindParam(':id_evento',$id_evento);
	  $stmt->execute();


	  while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          $label=$row['nombre'];
          $num_ronda=$row['num_ronda'];
          print "<option value=\"$num_ronda\"";
          if ($ronda==$num_ronda) print " SELECTED";
          print ">$label</option>";
      }
      print "</SELECT>";
   }

   print"<tr><th>Fecha y Hora<td colspan=\"2\" style=\"text-align:center\"><input type=\"date\" name=\"fecha\" value=\"$fecha\" required>\n
              <input type=\"time\" name=\"hora\" value=\"$hora\" required>\n";

   if ($tipo_e=='e'){   	  print "<tr><th>Editable<td colspan=\"2\" style=\"text-align:center\"><input type=\"checkbox\" name=\"editable\"";
      if ($editable) print " CHECKED";
      print ">\n";
   }
   print "<tr><th>Marcador<td colspan=\"2\" style=\"text-align:center\"><input type=\"number\" name=\"goles1\" value=\"$goles1\"> - <input type=\"number\" name=\"goles2\" value=\"$goles2\">\n";

   //marca de en progreso
   print "<tr><th>En Progreso<td colspan=\"2\" style=\"text-align:center\"><input type=\"checkbox\" name=\"enprogreso\"";
   if ($enprogreso) print " CHECKED";
   print ">\n";

   if ($tipo_e=='b'){//si es partido de bolsa poner la marca para ejecutar valoriazación
   	  print "<tr><th title=\"Ejecutar valorización al finalizar este partido\">Ejecutar valorización<td colspan=\"2\" style=\"text-align:center\"><input type=\"checkbox\" name=\"marcaval\"";
      if ($marcaval) print " CHECKED";
      print ">\n";
   }




?>
   <input type="hidden" name="id_partido" value="<?= $id_partido ?>">
</table>
<br>
<input type="submit" value="Modificar">
</form>
</center>
		<script src="includes/customselect/jScrollPane.js"></script>
		<script src="includes/customselect/jquery.mousewheel.js"></script>
		<script src="includes/customselect/SelectBox.js"></script>
		<script>
			$(function() {

				$("select.custom").each(function() {
					var sb = new SelectBox({
						selectbox: $(this),
						height: 250,
						width: 200
					});
				});

			});
		</script>
