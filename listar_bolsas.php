<?
session_start();
include 'includes/_Policy.php';

require_once 'includes/class_bolsa.php';

$bolsa=new bolsa($db);

if (isset($_POST['activo'])){
   $activo=$_POST['activo'];
   $publico=$_POST['publico'];
}else{	$activo=1;
	$publico=1;
}
?>
<center>
<form name="filtro" action="index.php?accion=bolsa_listar" method="POST">
<table class="tabla_simple">
   <tr><td>Activo
       <td><SELECT name="activo" onchange="document.forms.filtro.submit();">
               <option value="-1"<? if ($activo==-1) print " SELECTED"; ?>>Cualquiera
               <option value="1"<? if ($activo==1) print " SELECTED"; ?>>Si
               <option value="0"<? if ($activo==0) print " SELECTED"; ?>>No
           </SELECT>
</table>
</center>
</form>
<br>
<center>
<table class="tabla_con_encabezado">
<tr>
   <th>Id<th>Bolsa
<?php


$query="SELECT id_bolsa,nombre_bolsa
        FROM bolsas";
if ($activo==1)
    $query.=" WHERE activo='1'";
else if ($activo==0)
    $query.=" WHERE activo='0'";

$query.=" ORDER BY nombre_bolsa ASC";
//print "q=$query<br>";
$stmt = $db->query($query);



if ($stmt->rowCount()==0){
   print "<tr><td colspan=\"2\"><center>No se encontraron bolsas</center></td>\n";;
}else{
   while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
      $id_bolsa=$row['id_bolsa'];
      $nombre=$row['nombre_bolsa'];

      print "<tr><td width=\"40\">$id_bolsa
              <td><a href=\"index.php?accion=bolsa_admin&id_bolsa=$id_bolsa&accion2=parametros\">
              <img class=\"img_thumb\" src=\"".$bolsa->get_imagen($id_bolsa)."\" style=\"width:40px; height:40px\"> $nombre";
   }
}

?>
</table>
</center>