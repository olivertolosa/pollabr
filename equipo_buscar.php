<?
session_start();
$admin=$_SESSION['admin'];
$id_usuario=$_SESSION['usuario_polla'];

date_default_timezone_set ('America/Bogota');

$cadena=$_GET['cadena'];
$liga=$_GET['id_liga'];
$apuestad=$_GET['apuestad'];
$pos=$_GET['pos'];

?>
    <script type="text/javascript" src="includes/jquery.min.js"></script>
    <script src="includes/jquery.contextmenu.js"></script>
    <link rel="stylesheet" href="css/jquery.contextmenu.css">
    <script>
<? // https://github.com/joewalnes/jquery-simple-context-menu ?>
jQuery(function() {
    function CustomMenu1(i) {
        var v = jQuery(i);
        v.contextPopup({        	title: 'Estadisticas',
            items: [
                {label:'Tabla de posiciones',action:function() {
                                      $('#button').click();
                                      showDialog(v.attr('rel2'));
                } },
                {label:'Últimos Resultados',action:function() {
                                      $('#button').click();
                                      showDialog2(v.attr('rel'));
                } },
            ]
        });
    }
    jQuery('.divcontext').each(function(){
        CustomMenu1(this);
    });
});
    </script>

<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <script type="text/javascript">

    function showDialog(id_liga_par) {
    if (id_liga_par==0){
       alert ("si");
    }
        $('<div>').dialog({
            modal: true,
            open: function () {
                $(this).load('carga_tabla_posiciones.php?id_liga='+id_liga_par);
            },
            close: function(event, ui) {
                    $(this).remove();
                },
            height: 840,
            width: 540,
            title: 'Tabla de Posiciones',
            position: { my: 'top', at: 'top+50'},
        });

        return false;
    }

    </script>
    <script type="text/javascript">
    function showDialog2(id_equipo) {

        $('<div>').dialog({
            modal: true,
            position: { my: "top", at: "top+50", of: $('#cabeza') },
            open: function () {
                $(this).load('carga_ultimos_partidos.php?id_equipo='+id_equipo);
            },
            close: function(event, ui) {
                    $(this).remove();
                },
            height: 840,
            width: 540,
            title: 'Últimos Resultados',
        });

        return false;
    }

    </script>
<input type="button" style="display:none" id="button"></div>

<?





require 'includes/Open-Connection.php';

require_once 'includes/class_equipo.php';
$eq=new equipo($db);

if (isset($_GET['liga']) and $liga!=999){

      if (file_exists("imagenes/logos_ligas/".$liga.".png"))
         $extension=".png";
      else if (file_exists("imagenes/logos_ligas/".$liga.".PNG"))
         $extension=".PNG";
      else if (file_exists("imagenes/logos_ligas/".$liga.".jpg"))
          $extension=".jpg";
      else if (file_exists("imagenes/logos_ligas/".$liga.".JPG"))
         $extension=".JPG";
      else if (file_exists("imagenes/logos_ligas/".$liga.".gif"))
          $extension=".gif";
      else if (file_exists("imagenes/logos_ligas/".$liga.".GIF"))
         $extension=".GIF";
      else if (file_exists("imagenes/logos_ligas/".$liga.".bmp"))
          $extension=".bmp";
      else if (file_exists("imagenes/logos_ligas/".$liga.".BMP"))
          $extension=".BMP";

      $imagen=$liga.$extension;

?>


<img src="imagenes/logos_ligas/<?= $imagen ?>" style="max-width:180px; max-height:180px">
<br><br>
<?
}
?>
<table class="tabla_simple">
<tr>
<thead>
   <th>#<th colspan="2">Equipo<th>Escudo
</thead>
<?php

if (isset($cadena)){
   $query="SELECT * FROM equipos WHERE equipo LIKE '%$cadena%' OR equipoLS LIKE '%$cadena%' OR equipoLS2 LIKE '%$cadena%' OR equipoLS3 LIKE '%$cadena%' ORDER BY equipo ASC";
}else if (isset($liga)){   $query="SELECT * FROM equipos where id_grupo_equipos='$liga' ORDER BY equipo ASC";
}else if (isset($id_usuario_favoritos)){   $query="SELECT * FROM equipos where id_equipo IN(SELECT id_equipo from equipos_favoritos WHERE id_usuario='$id_usuario_favoritos') ORDER BY equipo";
}

$stmt = $db->query($query);
$cadena="";

$num=$stmt->rowCount();
if ($num==0)
   print "<tr><td colspan=\"3\">No se encontraron equipos\n";
else{	$i=1;
   while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
      $id_equipo=$row['id_equipo'];
      $equipo=$row['equipo'];
      $id_liga=$row['id_grupo_equipos'];

      $es_favorito=$eq->es_favorito($id_usuario,$id_equipo);
      if ($es_favorito){
          $img="favorito.png";
          $alt_img="favorito_del.png";
          $texto="Remover de favoritos";
      }else{
          $img="favorito_no.png";
          $alt_img="favorito_add.png";
          $texto="Agregar de favoritos";
      }

      ($i%2==0) ? $class="tabla-fila-par" : $class="tabla-fila-impar";
      print "<tr class=\"$class\"><td>$i
              <td><div id=\"fav_$id_equipo\" onclick=\"favorito($id_usuario,$id_equipo);\" style=\"onhover:cursor:pointer\">
               <img class=\"img_favorito\" src=\"imagenes/$img\" title=\"$texto\" onmouseover=\"this.src='imagenes/$alt_img';\" onmouseout=\"this.src='imagenes/$img';\"></div>
               <td>";

      if ($apuestad){
          $equipo2=htmlspecialchars($equipo,ENT_QUOTES);
          print "<a href=\"javascript:sel_equipo($id_equipo,'$equipo2','".$eq->get_imagen($id_equipo)."',$pos)\">$equipo</a>";
      }else if ($admin)
          print "<a href=\"index.php?accion=editar_equipo&id_equipo=$id_equipo\">$equipo</a>";
      else
           print "$equipo";
      print "<td style=\"text-align: center;\"><div class=\"divcontext\" rel=\"$id_equipo\" rel2=\"$id_liga\"><img ";
         if (!$apuestad) print "class=\"img_thumb\"";
      print " src=\"".$eq->get_imagen($id_equipo)."\" style=\"max-width:65px; max-height:65px;cursor: url('../polla/imagenes/balon.ani'),pointer;\" id=\"img$id_equipo\"></div>\n";
      $i++;
   }
}

print "</table>";

?>

</center>

