<?
session_start();
include 'includes/_Policy.php';

require_once 'includes/class_evento.php';

$even=new evento($db);

if (isset($_POST['activo'])){
   $activo=$_POST['activo'];
   $publico=$_POST['publico'];
}else{	$activo=1;
	$publico=1;
}
?>
<center>
<form name="filtro" action="index.php?accion=evento_listar" method="POST">
<table class="tabla_simple">
   <tr><td>Público
       <td><SELECT name="publico" onchange="document.forms.filtro.submit();">
               <option value="-1"<? if ($publico==-1) print " SELECTED"; ?>>Cualquiera
               <option value="1"<? if ($publico==1) print " SELECTED"; ?>>Si
               <option value="0"<? if ($publico==0) print " SELECTED"; ?>>No
           </SELECT>
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
   <th>Id<th>Evento<th>Administrador
<?php


$query="SELECT e.id_evento,e.evento,u.usuario as usuario_admin
        FROM eventos as e, usuarios as u
        WHERE e.admin=u.id_usuario";
if ($activo==1)
    $query.=" AND e.activo='1'";
else if ($activo==0)
    $query.=" AND e.activo='0'";
if ($publico==1)
    $query.=" AND e.publica='1'";
else if ($publico==0)
    $query.=" AND e.publica='0'";
if (!$admin && $administra_polla)
   $query.=" AND e.admin=:id_usuario";

$query.=" ORDER BY evento ASC";
//print "q=$query<br>";
$stmt= $db->prepare($query,array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
if (!$admin && $administra_polla)
	$stmt->bindParam(':id_usuario',$id_usuario);
$stmt->execute();



if ($stmt->rowCount()==0){
   print "<tr><td colspan=\"3\"><center>No se encontraron eventos</center></td>\n";;
}else{
   while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
      $id_evento=$row['id_evento'];
      $evento=$row['evento'];
      $admin=$row['usuario_admin'];

      print "<tr><td width=\"40\">$id_evento
              <td><a href=\"index.php?accion=evento_admin&id_evento=$id_evento&accion2=parametros\">
              <img class=\"img_thumb\" src=\"".$even->get_imagen($id_evento)."\" style=\"width:40px; height:40px\"> $evento
              <td>$admin\n";
   }
}

?>
</table>
</center>