<?
session_start();
?>
<script>
$(document).ready(function() {
$('tr[data-href]').on("click", function() {
    document.location = $(this).data('href');
});
});
</script>
<?

require_once 'includes/Open-Connection.php';
include 'audit.php';
include 'includes/class_equipo.php';
include 'includes/class_liga.php';

$eq_obj=new equipo($db);
$liga_obj=new liga($db);

$fecha=$_GET['fecha'];
$id_liga=$_GET['id_liga'];


print "<table class=\"tabla_con_encabezado\" style=\"font-size:11px\">
           <thead><th>Id<th>Fecha<th colspan=\"2\">Liga<th>Equipo1<th>Equipo2<th>Marcador";

$query="SELECT * FROM partidos2 WHERE fecha='$fecha'";

if ($id_liga!=-1) $query.=" AND id_liga='$id_liga'";
//print "<br>q=$query<br>";

$stmt=$db->query($query);
$num=$stmt->rowCount();

if ($num==0){	print "<tr><td colspan=\"7\" style=\"text-align:center\">No se encontraron partidos";
}else{
 while ($row=$stmt->fetch(PDO::FETCH_ASSOC)){
   $id_partido=$row['id_partido'];
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

   //validar si los dos equipos pertenecen a la misma liga....sino poner warning
   $id_liga1=$eq_obj->get_id_liga($id_equipo1);
   $id_liga2=$eq_obj->get_id_liga($id_equipo2);

   if ($id_liga1!=$id_liga or $id_liga2!=$id_liga or $id_liga1!=$id_liga2)
      $bg=";background-color:#ede5b1";
   else
      $bg="";

   print "<tr data-href=\"index.php?accion=editar_partido_historico&id_partido=$id_partido\" style=\"cursor:pointer $bg\">
             <td><a href=\"index.php?accion=editar_partido_historico&id_partido=$id_partido\" style=\"cursor:pointer\">$id_partido</a>
             <td>$fecha
             <td style=\"text-align:left;border-right-style: none;\"><img src=\"$img_liga\" style=\"max-width:45px; max-height:45px\"><td style=\"text-align:left;border-left-style: none;\">$liga
             <td style=\"text-align:center\"><img src=\"".$eq_obj->get_imagen($id_equipo1)."\" class=\"img_thumb_no_effect\">".$eq_obj->get_nombre($id_equipo1)."
             <td style=\"text-align:center\"><img src=\"".$eq_obj->get_imagen($id_equipo2)."\" class=\"img_thumb_no_effect\">".$eq_obj->get_nombre($id_equipo2)."
             <td style=\"text-align:center\">$goles1 - $goles2";
 }
}
print "</table>";
?>