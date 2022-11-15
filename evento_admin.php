<?
//print "id_evento iniciando evento_admin=$id_evento";

if (!$cron){//si no se está ejecutando el cron...validar credenciales
   //validar si el usuario es administrador del evento
   $query="SELECT admin,evento FROM eventos WHERE id_evento=:id_evento";
   $stmt= $db->prepare($query);
	$stmt->bindParam(':id_evento',$id_evento);
	$stmt->execute();

   $row=$stmt->fetch(PDO::FETCH_ASSOC);
   $admin_evento=$row['admin'];
   $evento=$row['evento'];
   if ($admin_evento!=$id_usuario && !$admin){      print "<center><span class=\"msg_error\">Acceso No Autorizado!!!!</span></center>";
      exit();
   }
}

//validar si el usuario es usuario del evento
$query="SELECT * FROM usuariosxevento WHERE id_evento=:id_evento AND id_usuario=:id_usuario";
   $stmt= $db->prepare($query,array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt->bindParam(':id_evento',$id_evento);
	$stmt->bindParam(':id_usuario',$id_usuario);
	$stmt->execute();

$usuario_evento=false;
if ($stmt->rowCount()==1)
   $usuario_evento=true;


/*if ($id_usuario!=$admin_evento && !$admin){   print "<center><h1>Acceso no autorizado</h1></center>";
   exit();
} */

?>
<center>
<?if (isset($_SESSION['msg']) && $accion=="evento_admin"){
   echo $_SESSION['msg'];
   unset ($_SESSION['msg']);
}
?>
<table border="0">
<tr>
   <td style="vertical-align:text-top;"><center>
<?
//print "accion=$accion2<br>";
if ($accion2=="listar_partidos" && $admin_evento){
    include 'listar_partidos.php';
}else if ($accion2=="listar_usuarios" && $admin_evento){
    include 'listar_usuariosxevento.php';
}else if ($accion2=="parametros" && $admin_evento){
    include 'evento_detalle.php';
}else if ($accion2=="equiposxevento" && $admin_evento){
    include 'equiposXevento.php';
}else if ($accion2=="partido_nuevo" && $admin_evento){
    include 'partido_nuevo.php';
}else if ($accion2=="editar_partido" && $admin_evento){
    include 'partido_detalle.php';
}else if ($accion2=="editar_usuario" && $admin_evento){
    include 'usuario_detalle_evento.php';
}else if ($accion2=="actualizar_marcadores" && $admin_evento){
    include 'marcadores.php';
}else if ($accion2=="actualizar_resultados" && $admin_evento){
    include 'genera_posiciones.php';
}else if ($accion2=="sin_apuesta" && $admin_evento){    include 'usuarios_sin_apuesta.php';
//}else if ($accion2=="invitar" && $admin_evento){
//    include 'evento_invitar.php';
}else if ($accion2=="impersonar" && $admin_evento){
    include 'evento_impersonar.php';
}else if ($accion2=="notificaciones" && $admin_evento){
    include 'evento_notificaciones.php';
}else if ($accion2=="grupos" && $admin_evento){
    include 'grupos.php';
}


?>
</center>
</table>
</center>