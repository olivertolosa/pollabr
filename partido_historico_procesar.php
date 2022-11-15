<?php


session_start();
include 'includes/_Policy.php';
include 'includes/class_liga.php';
require_once 'audit.php';
audit_max();

include 'includes/Open-Connection.php';

$liga_obj=new liga($db);


$id_partido=$_POST['id_partido'];
//$id_equipo1=$_POST['eq1'];
//$id_equipo2=$_POST['eq2'];
$fecha=$_POST['fecha'];
$goles1=$_POST['goles1'];
$goles2=$_POST['goles2'];
$id_liga=$_POST['id_liga'];

if ($id_liga!=0){   	$comentario=$liga_obj->get_nombre($id_liga);
}

$query="UPDATE partidos2 SET fecha='$fecha',id_liga='$id_liga',goles1='$goles1',goles2='$goles2'";
if ($id_liga!=0) $query.=",comentario='$comentario'";
$query.=" WHERE id_partido='$id_partido'";

$stmt=$db->query($query);
//print "q=$query<br>";

$redirect="index.php?accion=editar_partido_historico&id_partido=$id_partido";


$_SESSION['msg']="<span class=\"msg_ok\">Partido Modificado</span>";
include 'includes/Close-Connection.php';

if (!headers_sent() && $msg == '') {

      header('Location: '.$redirect);
}


?>

</body>

</html>