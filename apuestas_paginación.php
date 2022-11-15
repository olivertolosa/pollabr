<?
include 'seguridad.php';

require_once 'includes/class_equipo.php';
require_once 'includes/class_partido.php';

$eq=new equipo();
$partidoobj=new partido();

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
function pasar_pag(valor,ronda){
    //obtener el valor de la pag actual
    var pag_actual=document.getElementById('pag_ronda_'+ronda).value;
    
       
    
    
    //si la pag es mayor a 0 hacer visible el link de anteriores
    if (pag_actual>0) {
       //document.getElementById('prev'+ronda+(pag_actual-1)).display="block";
    }
    
    //ocultar la pag actual
    for (i=0 ; i< <?= $cantidad_partidos ?> ; i++) {
       //alert ('ocultando:tr_r'+ronda+'_p'+pag_actual+'_pa'+i);
       document.getElementById('tr_r'+ronda+'_p'+pag_actual+'_pa'+i).font-size='25px';
    }
    
    //mostrar la siguiente/anterior pag
    pag_actual=pag_actual+valor;   
    document.getElementById('pag_ronda_'+ronda).value=parseInt(document.getElementById('pag_ronda_'+ronda).value)+parseInt(valor);
    
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
<form name="apostar" method="POST" action="apostar_registrar.php">
<?php


//obtener el número de rondas del evento
require_once 'includes/class_evento.php';
$eventobj=new evento();
$num_rondas=$eventobj->get_numrondas($id_evento);


include 'function_tablas.php';
$ronda_visible=ronda_a_mostrar($id_evento,$num_rondas);

//obtener el marcador máximo que se puede apostar
$query_ap="SELECT max_marcador FROM eventos WHERE id_evento='$id_evento'";
$result_ap = mysql_query($query_ap) or die(mysql_error());
$row_ap=mysql_fetch_assoc($result_ap);
$max_marcador=$row_ap['max_marcador'];
mysql_free_result($result_ap);



for ($ronda=1 ; $ronda<=$num_rondas; $ronda++){


//  poner la marca de la ronda
   $nombre_ronda=$eventobj->get_nombre_ronda($id_evento,$ronda);

   if ($nombre_ronda!=""){  //inicio del if ---si hay partidos en la ronda

      print "<div id=\"link_ronda$ronda\"><table class=\"tabla_con_encabezado\">
             <tr><th style=\"text-align: center;cursor:pointer;\" onclick=\"javascript:mostrarOcultarTablas('ronda$ronda');\">
             <strong>$nombre_ronda (click aqui)</strong></table></div>\n";
      print "<div id=\"ronda$ronda\" style=\"display: none;\">\n";
      print "<table class=\"tabla_con_encabezado\" id=\"tablita\">\n";
      print "<tr> <th colspan=\"8\" style=\"text-align: center; cursor:pointer;\" onclick=\"javascript:mostrarOcultarTablas('ronda$ronda');\">
             <strong>$nombre_ronda</strong>\n";

$hoy=date("Y-m-d");

      $plantilla=$eventobj->tiene_plantilla($id_evento);
      
      if ($plantilla!=0){
         $id_event=$plantilla;
         $queryplus=" AND id_partido IN (SELECT id_partido_original FROM partidos_clon WHERE id_evento='$id_evento')";      
      }else{
         $id_event=$id_evento;
      }

      
      
      $query="SELECT * FROM partidos WHERE id_evento='$id_event' AND ronda='$ronda' AND (editable='1' or fecha='$hoy') $queryplus ORDER BY fecha ASC";
      $result = mysql_query($query) or die(mysql_error());
  // print "q=$query<br>";

      $hoy=date('Y-m-d');

      if ($num_partidos=mysql_num_rows($result)==0){         print "<tr><td colspan=\"8\"  style=\"text-align: center;\">No hay partidos pendientes para esta fase\n";
      }


      $tabla_cabeza=true;
      $cont_partidos=0;

      while($row=mysql_fetch_assoc($result)){         $id_partido=$row['id_partido'];
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




      //poner la marca para separación de grupo
         if ($tabla_cabeza){
           print " <input type=\"hidden\" name=\"pag_ronda_$ronda\" id=\"pag_ronda_$ronda\" value=\"1\">";
           if ($grupo)
              print "<tr> <td colspan=\"8\" style=\"text-align: center;\"><strong>Grupo $grupo</strong>\n";

           print "<tr><th colspan=\"3\" style=\"text-align: center;\">Equipo1
              <th colspan=\"3\" style=\"text-align: center;\">Equipo2
              <th width=\"95\" style=\"text-align: center;\">Fecha<th style=\"text-align: center;\">Hora\n";

           $tabla_cabeza=false;
         }
      //averiguar los nombres de los equipos
         $nombre_equipo1=$eq->get_nombre($id_equipo1);
         $nombre_equipo2=$eq->get_nombre($id_equipo2);


         //validar si el usuario ya registro marcador
         $query_marcador="SELECT equipo1,equipo2,aleatorio FROM apuestas WHERE id_partido='$id_partido' AND id_usuario='$id_usuario'";
         $result_marcador = mysql_query($query_marcador) or die(mysql_error());
         $marcador1="-1";
         $marcador2="-1";
         $aleatorio=0;
         $class="";
         if (mysql_num_rows($result_marcador)>0){             $row=mysql_fetch_assoc($result_marcador);
   	         $marcador1=$row['equipo1'];
        	 $marcador2=$row['equipo2'];
     	     $aleatorio=$row['aleatorio'];
   	   //si el partido es hoy ponerle fondo especial
      	     if ($hoy==$fecha) $class="fila-con-premio";
         }else{
        //si el partido es hoy ponerle fondo especial       	        if ($hoy==$fecha) $class="fila-usuario";
         }


         //setear la visibilidad de la fila
         ($cont_partidos<$cantidad_partidos)? $visibilidad="table-row": $visibilidad="none";
         
         
         print "<tr class=\"$class\" id=\"tr_r".$ronda."_p".$ronda."_pa".$cont_partidos."\" style=\"display:$visibilidad";
         if ($class=="fila-con-premio") print ";background-color: #61C974\"";
         print "\"><td style=\"";
         if ($aleatorio){
            print "background: url(imagenes/random.png);background-repeat:no-repeat;background-position:95% 50%;\" title=\"Marcador Aleatorio";
          }
         print "\">$nombre_equipo1
           <td>tr_r".$ronda."_p".$ronda."_pa".$cont_partidos."<div class=\"thumb\"><img src=\"".$eq->get_imagen($id_equipo1)."\" height=\"55\" width=\"55\" title=\"$nombre_equipo1\" id=\"img$id_equipo1\"></div>
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
         
         if ($cont_partidos%$cantidad_partidos==$cantidad_partidos-1){
             print "<tr style=\"display:$visibilidad\"><td colspan=\"4\" style=\"text-align:left;border-right:0px\"><span style=\"display:none\" id=\"prev_$ronda_".($cont_partidos/10)."\"><a href=\"javascript:pasar_pag(-1,'$ronda')\">Previos</a></span>
                                                       <td colspan=\"4\" style=\"text-align:right;border-left:0px\"><span style=\"display:block\" id=\"sig_$ronda_".($cont_partidos/10)."\"><a href=\"javascript:pasar_pag(1,'$ronda')\">Siguientes</a></span>";
         }
         
         $cont_partidos++;

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
<!--<script>
$(document).ready(function() {
    $('div.thumb img').hoverpulse({
        size: 80,  // number of pixels to pulse element (in each direction)
        speed: 400 // speed of the animation
    });
});
</script>

<script type="text/javascript">

jQuery(document).ready(function() {
    jQuery('#mycarousel').jcarousel();
});

</script>  -->


