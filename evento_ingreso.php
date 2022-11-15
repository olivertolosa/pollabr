<?
if (!isset($id_evento))
   $id_evento=$_REQUEST['id_evento'];


$query="SELECT e.evento,e.descripcion,e.publica,u.usuario,e.valor,e.top_premios,e.tipo_premios,e.porcentaje_premios
          FROM eventos as e, usuarios as u
          WHERE id_evento='$id_evento' and e.admin=u.id_usuario";
//print "q=$query<br>";
$stmt= $db->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$evento=$row['evento'];
$admin=$row['usuario'];
$descripcion=$row['descripcion'];
$valor=$row['valor'];
($valor==0)? $valor="Gratis!!!" : $valor="$".number_format($valor,0,'.','.');
$top_premios=$row['top_premios'];
$porcentaje_premios=$row['porcentaje_premios'];
$publico=$row['publica'];
$tipo_premios=$row['tipo_premios'];

$query_usuario="SELECT email FROM usuarios WHERE id_usuario=:id_usuario";
//print "q=$query_usuario<br>";
$stmt_usuario= $db->prepare($query_usuario);
$stmt_usuario->bindParam(':id_usuario',$id_usuario);
$stmt_usuario->execute();
$row_usuario = $stmt_usuario->fetch(PDO::FETCH_ASSOC);
$email_usuario=$row_usuario['email'];

($conf_usuarios) ? $conf_usuarios="Si" : $conf_usuarios="No";


$dejar_pasar=false;
$ya_participa=false;


//validar si el evento no es publico...debe tener invitación
if (!$publico){
	//validar si el usuario tiene invitación o ya participa
   $query2="SELECT * FROM invitaciones WHERE email=:email AND id_evento=:id_evento";
   $stmt2= $db->prepare($query2,array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt2->bindParam(':email',$email_usuario);
	$stmt2->bindParam(':id_evento',$id_evento);
	$stmt2->execute();
   if ($stmt2->rowCount()==0){
      $query3="SELECT * FROM usuariosxevento WHERE id_evento=:id_evento ANd id_usuario=:id_usuario;";
         $stmt3= $db->prepare($query3,array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt3->bindParam(':id_evento',$id_evento);
	$stmt3->bindParam(':id_usuario',$id_usuario);
	$stmt3->execute();
      if ($stmt3->rowCount()>0)
         $dejar_pasar=true;
         $ya_participa=true;
   }else{
		$dejar_pasar=true;
   }

}else{   $dejar_pasar=true;
   $query3="SELECT * FROM usuariosxevento WHERE id_evento=:id_evento ANd id_usuario=:id_usuario";
   $stmt3= $db->prepare($query3,array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt3->bindParam(':id_evento',$id_evento);
	$stmt3->bindParam(':id_usuario',$id_usuario);
	$stmt3->execute();
   if ($stmt3->rowCount()>0)
         $ya_participa=true;

}

if ($dejar_pasar){

if ($invitacion){
	$texto_inicial="Ha sido invitado al siguiente evento.";
}else{
	if ($ya_participa)
	  $texto_inicial="¡Ya participas en este evento!";
   else
      $texto_inicial="Ha solicitado ingreso al siguiente evento.";
}

?>
<center>
<?
   if (isset($_SESSION['msg'])){
      echo $_SESSION['msg'];
      unset($_SESSION['msg']);
      print "<br><br>";
   }
?>

<form name="evento_ingreso" class="form-wrapper" action="evento_ingreso_procesar.php" method="POST">
<?= $texto_inicial ?>
<br><br>
<? if (!$ya_participa){ ?>
Por favor confirme si la información es correcta.
<?} ?>
<br><br>
<table class="tabla_simple">
<tr>
   <td>Nombre del Evento
   <td><?= $evento ?>
<tr>
   <td>Administrador
   <td><?= $admin ?>
<tr>
   <td>Descripción
   <td><?= $descripcion ?>
<tr>
  <td>Valor
  <td><?= $valor ?>
<?
if ($top_premios!="0"){
?>
<tr>
   <td>Premios
   <td>
<?
       print "$top_premios";

       	  print" Primeros lugares:<br>";
       	  $premios_array=split(',',$porcentaje_premios);
       	  for ($i=1 ; $i<=$top_premios ; $i++ ){
				  $j=$i-1;
       	  	  print "<br>$i. ";
       	  	  if ($tipo_premios=="fijo") print "$".number_format($premios_array[$j],0,"",".");
       	  	  else if ($tipo_premios=="porcentaje") print "$premios_array[$j]%";
       	  }
          print "<br>Los porcentajes corresponden al total del valor recaudado";
       }


?>
<tr>
   <input type="hidden" name="id_evento" value="<?= $id_evento ?>">
   <input type="hidden" name="confirmado" value="1">
<?
if (isset($_SESSION['usuario_polla'])){	if (!$ya_participa){
?>
   <td colspan="2" style="text-align: center;"><input type="submit" class="submit" value="Confirmar"></form>
<?
   }
}else{?>
  <td colspan="2" style="text-align: center;">Debes estar autenticado para solicitar ingreso</form>
<?
}
?>
</table>
</center>

<?
}else{   print "<span class=\"msg_error\">Este evento es privado y solo se puede acceder a él mediante invitación del administrador.</span>";
}
?>
