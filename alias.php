<?php
//include 'includes/Open-Connection.php';
require_once 'includes/class_liga.php';

$liga_obj=new liga($db);

/*$query="SELECT id_partido, comentario FROM partidos2 WHERE comentario LIKE '%::%'";

print "q=$query<br>";

$stmt = $db->query($query);

$num_partidos=$stmt->rowCount();

print "Procesando $num_partidos partidos<br><br>";


while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
   $id_partido=$row['id_partido'];
   $comentario=$row['comentario'];

   $comentario=substr($comentario,0,strpos($comentario,':'));

   $query2="UPDATE partidos2 SET comentario='$comentario' WHERE id_partido='$id_partido'";
   $stmt2 = $db->query($query2);
   print "q2=$query2<br>";
}
          */

//corregir los ids si aplica
$query="SELECT id_partido, comentario FROM partidos2 WHERE id_liga='0'";

$stmt = $db->query($query);
$num_partidos=$stmt->rowCount();

print "Procesando $num_partidos partidos<br><br>";

while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
   $id_partido=$row['id_partido'];
   $comentario=$row['comentario'];

   //validar si la competencia tiene un alias
   $query_alias="SELECT * FROM traducciones WHERE original='$comentario'";
   $stmt_alias = $db->query($query_alias);
   if ($stmt_alias->rowCount()>0){
      $row_alias=$stmt_alias->fetch(PDO::FETCH_ASSOC);
      $id_competencia=$row_alias['id_liga'];
      $comentario=$row_alias['traducido'];

      print "liga=$id_competencia<br>";

      if ($id_competencia>0){         //cambiar el nombre x el oficial
         $comentario=$liga_obj->get_nombre($id_competencia);
      }

      $query2="update partidos2 SET id_liga='$id_competencia',comentario='$comentario' WHERE id_partido='$id_partido'";
      $stmt2 = $db->query($query2);
      print "q2=$query2<br>";

   }

}

//include 'includes/Close-Connection.php';
?>
