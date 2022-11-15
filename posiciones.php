<?
///validar que el usuario si haga parte del evento
include 'seguridad.php';

require_once 'includes/class_usuario.php';



?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
<script type="text/javascript">

function showDialog() {

    $('<div>').dialog({
        modal: true,
        open: function () {
            $(this).load('evento_pos_hist.php?id_evento=<?php echo $id_evento; ?>&id_usuario=<?php echo $id_usuario; ?>');
        },
        close: function(event, ui) {
                $(this).remove();
            },
        height: 580,
        width: 680,
        title: 'Mi Historia',
        position: { my: 'top', at: 'top+50' },
    });

    return false;
}



</script>

<!--<div id="mystats" style="text-align:left;width:100%;overflow:auto;cursor: pointer;" onclick="showDialog(<? echo $id_usuario ?>)">
<img src="imagenes/stats_lines.png" style="max-width:30px; max-height:30px;float:left; "> <span class="titulo_medio" style="padding: 00px 10px;">Mi historia</span>-->
</div>

    <h2>Tabla de Posiciones</h2>
<div class="table-responsive">
<table class="tabla_con_encabezado table table-condensed">
<thead>
<tr><th>Posición
    <th width="200">Usuario
    <th align="middle">Puntos
    <th align="middle"><? ($mobile) ? print "ME" : print "Marcador<br>Exacto"; ?>
    <th align="middle"><? ($mobile) ? print "G/E" : print "Ganador /<br> Empate"; ?>
    <th align="middle"><? ($mobile) ? print "M1" : print "Marcador 1"; ?>
</thead>
<tbody>
<?


//cuantos usuarios se deben premiar
$query="SELECT top_premios FROM eventos WHERE id_evento=:id_evento";
$stmt= $db->prepare($query);
$stmt->bindParam(':id_evento',$id_evento);
$stmt->execute();
$row=$stmt->fetch(PDO::FETCH_ASSOC);
$top_premios=$row['top_premios'];


$query="SELECT 	u.id_usuario,u.usuario,u.nombre,uxe.puntos,uxe.marcadores_exactos,uxe.ganadorempate,uxe.marcador1
        FROM usuarios as u, usuariosxevento as uxe
        WHERE u.id_usuario=uxe.id_usuario AND uxe.id_evento=:id_evento";

//print "q=$query<br>";

//validar si el evento requiere validar los usuarios
$queryv="SELECT conf_usuarios FROM eventos WHERE id_evento=:id_evento";
$stmtv= $db->prepare($queryv);
$stmtv->bindParam(':id_evento',$id_evento);
$stmtv->execute();
$rowv=$stmtv->fetch(PDO::FETCH_ASSOC);
if ($rowv['conf_usuarios'])
   $query.=" AND uxe.validado='1'";

$query.=" ORDER BY uxe.puntos DESC,uxe.marcadores_exactos DESC,uxe.ganadorempate DESC,uxe.marcador1 DESC";

//print "<br>q=$query<br>";
$stmt= $db->prepare($query);
$stmt->bindParam(':id_evento',$id_evento);
$stmt->execute();

//obtener número de participantes
$num_participantes=$stmt->rowCount();
$mitad=ceil($num_participantes/2);

$i=1;
if ($num_participantes==0)
   print "<tr><td colspan=\"6\" style=\"text-align: center;\">No se encontraron participantes";
while ($row=$stmt->fetch(PDO::FETCH_ASSOC)){   $texto_mitad="";
   $id_user=$row['id_usuario'];
   $usuarioobj=new usuario($id_user);
   $usuario=$usuarioobj->usuario;
   $nombre=$usuarioobj->nombre;
   $puntos=$row['puntos'];
   $ganadorempate=$row['ganadorempate'];
   $marcadores_exactos=$row['marcadores_exactos'];
   $marcador1=$row['marcador1'];
   //poner el color de fondo en la fila de los ganadores y del usuario
   if ($i<=$top_premios) $class="fila-con-premio";
   else if ($id_usuario==$id_user) $class="fila-usuario";
   else ($i%2==0) ? $class="tabla-fila-par" : $class="tabla-fila-impar";




      $img=$usuarioobj->get_imagen($id_user);


print "<tr class=\"$class\"><td style=\"vertical-align: middle; text-align:center\">$i";
print "<td style=\"vertical-align: middle;\"><img class=\"img_thumb\" style=\"max-width:75px;max-height:75px\" src=\"".$usuarioobj->get_imagen($id_user)."\"> <a href=\"index.php?accion=resultados&id_evento=$id_evento&id_usuario=$id_user\">$usuario</a>\n";
print "<td style=\"vertical-align: middle; text-align:center\">$puntos
       <td style=\"vertical-align: middle; text-align:center\">$marcadores_exactos
       <td style=\"vertical-align: middle; text-align:center\">$ganadorempate
       <td style=\"vertical-align: middle; text-align:center\">$marcador1\n";

   $i++;
}

?>
</tbody>
</table>
</div><!-- Div table responsive -->
</center>
<br>


<?
if ($mobile){   print "ME : Marcador Exacto<br>
          G/E : Ganador / Empate<br>
          M1 : Marcador 1";
}


?>

<script>
// Using multiple unit types within one animation.

$( document ).ready(function() {
  $( "#mystats" ).animate({
    width: "95%",
  }, 100 );

  $( "#mystats" ).delay(50).animate({
      width: "90%",
  }, 100 );
  $( "#mystats" ).delay(50).animate({
	width: "85%",
  }, 100 );
  $( "#mystats" ).delay(50).animate({
	width: "90%",
  }, 100 );
  $( "#mystats" ).delay(50).animate({
	width: "95%",
  }, 100 );
  $( "#mystats" ).delay(50).animate({
    width: "100%",
	backgroundColor: '#FFFFFF',
  }, 100 );
});
</script>
