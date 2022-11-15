<?php

require 'includes/Open-Connection.php';
include 'includes/class_equipo.php';

$id_punta=$_POST['id_punta'];
$equipo=new equipo($db);

if (substr($id_punta,0,1)=='c' or substr($id_punta,0,1)=='v'){	$id_equipo=substr($id_punta,2);
	$compraventa=substr($id_punta,0,1);

}else{
   $query="SELECT * FROM bolsa_puntas WHERE id_punta='$id_punta'";
   $stmt = $db->query($query);
   if ($stmt->rowCount()==0){      $resp['nombre_equipo']="-";
   }else{      $row=$stmt->fetch(PDO::FETCH_ASSOC);
      $id_equipo=$row['id_equipo'];
      $compraventa=$row['compraventa'];
      //nombre del equipo
   }

}



$nombre=$equipo->get_nombre($id_equipo);


//devolver en JSON
$resp['nombre']=utf8_encode($nombre);
$resp['compraventa']=$compraventa;

print json_encode($resp);

require 'includes/Close-Connection.php';
?>
