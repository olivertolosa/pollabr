	<!-- *****************************************************************************************
                                                INICIO Carrousel
**********************************************************************************************  -->
<?

//priemro armar el encabezado segú corresponda
if ($mobile){
?>
     <div class="table-responsive">
        <table class="table table-condensed" style="width:100%;max-width:280px">
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
$query_fechas="SELECT DISTINCT fecha FROM partidos WHERE id_evento='$id_evento' AND ronda='1' ORDER BY fecha DESC";
$stmt_fechas=$result_fechas = $db->query($query_fechas);
$i=1;
$num_fechas=$stmt_fechas->rowCount();
while ($row_fechas=$stmt_fechas->fetch(PDO::FETCH_ASSOC)){
   $fecha=$row_fechas['fecha'];
   $dia=substr($fecha,8);
   $mes=substr($fecha,5,2);
   $anho=substr($fecha,0,4);

   switch ($mes){
   	  case 1 : $mes="Ene";
               break;
   	  case 2 : $mes="Feb";
               break;
   	  case 3 : $mes="Mar";
               break;
   	  case 4 : $mes="Abr";
               break;
   	  case 5 : $mes="May";
               break;
   	  case 6 : $mes="Jun";
               break;
   	  case 7 : $mes="Jul";
               break;
   	  case 8 : $mes="Ago";
               break;
   	  case 9 : $mes="Sep";
               break;
   	  case 10 : $mes="Oct";
               break;
   	  case 11 : $mes="Nov";
               break;
   	  case 12 : $mes="Dic";
               break;

   }

//escribir el registro según el tipo de dispositivo
   if ($mobile){        print "<td style=\"text-align: center\"><div style=\"border: 1px solid #0D0909;padding:1px;width:115px;height: 60px;cursor:pointer;text-align:middle;color: #000000;padding-top:10px;\" onclick=\"cambiarTablas('fechac$i',$num_fechas)\">
       $dia<br>$mes&nbsp;$anho</div>";
   }else{
       print "<li><center><div style=\"border: 1px solid #0D0909;padding:1px;width:115px;height: 60px;cursor:pointer;text-align:middle;color: #000000;padding-top:10px;\" onclick=\"cambiarTablas('fechac$i',$num_fechas)\">
       $dia<br>$mes&nbsp;$anho</div></center></li>";
   }
   $i++;
}
//imprmir el cierre
if ($mobile){
    print "</table></div>";
}else{
   print "</ul></div>";
}
?>
