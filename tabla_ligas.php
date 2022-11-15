<table class="tabla_simple">
<tr>
   <th>Id<th colspan="2">Liga<th>Validado
<?php

require_once 'includes/Open-Connection.php';
require_once 'includes/class_liga.php';
require_once 'config.php';

$ligaobj=new liga($db);

if (isset($_REQUEST['liga']))
   $cad_query="WHERE grupo_equipos LIKE '%".$_REQUEST['liga']."%'";

$query="SELECT * FROM grupos_equipos $cad_query";
$query.=" ORDER BY grupo_equipos ASC";
$stmt = $db->query($query);
$cadena="";
if ($stmt->rowCount()==0){
   print "<tr><td colspan=\"2\"><center>No se encontraron grupos</center></td>\n";;
}else{
   $i=1;
   while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
      $id_grupo_equipos=$row['id_grupo_equipos'];
      $grupo_equipos=$row['grupo_equipos'];

    $logo=$ligaobj->get_imagen($id_grupo_equipos);


    //validar si se pone el chulito debido a que se validó en los últimos X dias (archivo config)
    if ($row['link_LS']=="N/A"){
		$img="undefined.png";
	}else{
	   $last_check=strtotime($row['last_check']);
       $hoy=strtotime(date("Y-m-d"));
       $datediff = $hoy - $last_check;
       $datediff=floor($datediff / (60 * 60 * 24));


       if ($datediff<$max_dias_check){
          $img="ok.png";
       }else{
          $img="pregunta.png";
       }
	}   

      ($i%2==0) ? $class="tabla-fila-par" : $class="tabla-fila-impar";
      print "<tr class=\"$class\"><td width=\"40\">$id_grupo_equipos
              <td><img src=\"$logo\" style=\"max-width:65px;max-height:65px\" class=\"img_thumb\">
              <td>&nbsp;&nbsp;<a href=\"index.php?accion=grupo-equipos_detalle&id_grupo=$id_grupo_equipos\">$grupo_equipos</a></td>
              <td><center><div id=\"check-$id_grupo_equipos\" class=\"check_liga\" style=\"cursor:pointer\"><img src=\"imagenes/$img\" style=\"max-width:30px;max-height:30px;\"></div></center>\n";
       $i++;
   }
}
?>
</table>
<script type="text/javascript">
	$(document).ready(function() {
		$('.check_liga').click(function(event) {
             var liga = jQuery(this).attr("id");
		     liga=liga.substr(6);
		     //alert ("id:"+liga);
             $(document).ajaxStart(function(){
                     $("#loadingdiv").css("display","block");
                  });
             $(document).ajaxComplete(function(){
                     $("#loadingdiv").css("display","none");
                  });
             $("#check-"+liga).load("grupo_equipo_validar_ajax.php?id_liga="+liga);
        });
	});
</script>
