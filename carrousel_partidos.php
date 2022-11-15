<!-- Inicio Carrousel -->


<?
require_once 'includes/class_equipo.php';
$eq=new equipo($db);

//el query está hecho x fuera para contemplar el caso de que no haya partidos a mostrar
//o que sean los partidos abiertos (por jugar)


//priemro armar el encabezado segú corresponda
if ($mobile  && !$android){?>
     <div class="table-responsive">
        <table class="table" style="width:100%;max-width:320px">
           <tr>
<?
}else{
?>
   <script type="text/javascript" src="includes/jquery.jcarousel.min.js"></script>
   <link rel="stylesheet" type="text/css" href="css/carrusel.css">
   <div id="wrap">
   <ul id="mycarousel" class="jcarousel-skin-tango">
<?
}
//recoger los datos
//por cada partido armar la tabla con las apuestas de todos
while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
   $id_partido=$row['id_partido'];
   $id_equipo1=$row['id_equipo1'];
   $id_equipo2=$row['id_equipo2'];
   $goles1=$row['goles1'];
   $goles2=$row['goles2'];
   $fecha=$row['fecha'];
   $hora=$row['hora'];
   $grupo=$row['grupo'];

   if ($goles1==-1) $goles1="-";
   if ($goles2==-1) $goles2="-";

	   //traducir el id del partido si se está usando plantilla
   if ($plantilla!=0){
      $id_partido=$partidoobj->get_id_partido_clon_from_original($id_partido,$id_evento);
   }

//escribir el registro según el tipo de dispositivo
   if ($mobile  && !$android){         print "<td><div style=\"border:1px solid #0D0909;padding:1px;width:115px;height: 60px;cursor:pointer; color: #000000;padding-top:10px;\" onclick=\"javascript:mostrarOcultarTablas('$id_partido');\">
           <img src=\"".$eq->get_imagen($id_equipo1)."\" style=\"max-width:40px;max-height:40px;position:relative;left:-35px;top:0px\" title=\"".$eq->get_nombre($id_equipo1)."\">
           <div style=\"position:relative;top:-32px;left:50px\">Vs</div>
           <img src=\"".$eq->get_imagen($id_equipo2)."\" style=\"max-width:40px;max-height:40px;position:relative;left:37px;top:-63px\" title=\"".$eq->get_nombre($id_equipo2)."\"></div>";
   }else{
      print"<li><div style=\"border:1px solid #0D0909;padding:1px;width:115px;height: 60px;cursor:pointer; color: #000000;padding-top:1px;\" onclick=\"javascript:mostrarOcultarTablas('$id_partido');\">
           <img src=\"".$eq->get_imagen($id_equipo1)."\" style=\"max-width:40px;max-height:40px;position:relative;left:-35px;top:10px\" title=\"".$eq->get_nombre($id_equipo1)."\">
           <div style=\"position:relative;top:-18px\">Vs</div>
           <img src=\"".$eq->get_imagen($id_equipo2)."\" style=\"max-width:40px;max-height:40px;position:relative;left:37px;top:-48px\" title=\"".$eq->get_nombre($id_equipo2)."\"></div></li>\n";
   }

}

//imprmir el cierre
if ($mobile  && !$android){    print "</table></div>";
}else{
	   print "</ul></div>\n";
}
?>


<!-- Fin Carrousel -->
