<?
session_start();
include 'includes/_Policy.php';


$id_partido=$_REQUEST['id_partido'];
include 'includes/Open-Connection.php';


//averiguar el partido de que evento era
$query="SELECT id_evento,tipo_e FROM partidos WHERE id_partido=:id_partido";
$stmt= $db->prepare($query);
$stmt->bindParam(':id_partido',$id_partido);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$id_evento=$row['id_evento'];
$tipo_e=$row['tipo_e'];

if ($tipo_e=='b'){
   $redirect="index.php?accion=bolsa_admin&id_bolsa=$id_evento&accion2=listar_partidos";
}else if($tipo_e=='e'){
   $redirect="index.php?accion=evento_admin&id_evento=$id_evento&accion2=listar_partidos";
}



//validar que el usuario es administrador del evento o es super admin
$id_usuario=$_SESSION['usuario_polla'];

$query="SELECT admin FROM eventos WHERE id_evento=:id_evento";
$stmt= $db->prepare($query);
$stmt->bindParam(':id_evento',$id_evento);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);$admin=$row['admin'];

if ($admin!=$id_usuario){  //no es el admin del evento    $query="SELECT id_usuario FROM administradores WHERE id_usuario=:id_usuario";
    $stmt= $db->prepare($query,array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt->bindParam(':id_usuario',$id_usuario);
	$stmt->execute();
    if ($stmt->rowCount()==0){ //tampoco es super admin    	$redirect="index.php";
        header('Location: '.$redirect);
    }
}




//eliminar el partido
$query="DELETE FROM partidos WHERE id_partido=:id_partido";
$stmt= $db->prepare($query);
$stmt->bindParam(':id_partido',$id_partido);
$stmt->execute();

//eliminar las apuestas relacionadas con el partido
$query="DELETE FROM apuestas WHERE id_partido=:id_partido";
$stmt= $db->prepare($query);
$stmt->bindParam(':id_partido',$id_partido);
$stmt->execute();
$_SESSION['msg']="<span class=\"msg_ok\">Partido Eliminado</span>";


//Borrar las apuestas de los partidos_clon
$query="SELECT id_partido FROM partidos_clon WHERE id_partido_original=:id_partido";
$stmt= $db->prepare($query);
$stmt->bindParam(':id_partido',$id_partido);
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {	$id_partido_clon=$row['id_partido'];
	$query2="DELETE FROM partidos WHERE id_partido=:id_partido_clon";
	$stmt2= $db->prepare($query2);
	$stmt2->bindParam(':id_partido_clon',$id_partido_clon);
	$stmt2->execute();
}


//borrar los partidos clon
$query="DELETE FROM partidos_clon WHERE id_partido_original=:id_partido";
$stmt= $db->prepare($query);
$stmt->bindParam(':id_partido',$id_partido);
$stmt->execute();





include 'includes/Close-Connection.php';

if (!headers_sent() && $msg == '') {

      header('Location: '.$redirect);
}


?>

</body>

</html>