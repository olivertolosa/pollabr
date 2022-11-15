<?
session_start();

require_once 'includes/Open-Connection.php';
include 'audit.php';
include 'includes/class_equipo.php';
include 'includes/class_liga.php';

$eq=new equipo($db);
$liga_obj=new liga($db);

$id_equipo=$_GET['id_equipo'];
$contrario=$_GET['contrario'];
$liga=$_GET['liga'];

$liga=urldecode($liga);


print "<table class=\"tabla_con_encabezado\" style=\"font-size:11px\">
           <thead><th>Fecha<th colspan=\"2\">Liga<th>Equipo1<th>Equipo2<th>Marcador";

$query="SELECT * FROM partidos2 WHERE ";
if ($contrario!=0){
   $query.="((id_equipo1='$id_equipo' AND id_equipo2='$contrario') OR (id_equipo2='$id_equipo' AND id_equipo1='$contrario'))";
}else{   $query.="(id_equipo1='$id_equipo' OR id_equipo2='$id_equipo')";
}

if ($liga!='0'){   $query.=" AND comentario='$liga'";
}


$query.=" ORDER BY fecha DESC LIMIT 0,10";
//print "<br>q=$query<br>";

$stmt=$db->query($query);
$num=$stmt->rowCount();

if ($num==0){	print "<tr><td colspan=\"5\" style=\"text-align:center\">No se encontraron partidos";
}else{
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
             <td style=\"text-align:left;border-right-style: none;\"><img src=\"$img_liga\" style=\"max-width:45px; max-height:45px\"><td style=\"text-align:left;border-left-style: none;\">$liga
             <td style=\"text-align:center\"><img src=\"".$eq->get_imagen($id_equipo1)."\" style=\"max-width:45px; max-height:45px\">".$eq->get_nombre($id_equipo1)."
             <td style=\"text-align:center\"><img src=\"".$eq->get_imagen($id_equipo2)."\" style=\"max-width:45px; max-height:45px\">".$eq->get_nombre($id_equipo2)."
             <td style=\"text-align:center\">$goles1 - $goles2";
 }
}
print "</table>";
?>