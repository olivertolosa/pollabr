<?
session_start();
include 'includes/_Policy.php';
include 'includes/class_pais.php';

?>

<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">

<table class="tabla_con_encabezado">
<thead>
<tr>
   <th class="hidden-xs">#<th>Pais<th>Bandera
</thead>
<tbody>
<?php

$query="SELECT id_pais FROM paises ORDER BY pais ASC";
//print "q=$query<br>";

$stmt = $db->query($query);
$cadena="";
$num=$stmt->rowCount();
//print "num=$num<br>";
$i=1;
while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
   $id_pais=$row['id_pais'];
   //$pais=$row['pais'];
   $pais_obj=new pais($id_pais); 
   print "<tr class=\"$class\">
           <td class=\"hidden-xs\">$i";
   print "        <td><a href=\"index.php?accion=pais_editar&id_pais=$id_pais\">".$pais_obj->pais;
   print "<td><img class=\"bandera\" src=\"".$pais_obj->get_imagen()."\">\n";

   $i++;
}

?>
</tbody>
</table>
</div>
</center>