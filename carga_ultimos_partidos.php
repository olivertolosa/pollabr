<?
session_start();

require_once 'includes/Open-Connection.php';
include 'audit.php';
include 'includes/class_equipo.php';
include 'includes/class_liga.php';

$eq=new equipo($db);
$liga_obj=new liga($db);

$id_equipo=$_GET['id_equipo'];


?>
<center>
<link rel="stylesheet" type="text/css" href="css/polla.css" />
<link rel="stylesheet" href="css/jquery.modal.css" type="text/css" media="screen" />


<script>
$(document).ready(function() {
$('#contrario').on('change', function() {
		     var contrario = $(this).val();
		     var liga = $('#liga').val();
		     liga=$.trim(liga).replace(/ /g,"%20")
             $(document).ajaxStart(function(){
                     //$("#loadingdiv").css("display","block");
                  });
             $(document).ajaxComplete(function(){
                      //$("#loadingdiv").css("display","none");
                  });
             $("#resultados_partidos").load("carga_ultimos_partidos_ajax.php?id_equipo=<? echo $id_equipo; ?>&contrario="+contrario+"&liga="+liga);
        });
$('#liga').on('change', function() {
		     var contrario = $('#contrario').val();
		     var liga = $('#liga').val();
		     liga=$.trim(liga).replace(/ /g,"%20")
             $(document).ajaxStart(function(){
                     //$("#loadingdiv").css("display","block");
                  });
             $(document).ajaxComplete(function(){
                      //$("#loadingdiv").css("display","none");
                  });
             $("#resultados_partidos").load("carga_ultimos_partidos_ajax.php?id_equipo=<? echo $id_equipo; ?>&contrario="+contrario+"&liga="+liga);
        });
});

</script>

<?



//obtener nombre y logo del equipo
print "<table><th><td><img src=\"".$eq->get_imagen($id_equipo)."\" style=\"max-width:65px; max-height:65px\"><td><h2>".$eq->get_nombre($id_equipo)."</h2></table>";

print "<table>
          <th colspan=\"2\">Filtrar por:
          <tr>
             <td>Equipo contrario
             <td><SELECT name=\"contrario\" id=\"contrario\">
                 <option value=\"0\">Todos";

$query="SELECT id_equipo1,id_equipo2 FROM partidos2 WHERE id_equipo1='$id_equipo' OR id_equipo2='$id_equipo'";
$stmt=$db->query($query);
while ($row=$stmt->fetch(PDO::FETCH_ASSOC)){
   $id_equipo1=$row['id_equipo1'];
   $id_equipo2=$row['id_equipo2'];
   ($id_equipo1==$id_equipo)? $id_contrario=$id_equipo2 : $id_contrario=$id_equipo1;
   $cadena.=$id_contrario.",";
}
$cadena=substr($cadena,0,strlen($cadena)-1);

$query="SELECT id_equipo,equipo FROM equipos WHERE id_equipo IN ($cadena) ORDER BY equipo ASC";
$stmt=$db->query($query);
while ($row=$stmt->fetch(PDO::FETCH_ASSOC)){
	$id_equipox=$row['id_equipo'];
	$equipo=$row['equipo'];
   print "<option value=\"$id_equipox\">$equipo";
}

print "             </SELECT>
          <tr>
             <td>Torneo
             <td><SELECT name=\"liga\" id=\"liga\">
                <option value=\"0\">Todos";

$query="SELECT DISTINCT comentario FROM partidos2 WHERE id_equipo1='$id_equipo' OR id_equipo2='$id_equipo' ORDER BY comentario ASC";
$stmt=$db->query($query);
while ($row=$stmt->fetch(PDO::FETCH_ASSOC)){
    $comentario=$row['comentario'];
    print "<option>$comentario";
}
print "             </SELECT>

</table>";



print "<br><div id=\"resultados_partidos\"><table class=\"tabla_con_encabezado\" style=\"font-size:11px\">
           <thead><th>Fecha<th>Liga<th>Equipo1<th>Equipo2<th>Marcador";

$query="SELECT * FROM partidos2 WHERE id_equipo1='$id_equipo' OR id_equipo2='$id_equipo' ORDER BY fecha DESC LIMIT 0,10";
//print "<br>q=$query<br>";
$stmt=$db->query($query);
while ($row=$stmt->fetch(PDO::FETCH_ASSOC)){
   $id_equipo1=$row['id_equipo1'];
   $id_equipo2=$row['id_equipo2'];
   $id_liga=$row['id_liga'];
   $fecha=$row['fecha'];
   $goles1=$row['goles1'];
   $goles2=$row['goles2'];
   $comentario=$row['comentario'];

   $img_liga=$liga_obj->get_imagen($id_liga);

   if ($id_liga==0){
      $liga=$comentario;
   }else{
   	  $liga="";
      $query_liga="SELECT grupo_equipos FROM grupos_equipos WHERE id_grupo_equipos='$id_liga'";
//print "q=$query_liga<br>";
      $stmt2=$db->query($query_liga);
      $row_liga=$stmt2->fetch(PDO::FETCH_ASSOC);
      $liga=$row_liga['grupo_equipos'];
   }
   print "<tr>
             <td>$fecha
             <td style=\"text-align:left\"><img src=\"$img_liga\" style=\"max-width:45px; max-height:45px\"<td>$liga
             <td style=\"text-align:center\"><img src=\"".$eq->get_imagen($id_equipo1)."\" style=\"max-width:45px; max-height:45px\">".$eq->get_nombre($id_equipo1)."
             <td style=\"text-align:center\"><img src=\"".$eq->get_imagen($id_equipo2)."\" style=\"max-width:45px; max-height:45px\">".$eq->get_nombre($id_equipo2)."
             <td style=\"text-align:center\">$goles1 - $goles2";

}
print "</table></div>";



include 'includes/Close-Connection.php';
?>
</center>
