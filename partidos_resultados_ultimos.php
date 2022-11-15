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
<tr>
   <th colspan="3">Últimos Resultados
<?php

require_once 'includes/class_equipo.php';
require_once 'includes/class_partido.php';
$eq=new equipo($db);   //objeto geenérico
$partido=new partido($db);

$hora=date('H:m:s');
$fecha=date('Y-m-d');
//tabla con partidos mas próximos
$query="SELECT TOP 10 id_equipo1,id_equipo2,fecha,hora,goles1,goles2
        FROM partidos
        WHERE fecha <= '$hoy'
        AND goles1>-1

		order by fecha DESC,hora DESC";
        /*UNION DISTINCT
        SELECT id_equipo1,id_equipo2,fecha,hora,goles1,goles2
        FROM apuesta_directa
        WHERE fecha <= '$hoy'
        AND goles1>-1
        order by fecha DESC,hora DESC limit 0,10";*/


/*$query="SELECT id_partido,id_equipo1,id_equipo2,fecha,goles1,goles2 FROM partidos WHERE fecha <= '$hoy'
AND goles1>-1
AND id_partido IN
 (
SELECT MIN(id_partido) FROM partidos GROUP BY id_equipo1,id_equipo2,fecha
)
order by fecha DESC,hora DESC limit 0,10";*/


//print "q=$query<br>";


foreach($db->query($query) as $row){
    $id_equipo1=$row['id_equipo1'];
    $id_equipo2=$row['id_equipo2'];
    $goles1=$row['goles1'];
    $goles2=$row['goles2'];
    $fecha=$row['fecha'];


   print "<tr";
   if  ($partido->en_progreso($id_partido)){   	   print " style=\"background: rgba(0,210,40,0.7);\" title=\"En Progreso\"";
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
</table>