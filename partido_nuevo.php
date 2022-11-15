<?
session_start();
include 'includes/_Policy.php';

require_once 'includes/class_equipo.php';
$eq=new equipo($db);
require_once 'includes/class_evento.php';
$eventoobj=new evento($db);

$plantilla=$eventoobj->tiene_plantilla($id_evento); //si es una bolsa va a dar 0


if ($plantilla!=0){
	print "<span class=\"msg_warn\">Este evento usa una plantilla.<br><br>No es posible adicionar partidos</span>";

}else{

	//si noes plantilla el resto!!!

$id_partido=$_REQUEST['id_partido'];
$id_bolsa=$_REQUEST['id_bolsa'];


//Tipo de Evento (polla o bolsa)
if (isset($_REQUEST['id_evento']))
   $tipo_e="e";
else if (isset($_REQUEST['id_bolsa']))
   $tipo_e="b";

//validar que haya equipos inscritos...de lo contrario no dejar armar partidos
if ($tipo_e=='e')
   $query="SELECT id_equipo FROM equiposxevento WHERE id_evento=:id_evento";
else if ($tipo_e=='b')
   $query="SELECT id_equipo FROM bolsa_acciones WHERE id_bolsa=:id_bolsa";

$stmt= $db->prepare($query,array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
if ($tipo_e=='e') $stmt->bindParam(':id_evento',$id_evento);
else if ($tipo_e=='b')  $stmt->bindParam(':id_bolsa',$id_bolsa);
$stmt->execute();


if ($stmt->rowCount()<=1){
   print "<span class=\"msg_error\">Debe incluir equipos para poder armar partidos</span>";
}else{
echo $_SESSION['msg'];
print "<br>";
unset($_SESSION['msg']);

?>

		<script src="includes/customselect/jScrollPane.js"></script>
		<script src="includes/customselect/jquery.mousewheel.js"></script>
		<script src="includes/customselect/SelectBox.js"></script>

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
	z-index: 50;
}
<?



//crear las clases CSS para cada equipo
if ($tipo_e=='e')
   $query="SELECT id_equipo,equipo FROM equipos WHERE id_equipo IN(SELECT id_equipo FROM equiposxevento WHERE id_evento=:id_evento) ORDER BY equipo ASC";
else if ($tipo_e=='b')
   $query="SELECT id_equipo,equipo FROM equipos WHERE id_equipo IN(SELECT id_equipo FROM bolsa_acciones WHERE id_bolsa=:id_bolsa) ORDER BY equipo ASC";

$stmt= $db->prepare($query);
if ($tipo_e=='e') $stmt->bindParam(':id_evento',$id_evento);
else if ($tipo_e=='b')  $stmt->bindParam(':id_bolsa',$id_bolsa);
$stmt->execute();


while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {   $id_equipo=$row['id_equipo'];
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
<script>
$(function() {
      $("#boton_agregar").click( function()
           {
              var secuencia=$('#secuencia').val();
              secuencia++;
//              alert (secuencia);
              $('#secuencia').val(secuencia);
//              $("#partido_container").clone(false).find("*[id]").andSelf().each(function() { $(this).attr("id", $(this).attr("id") + "-"+secuencia); });
//              $("#partido_container").clone().attr("id","eq1-2").appendTo("#all_container");
//              alert ($(this).attr);

            var table = document.getElementById('tabla_partidos');

            var rowCount = table.rows.length;
            document.getElementById('num_rows').value=rowCount;
            var row = table.insertRow(rowCount);

            var cell1 = row.insertCell(0);
            var element1 = document.createElement("input");
            element1.type = "checkbox";
            element1.name="chkbox[]";
            cell1.appendChild(element1);

            var cell2 = row.insertCell(1);
            var selectt1 = document.getElementById("eq1-1").cloneNode(true);
            selectt1.setAttribute("name", "eq1-"+secuencia);
            selectt1.setAttribute("id", "eq1-"+secuencia);
            selectt1.onchange = (function( cntr ) {
                 return function() { sellcalculate(cntr); };
             })( secuencia );
            cell2.appendChild(selectt1);

            var cell3 = row.insertCell(2);
            var selectt2 = document.getElementById("eq1-1").cloneNode(true);
            selectt2.setAttribute("name", "eq2-"+secuencia);
            selectt2.setAttribute("id", "eq2-"+secuencia);
            selectt2.onchange = (function( cntr ) {
                 return function() { sellcalculate(cntr); };
             })( secuencia );
            cell3.appendChild(selectt2);

<? if ($tipo_e=='e'){?>
            var cell4 = row.insertCell(3);
            var selectt = document.getElementById("ronda-1").cloneNode(true);
            selectt.setAttribute("name", "ronda-"+secuencia);
            selectt.setAttribute("id", "ronda-"+secuencia);
            selectt.onchange = (function( cntr ) {
                 return function() { sellcalculate(cntr); };
             })( secuencia );
            cell4.appendChild(selectt);
<?
}
?>
<? if ($tipo_e=='e'){
?>
            var cell5 = row.insertCell(4);
<? }else{ ?>
            var cell5 = row.insertCell(3)
<? } ?>
            var element3 = document.createElement("input");
            element3.type = "date";
            element3.name="fecha-"+secuencia;
            cell5.appendChild(element3);
            element3.style.width= "130px";

<? if ($tipo_e=='e'){
?>
            var cell6 = row.insertCell(5);
<? }else{ ?>
            var cell6 = row.insertCell(4);
<? } ?>

            var element4 = document.createElement("input");
            element4.type = "time";
            element4.name="hora-"+secuencia;
            cell6.appendChild(element4);

            var sel1="#eq1-"+secuencia;
            var sel2="#eq2-"+secuencia;



              $(sel1).each(function() {					var sb = new SelectBox({
						selectbox: $(this),
						height: 250,
						width: 200
					});
				});
				$(sel2).each(function() {
					var sb = new SelectBox({
						selectbox: $(this),
						height: 250,
						width: 200
					});
				});          }

      );
});
</script>



<form name="mod_partido" action="partido_nuevo_procesar.php" method="POST">
<input type="hidden" id="secuencia" name="secuencia" value="1">
<input type="hidden" id="num_rows" name="num_rows" value="1">
<center>
<br><br>
<div id="all_container">
<div id="partido_container">
<table border="1" id="tabla_partidos">
<tr>
   <th><th>Equipo1<th>Equipo2
<? if ($tipo_e=='e')
   print "<th>Ronda ";
?>

   <th>Fecha<th>Hora
<?php


   print "<tr>
             <td><input type=\"checkbox\" name=\"chkbox[]\">
             <td><select class=\"custom\" name=\"eq1-1\" id=\"eq1-1\">";

if ($tipo_e=='e')
   $queryeq="SELECT id_equipo,equipo FROM equipos WHERE id_equipo IN(SELECT id_equipo FROM equiposxevento WHERE id_evento=:id_evento)ORDER BY equipo ASC";
else if ($tipo_e=='b')
   $queryeq="SELECT id_equipo,equipo FROM equipos WHERE id_equipo IN(SELECT id_equipo FROM bolsa_acciones WHERE id_bolsa=:id_bolsa)ORDER BY equipo ASC";


$stmteq= $db->prepare($queryeq);
if ($tipo_e=='e') $stmteq->bindParam(':id_evento',$id_evento);
else if ($tipo_e=='b')  $stmteq->bindParam(':id_bolsa',$id_bolsa);
$stmteq->execute();


	while($roweq = $stmteq->fetch(PDO::FETCH_ASSOC)) {   	   $id_equipo=$roweq['id_equipo'];
   	   $nombre_eq=$roweq['equipo'];
       print "<option class=\"equipo_$id_equipo\" value=\"$id_equipo\" class=\"usa\"";
       if ($id_equipo==$id_equipo1) print " SELECTED";
       print ">$nombre_eq\n";
   }
   print "<td><SELECT class=\"custom\"  name=\"eq2-1\" id=\"eq2-1\">";

if ($tipo_e=='e')
   $queryeq="SELECT id_equipo,equipo FROM equipos WHERE id_equipo IN(SELECT id_equipo FROM equiposxevento WHERE id_evento=:id_evento)ORDER BY equipo ASC";
else if ($tipo_e=='b')
   $queryeq="SELECT id_equipo,equipo FROM equipos WHERE id_equipo IN(SELECT id_equipo FROM bolsa_acciones WHERE id_bolsa=:id_bolsa)ORDER BY equipo ASC";

$stmteq= $db->prepare($queryeq);
if ($tipo_e=='e') $stmteq->bindParam(':id_evento',$id_evento);
else if ($tipo_e=='b')  $stmteq->bindParam(':id_bolsa',$id_bolsa);
$stmteq->execute();


	while($roweq = $stmteq->fetch(PDO::FETCH_ASSOC)) {
   	   $id_equipo=$roweq['id_equipo'];
   	   $nombre_eq=$roweq['equipo'];
       print "<option class=\"equipo_$id_equipo\" value=\"$id_equipo\"";
       if ($id_equipo==$id_equipo2) print " SELECTED";
       print ">$nombre_eq\n";
   }

   if ($tipo_e=='e'){   	  print "<td><SELECT name=\"ronda-1\" id=\"ronda-1\">";
      //seleccionar los nombres de las rondas disponibles
      $query="SELECT nombre,num_ronda FROM rondasxevento WHERE id_evento=:id_evento";
      $stmt= $db->prepare($query);
	  $stmt->bindParam(':id_evento',$id_evento);
	  $stmt->execute();

      while ($row=$stmt->fetch(PDO::FETCH_ASSOC)){         $label=$row['nombre'];
         $num_ronda=$row['num_ronda'];
          print "<option value=\"$num_ronda\">$label</option>";
      }
      print "</SELECT>";
    }
    print"<td><input type=\"date\" style=\"max-width: 130px;\" name=\"fecha-1\" id=\"fecha-1\" required>\n
              <td align=\"center\"><input type=\"time\" style=\"max-width: 130px;\" name=\"hora-1\" value=\"$hora\" required>\n";



?>


</table>
</div>
</div>
<br>
<input type="hidden" name="id_evento" value="<?= $id_evento ?>">
<input type="hidden" name="id_bolsa" value="<?= $id_bolsa ?>">
<input type="button" id="boton_agregar" value="Agregar Partido">
<input type="button" value="Eliminar Partido(s)" onclick="deleteRow('tabla_partidos')" >
<br>
<input type="submit" value="Guardar">
</form>
</center>
		<script>
			$(function() {

				$("#eq1-1").each(function() {
					var sb = new SelectBox({
						selectbox: $(this),
						height: 250,
						width: 200
					});
				});
				$("#eq2-1").each(function() {
					var sb = new SelectBox({
						selectbox: $(this),
						height: 250,
						width: 200
					});
				});

			});
		</script>
<?
}
}
?>
