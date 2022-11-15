<link rel="stylesheet" type="text/css" href="css/polla.css" />
<?php
require 'includes/Open-Connection.php';

require 'includes/class_partido.php';
$partidoobj=new partido();
require 'includes/class_equipo.php';
$eq=new equipo();

$id_partido=$_GET['id_partido'];
$lista_partidos=$id_partido.",";



$query="SELECT id_equipo1,id_equipo2 FROM partidos WHERE id_partido='$id_partido'";
$result= mysql_query($query) or die(mysql_error());
$row=mysql_fetch_assoc($result);
$id_equipo1=$row['id_equipo1'];
$id_equipo2=$row['id_equipo2'];


$query="SELECT equipo1, equipo2
          FROM apuestas
          WHERE id_partido ='$id_partido'
         GROUP BY equipo1, equipo2
         ORDER BY equipo1 ASC, equipo2 ASC";
$result= mysql_query($query) or die(mysql_error());
//print "q=$query<br>";
$total_apuestas=0;

while ($row=mysql_fetch_assoc($result)){
    $eq1=$row['equipo1'];
    $eq2=$row['equipo2'];
    
    $query_suma="SELECT count(*) as suma FROM apuestas WHERE id_partido='$id_partido' AND equipo1='$eq1' AND equipo2='$eq2'";
//    print "<br>q_suma=$query_suma<br>";
    $result_suma= mysql_query($query_suma) or die(mysql_error());
    $row_suma=mysql_fetch_assoc($result_suma);
    $apuestas[$eq1."-".$eq2]=$row_suma['suma'];
    $total_apuestas+=$apuestas[$eq1."-".$eq2];    
}



?>
<center>
<table  class="tabla_simple" style="max-width:270px;">
   <tbody style="display:block;">
      <th colspan="3" style="text-align:center;"><img src="<?= $eq->get_imagen($id_equipo1) ?>" width="55" height="55" title="<?= $eq->get_nombre($id_equipo1) ?>"> VS <img src="<?= $eq->get_imagen($id_equipo2) ?>" width="55" height="55" title="<?= $eq->get_nombre($id_equipo2) ?>"></th>

<?
if ($total_apuestas==0){
   print utf8_encode("<tr><td colspan=\"3\">Ningún usuario ha registrado marcador para este partido todavía");
}else{
foreach ($apuestas as $llave=>$valor){
   print "<tr><td style=\"width:80px;border-right:0px solid;text-align:center;\">$llave
           <td style=\"border-left:0px solid;border-right:0px;width:10%\">$valor
           <td style=\"border-left:0px solid;border-right:solid #ccc 1px;width:60%\"><img src=\"imagenes/poll.gif\" width=";
           echo(300*round($valor/$total_apuestas,5));
   print "' height='20'>";
}

}


require 'includes/Close-Connection.php';
?>
   </tbody>
</table>
</center>


