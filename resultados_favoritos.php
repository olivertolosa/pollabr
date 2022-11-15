<style type="text/css">
.container_vs {
   position: relative;
}

.background_vs {
   position: absolute;
   top: -5;
   left: 0;
   bottom: 0;
   right: 0;
   z-index: 5;
   font-size: 35px;
   overflow: hidden;
   opacity: 0.1;
   font-weight:bold;
}
</style>
<table class="tabla_simple_pequena">
<tbody>
<tr>
   <th colspan="3">Resultados de Equipos favoritos
<?php

require_once 'includes/class_equipo.php';
require_once 'includes/class_partido.php';
$eq=new equipo($db);   //objeto geenérico
$partido=new partido($db);

//tabla con partidos mas próximos
$query="SELECT id_equipo1,id_equipo2,goles1,goles2,fecha
        FROM partidos2
        WHERE fecha <CURDATE()
        AND ((id_equipo1 IN (SELECT id_equipo FROM equipos_favoritos WHERE id_usuario='$id_usuario')) OR (id_equipo2 IN (SELECT id_equipo FROM equipos_favoritos WHERE id_usuario='$id_usuario')))
         group by id_equipo1, id_equipo2 ORDER BY fecha DESC LIMIT 0,$max_resultados_favoritos";

//print "query=$query<br>";  exit();
$stmt = $db->query($query);

if ($stmt->rowcount()==0){   print "<tr><td style=\"text-align:center\"> No hay partidos";
}


foreach($db->query($query) as $row){
    $id_equipo1=$row['id_equipo1'];
    $id_equipo2=$row['id_equipo2'];
    $goles1=$row['goles1'];
    $goles2=$row['goles2'];
    $fecha=$row['fecha'];


   print "<tr";
   if  ($partido->en_progreso($id_partido)){
   	   print " style=\"background: rgba(0,210,40,0.7);\" title=\"En Progreso\"";
   }
   print "   >
         <td style=\"text-align:center;border:0px;\"><img class=\"img_thumb\" src=\"".$eq->get_imagen($id_equipo1)."\" width=\"45\" height=\"45\" title=\"".$eq->get_nombre($id_equipo1)."\">
         <td style=\"text-align:center;border:0px;\"><div class=\"container_vs\"><div class=\"background_vs\">Vs</div>  $fecha
           <br>
           $goles1&nbsp;&nbsp;-&nbsp;&nbsp;$goles2</div>
         <td style=\"text-align:center;border:0px;\">
           <img class=\"img_thumb\" src=\"".$eq->get_imagen($id_equipo2)."\" width=\"45\" height=\"45\" title=\"".$eq->get_nombre($id_equipo2)."\">";

}

?>
</tbody>
</table>