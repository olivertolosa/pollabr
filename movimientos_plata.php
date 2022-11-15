<?php


session_start();
include 'includes/_Policy.php';

$id_usuario=$_REQUEST['id_usuario'];
if (isset($_POST['desde'])){
	$hasta=$_POST['hasta'];
}else{
	$hasta=$hoy=date("Y-m-d");
}

require_once 'includes/class_equipo.php';
require_once 'includes/class_usuario.php';
$eq=new equipo($db);


?>

<script>
$(document).ready(function() {
   $('.mostrar_transacciones').on('click', function () {
   	       id=id.substring(5);
           if($("#registros"+id+"-1").is(":visible")){
             $('.registros'+id).hide();
             $('#signo'+id).attr("src","imagenes/mas2.png");
           }else{
              $('.registros'+id).show();
              $('#signo'+id).attr("src","imagenes/menos.png");;
           }

    });
});
</script>

<h2>Listado de Movimientos financieros</h2>
<br>

<form name="fechas" action="index.php?accion=movimientos_plata" method=POST>
<table class="tabla_simple">
<tr><th>Desde<th>Hasta
<tr><td><input type="date" name="desde" value="<? echo $desde; ?>"><td><input type="date" name="hasta" value="<? echo $hasta; ?>">
<tr><td colspan="2" style="text-align:center"><input type="submit" value="Mostrar">
</table>
</form>
<center>

<table class="tabla_con_encabezado">
<tr>
   <th>&nbsp;<th>Fecha<th>hora<th>Usuario<th>Descripción<th>Valor
<?php

$query="SELECT fecha FROM movimientos_plata WHERE fecha BETWEEN '$desde' and '$hasta' AND id_usuario<>'0'
        ORDER BY fecha ASC";
//print "q=$query<br>";

$fecha_anterior=$desde;

$balance_total=0;

$stmt=$db->query($query);
$num=$stmt->rowCount();
if ($num==0){
}else{
  while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
   $fecha=$row['fecha'];

 if ($fecha_anterior!=$fecha){
   $fecha_anterior=$fecha;
   $balance_dia=0;

   //determinar si ese día hubo movimietos
   $query2="SELECT * FROM movimientos_plata WHERE fecha='$fecha' AND concepto<>'Abono hecho por Admin' AND id_usuario<>'0'";
//print "q2=$query2";
   $stmt2=$db->query($query2);
   if(!empty($stmt2)) $num_transacciones=$stmt2->rowCount();
   else $num_transacciones=0;

  if ($num_transacciones>0){
  	   while($row2=$stmt2->fetch(PDO::FETCH_ASSOC)){
		   $usr=new usuario($id_usuario);
          $hora=$row2['hora'];
          $monto=$row2['monto'];
          $favor=$row2['favor'];
          $concepto=$row2['concepto'];
          $pos=strpos($concepto,'id_apuestad');
          if (strpos($concepto,'id_apuestad')>0){
          	  $concepto=substr($concepto,0,strpos($concepto,"."));
          	  //agregar al concepto la descripción gráfica del partido
          	  $query3="SELECT id_equipo1,id_equipo2 FROM apuesta_directa WHERE id_apuesta='$id_apuestad'";
          	  $stmt3=$db->query($query3);
          	  $row3=$stmt3->fetch(PDO::FETCH_ASSOC);
          	  $id_equipo1=$row3['id_equipo1'];
          	  $id_equipo2=$row3['id_equipo2'];
          	  $desc_partido="<center><table class=\"tabla_sin_bordes\"><tr><td><img src=\"".$eq->get_imagen($id_equipo1)."\" class=\"img_thumb_small\" title=\"".$eq->get_nombre($id_equipo1)."\">
          	                            <td>Vs
          	                            <td><img src=\"".$eq->get_imagen($id_equipo2)."\" class=\"img_thumb_small\" title=\"".$eq->get_nombre($id_equipo2)."\">
          	                  </table></center>";
          	  $concepto.=$desc_partido;
          }

          $favor=$row2['favor'];
          //$monto=number_format($monto,0,'.','.');
          print "<tr style=\"display:none\" class=\"registros$i\" id=\"registros$i-$j\"><td>&nbsp;<td>&nbsp;<td>$hora<td style=\"text-align:right\">".$usr->get_usuario($id_usuario)."<td>$concepto<td>$favor$monto";
          $j++;
          ($favor=='-')? $balance_dia+=$monto : $balance_dia-=$monto;
       }
  }
  $balance_total+=$balance_dia;
   if ($num_transacciones>0){
         print "<tr class=\"mostrar_transacciones\"  id=\"fecha$i\" ><td><img id=\"signo$i\" src=\"imagenes/mas2.png\" style=\"width:20px;height:20px\"><td>$fecha<td colspan=\"4\" style=\"text-align:right\">\$$balance_dia";
   }else
      print "<tr><td>&nbsp;<td>$fecha";
 /*  print "<td style=\"text-align: center;\">Saldo al final del día
             <td style=\"text-align: center;\">&nbsp;
             <td style=\"text-align: center;\">\$$saldo"; */


  }
  $i++;
 }

}
?>
</table>
<?  print "Balance: $balance_total"; ?>
</center>
