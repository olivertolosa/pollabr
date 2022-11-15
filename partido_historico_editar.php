<?
session_start();
include 'includes/_Policy.php';
require_once 'includes/class_equipo.php';
require_once 'includes/class_liga.php';


$liga_obj=new liga($db);
$eq_obj=new equipo($db);


$id_partido=$_REQUEST['id_partido'];


$query="SELECT * FROM partidos2 WHERE id_partido='$id_partido'";
$stmt=$db->query($query);
$row=$stmt->fetch(PDO::FETCH_ASSOC);
$num=$stmt->rowCount();
//print "num=$num<br>";

$id_equipo1=$row['id_equipo1'];
$id_equipo2=$row['id_equipo2'];
$fecha=$row['fecha'];
$id_liga=$row['id_liga'];
$goles1=$row['goles1'];
$goles2=$row['goles2'];
$comentario=$row['comentario'];

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

$img_liga=$liga_obj->get_imagen($id_liga);

?>

<form name="mod_partido" action="partido_historico_procesar.php" method="POST">
<center>
<?
echo $_SESSION['msg'];
print "<br><br>";
unset($_SESSION['msg']);
?>

<table class="tabla_simple">

<?php

   print "<tr><th>Id<td colspan=\"2\">$id_partido
          <tr><th>Liga<td style=\"text-align:left;border-right-style: none;\"><img src=\"$img_liga\" style=\"max-width:45px; max-height:45px\"><td style=\"text-align:left;border-left-style: none;\">$liga";
?><tr><th>Cambiar Liga
    <td colspan="2"><SELECT id="id_liga" name="id_liga" title="Selecciona una liga">
   <option value="0">Seleccione una liga</option>
<? if ($admin){ ?>
      <option value="-1"<? if ($grupo_equipos==-1) print " SELECTED"; ?>>Todos</option>
<?
}
   $query="SELECT * FROM grupos_equipos ORDER BY grupo_equipos ASC";
   foreach($db->query($query) as $row) {
   	   $id_liga2=$row['id_grupo_equipos'];
   	   $liga=$row['grupo_equipos'];
   	   print "<option value=\"$id_liga2\"";
   	   if ($id_liga==$id_liga2) print " SELECTED";
   	   print ">$liga</option>\n";
   }

?>
</SELECT>
<?
   print "       <tr><th>Equipos

             <td style=\"text-align:center;\"><img src=\"".$eq_obj->get_imagen($id_equipo1)."\" style=\"max-width:45px; max-height:45px\">".$eq_obj->get_nombre($id_equipo1)
             ."<td style=\"text-align:center;\"><img src=\"".$eq_obj->get_imagen($id_equipo2)."\" style=\"max-width:45px; max-height:45px\">".$eq_obj->get_nombre($id_equipo2);

   print"<tr><th>Fecha<td colspan=\"2\"><input type=\"date\" name=\"fecha\" value=\"$fecha\" required>\n";

   print "<tr><th>Marcador<td colspan=\"2\" style=\"text-align:center\"><input type=\"number\" name=\"goles1\" value=\"$goles1\"> - <input type=\"number\" name=\"goles2\" value=\"$goles2\">\n";

?>
   <input type="hidden" name="id_partido" value="<?= $id_partido ?>">
</table>
<br>
<input type="submit" value="Modificar">
</form>
</center>

