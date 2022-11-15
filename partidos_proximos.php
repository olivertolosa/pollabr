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
   <th colspan="3">Próximos Partidos
<?php

require_once 'includes/class_equipo.php';
$eq=new equipo($db);   //objeto geenérico

//tabla con partidos mas próximos
/*$query="SELECT id_equipo1,id_equipo2,fecha,hora
        FROM partidos
        WHERE fecha >CURDATE() OR (fecha=CURDATE() AND hora>DATE_SUB(NOW(), INTERVAL 1 HOUR)) group by id_equipo1, id_equipo2
        UNION
        SELECT id_equipo1,id_equipo2,fecha,hora
        FROM apuesta_directa
        WHERE fecha >CURDATE() OR (fecha=CURDATE() AND hora>DATE_SUB(NOW(), INTERVAL 1 HOUR)) group by id_equipo1, id_equipo2
        ORDER BY fecha ASC, hora ASC LIMIT 0,10";*/

        $hora=date('H:m:s');
        $fecha=date('Y-m-d');

$query="SELECT TOP 10 id_equipo1,id_equipo2,fecha,hora
        FROM partidos
        WHERE fecha >'$fecha' OR (fecha='$fecha' AND hora>'$hora')
        UNION
        SELECT id_equipo1,id_equipo2,fecha,hora
        FROM apuesta_directa
        WHERE fecha >'$fecha' OR (fecha='$fecha' AND hora>'$hora')
        ORDER BY fecha ASC, hora ASC";
//print "query=$query<br>";  exit();
$stmt = $db->query($query);

if ($stmt->rowcount()==0){   print "<tr><td style=\"text-align:center\"> No hay partidos";
}


while ($row=$stmt->fetch(PDO::FETCH_ASSOC)){
    $id_partido=$row['id_partido'];
    $id_equipo1=$row['id_equipo1'];
    $id_equipo2=$row['id_equipo2'];
    $fecha=$row['fecha'];
    $hora=$row['hora'];
    $hora=substr($hora,0,5);

   print "<tr>
         <td style=\"text-align:center;border:0px;\"><img class=\"img_thumb\" src=\"".$eq->get_imagen($id_equipo1)."\" width=\"45\" height=\"45\" title=\"".$eq->get_nombre($id_equipo1)."\">
         <td style=\"text-align:center;border:0px;\"><div class=\"container_vs\"><div class=\"background_vs\">Vs</div>$fecha<br>$hora</div>
         <td style=\"text-align:center;border:0px;\">
           <img class=\"img_thumb\" src=\"".$eq->get_imagen($id_equipo2)."\" width=\"45\" height=\"45\" title=\"".$eq->get_nombre($id_equipo2)."\">";
}


?>
</tbody>
</table>