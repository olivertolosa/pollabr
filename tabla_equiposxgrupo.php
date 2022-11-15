<?php
$grupo=$_GET['grupo'];
$id_evento=$_GET['id_evento'];
$id_bolsa=$_GET['id_bolsa'];
$num_ronda=$_GET['num_ronda'];
include 'includes/Open-Connection.php';

include 'includes/class_equipo.php';
$eq=new equipo($db);


   print "<table class=\"tabla_simple\" style=\"display: block; max-height: 500px; overflow-y: scroll;width:280;\">\n";

if (isset($_GET['id_evento'])){
  $query="SELECT id_equipo,equipo FROM equipos
           WHERE id_equipo IN
           ( SELECT id_equipo FROM gruposxevento WHERE id_evento=:id_evento AND grupo=:grupo ANd num_ronda=:num_ronda)";
}else if (isset($_GET['id_bolsa'])){	$grupo=$_GET['grupo'];  $query="SELECT id_equipo,equipo FROM equipos
           WHERE id_equipo IN
           (SELECT DISTINCT id_equipo FROM bolsa_acciones WHERE id_bolsa=:id_bolsa)
           AND id_equipo NOT IN (SELECT DISTINCT id_equipo FROM bolsa_paquetes WHERE id_bolsa=:id_bolsa ANd id_paquete=:grupo)";

}
  $query.="ORDER BY equipo ASC";

	$stmt= $db->prepare($query);
	if (isset($_GET['id_evento'])){
		$stmt->bindParam(':id_evento',$id_evento);
		$stmt->bindParam(':grupo',$grupo);
		$stmt->bindParam(':num_ronda',$num_ronda);
	}else if (isset($_GET['id_bolsa'])){		$stmt->bindParam(':id_bolsa',$id_bolsa);
		$stmt->bindParam(':grupo',$grupo);
	}
	$stmt->execute();


	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {{
       $id_equipo=$row['id_equipo'];
       $equipo=$row['equipo'];
       $equipo=$equipo;

       print "<tr ><td style=\"vertical-align:middle;\"><span class=\"lista_equipos\"><a href=\"javascript:inc_equipo($id_equipo,1)\">
                   <img style=\"vertical-align:middle\" src=\"".$eq->get_imagen($id_equipo)."\" width=\"40\" height=\"40\">&nbsp;&nbsp;$equipo</a></span></td>\n";
    }
   print "</table>";

include 'includes/Close-Connection.php';
?>
