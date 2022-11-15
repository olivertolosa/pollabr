<link rel="stylesheet" href="css/jquery.cluetip.css" type="text/css" />
<script src="includes/jquery.cluetip.min.js"></script>

<style>
#myBtn {
  display: none;
  position: fixed;
  top: 100px;
  left: 630px;
  z-index: 99;
  font-size: 18px;
  border: none;
  outline: none;
  background-color: red;
  color: white;
  cursor: pointer;
  padding: 15px;
  border-radius: 4px;
}

#myBtn:hover {
  background-color: #555;
}
</style>


<button id="myBtn" title="Guardar" onclick="document.getElementById('apostar').submit();">Guardar</button>


<script>
// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function() {scrollFunction()};

function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        document.getElementById("myBtn").style.display = "block";
    } else {
        document.getElementById("myBtn").style.display = "none";
    }
}

</script>

<?
include 'seguridad.php';
require_once 'includes/_Policy.php';

require_once 'includes/class_equipo.php';
require_once 'includes/class_partido.php';

$eq=new equipo($db);
$partidoobj=new partido($db);

?>
<!-- <script src="includes/jquery.hoverpulse.js" type="text/javascript"></script> -->
<script language="JavaScript">
function modificar_select_2(id_select){   var objselect=document.getElementById(id_select);
   var indice=objselect.selectedIndex;
   valor=objselect.options[indice].value;
//   alert ("valor="+valor);
   if (valor==-1)
      objselect.selectedIndex=1;

}
</script>

<script>
function mostrarOcultarTablas(id){



ronda=id.substr(5)

mostrado=0;
elem = document.getElementById(id);
lin=document.getElementById('link_ronda'+ronda);
if(elem.style.display=='block'){
   mostrado=1;
   elem.style.display='none';
   lin.style.display='block';
}
if(mostrado!=1){
   elem.style.display='block';
   lin.style.display='none';
}
}
</script>
<!-- Carrusel con las fechas -->
<?// include 'carrousel.php'; ?>
<!-- Fin del Carrusel de Fechas -->
<?
$msg=$_SESSION['msg'];
if ($msg){
   echo "<p><center>" . $msg  . "</p>";
   $_SESSION['msg']="";
}
?>
<center>
<form name="apostar" id="apostar" method="POST" action="apostar_registrar.php"><input type='hidden' name='__token_timestamp__' value='1402631104'><input type='hidden' name='__token_val__' value='da018197355543189aeca6057732c8e4'>
<?php


//obtener el número de rondas del evento
require_once 'includes/class_evento.php';
$eventobj=new evento($db);
$num_rondas=$eventobj->get_numrondas($id_evento);


include 'function_tablas.php';
$ronda_visible=ronda_a_mostrar($id_evento,$num_rondas);

//obtener el marcador máximo que se puede apostar
$query_ap="SELECT max_marcador FROM eventos WHERE id_evento=:id_evento";
$stmt_ap= $db->prepare($query_ap);
$stmt_ap->bindParam(':id_evento',$id_evento);
$stmt_ap->execute();

$row_ap=$stmt_ap->fetch(PDO::FETCH_ASSOC);
$max_marcador=$row_ap['max_marcador'];

for ($ronda=1 ; $ronda<=$num_rondas; $ronda++){


//  poner la marca de la ronda
   $nombre_ronda=$eventobj->get_nombre_ronda($id_evento,$ronda);

   if ($nombre_ronda!=""){  //inicio del if ---si hay partidos en la ronda

      print "<div id=\"link_ronda$ronda\"><table class=\"tabla_con_encabezado\">
             <tr><th style=\"text-align: center;cursor:pointer;\" onclick=\"javascript:mostrarOcultarTablas('ronda$ronda');\">
             <strong>$nombre_ronda (click aqui)</strong></table></div>\n";
      print "<div id=\"ronda$ronda\" style=\"display: none;\">\n";
      print "<table class=\"tabla_con_encabezado\" id=\"tablita\">\n";
      print "<tr> <th colspan=\"9\" style=\"text-align: center; cursor:pointer;\" onclick=\"javascript:mostrarOcultarTablas('ronda$ronda');\">
             <strong>$nombre_ronda</strong>\n";

$hoy=date("Y-m-d");

      $plantilla=$eventobj->tiene_plantilla($id_evento);

      if ($plantilla!=0){
         $id_event=$plantilla;
         $queryplus=" AND id_partido IN (SELECT id_partido_original FROM partidos_clon WHERE id_evento=:id_evento)";
      }else{
         $id_event=$id_evento;
      }



      $query="SELECT * FROM partidos WHERE id_evento=:id_evento AND ronda=:ronda AND (editable='1' or fecha=:hoy) $queryplus ORDER BY fecha ASC";
      $stmt= $db->prepare($query,array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	  $stmt->bindParam(':id_evento',$id_event);
	  $stmt->bindParam(':ronda',$ronda);
	  $stmt->bindParam(':hoy',$hoy);
	  $stmt->execute();
  // print "q=$query<br>";

      $hoy=date('Y-m-d');

      if ($stmt->rowCount()==0){         print "<tr><td colspan=\"9\"  style=\"text-align: center;\">No hay partidos pendientes para esta fase\n";
      }


      $tabla_cabeza=true;

      while($row=$stmt->fetch(PDO::FETCH_ASSOC)){         $id_partido=$row['id_partido'];
         $id_equipo1=$row['id_equipo1'];
         $id_equipo2=$row['id_equipo2'];
         $fecha=$row['fecha'];
         $hora=$row['hora'];
         $hora=substr($hora,0,5);
         $grupo=$row['grupo'];
         $goles1=$row['goles1'];
         $editable=$row['editable'];

         //traducir el id del partido si se está usando plantilla
         if ($plantilla!=0){
            $id_partido=$partidoobj->get_id_partido_clon_from_original($id_partido,$id_evento);
         }


         $lista_partidos[]=$id_partido;

      //poner la marca para separación de grupo
         if ($tabla_cabeza){
           if ($grupo)
              print "<tr> <td colspan=\"9\" style=\"text-align: center;\"><strong>Grupo $grupo</strong>\n";

           print "<tr>
              <th colspan=\"3\" style=\"text-align: center;\">Equipo1
              <th colspan=\"3\" style=\"text-align: center;\">Equipo2
              <th width=\"95\" style=\"text-align: center;\">Fecha<th style=\"text-align: center;\">Hora\n";

           $tabla_cabeza=false;
         }
      //averiguar los nombres de los equipos
         $nombre_equipo1=$eq->get_nombre($id_equipo1);
         $nombre_equipo2=$eq->get_nombre($id_equipo2);


         //validar si el usuario ya registro marcador
         $query_marcador="SELECT equipo1,equipo2,aleatorio FROM apuestas WHERE id_partido=:id_partido AND id_usuario=:id_usuario";
         $stmt_marcador= $db->prepare($query_marcador,array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		 $stmt_marcador->bindParam(':id_partido',$id_partido);
		 $stmt_marcador->bindParam(':id_usuario',$id_usuario);
		 $stmt_marcador->execute();


         $marcador1="-1";
         $marcador2="-1";
         $aleatorio=0;
         $class="";
         if ($stmt_marcador->rowCount()>0){             $row=$stmt_marcador->fetch(PDO::FETCH_ASSOC);
   	         $marcador1=$row['equipo1'];
        	 $marcador2=$row['equipo2'];
     	     $aleatorio=$row['aleatorio'];
   	   //si el partido es hoy ponerle fondo especial
      	     if ($hoy==$fecha) $class="fila-con-premio";
         }else{
        //si el partido es hoy ponerle fondo especial       	        if ($hoy==$fecha) $class="fila-usuario";
         }



         print "<tr class=\"$class\"";
         if ($class=="fila-con-premio") print "style=\"background-color: #61C974\"";
//print "<td><a id=\"clickme$id_partido\" href=\"partido_estadisticas_apuestas.php?id_partido=$id_partido\" rel=\"partido_estadisticas_apuestas.php?id_partido=$id_partido\" title=\"$nombre_equipo1 Vs $nombre_equipo2\"><img src=\"imagenes/stats.png\" style=\"width:45px;height:45px;\"></a>"
         print "><td style=\"";
         if ($aleatorio){
            print "background: url(imagenes/random.png);background-repeat:no-repeat;background-position:95% 50%;\" title=\"Marcador Aleatorio";
          }
         print "\">$nombre_equipo1
           <td><div class=\"thumb\"><img src=\"".$eq->get_imagen($id_equipo1)."\" height=\"55\" width=\"55\" title=\"$nombre_equipo1\" id=\"img$id_equipo1\"></div>
            <td style=\"text-align: center;\">";
         if (!$editable){
             print "$marcador1";

         }else{             print "<input type=\"number\" name=\"p$id_partido-eq1\" min=\"0\" max=\"$max_marcador\" step=\"1\"";
             if ($marcador1!=-1)
                print " value=\"$marcador1\"";
             print ">";
         }
         print "<td><div class=\"thumb\"><img src=\"".$eq->get_imagen($id_equipo2)."\" height=\"55\" width=\"55\" title=\"$nombre_equipo2\" id=\"img$id_equipo2\"></div>
            <td style=\"";
         if ($aleatorio){
            print "background: url(imagenes/random.png);background-repeat:no-repeat;background-position:95% 50%;\" title=\"Marcador Aleatorio";
          }
         print "\">$nombre_equipo2
            <td style=\"text-align: center;\">";
         if (!$editable){
            print "$marcador2";
         }else{
            print "<input type=\"number\" name=\"p$id_partido-eq2\" min=\"0\" max=\"$max_marcador\" step=\"1\"";
            if ($marcador2!=-1)
               print " value=\"$marcador2\"";
            print ">";
         }
         print "<td style=\"text-align: center;\">$fecha<td style=\"text-align: center;\"	>$hora\n";

}
      //visibilidad
   if ($ronda==$ronda_visible){
   	  ?> <script>mostrarOcultarTablas('ronda<?= $ronda ?>');</script><?
   }

?>

</table>
</div>
<br><br>


<?

   }//fin del if ---si hay partidos en la ronda
}
?>
<br><br>
<input type="hidden" name="id_evento" value="<?= $id_evento ?>">
<input type="Submit" value="Registrar">

</form>
</center>
<!-- <script src="includes/jquery.cluetip.2.js"></script> -->
<script>

 $(document).ready(function() {

<?
   for ($i=0 ; $i<sizeof($lista_partidos) ;$i++){
       print "$('#clickme$lista_partidos[$i]').cluetip({activation: 'click', sticky: true,positionBy: 'fixed', topOffset: 35, closePosition: 'title', showTitle: false,width: '350px', cluetipClass: 'rounded', arrows: true,closeText: '<img src=\"imagenes/cross.png\" alt=\"Cerrar\" />'});\n";
   }
?>


});
</script>




