<?php
session_start();
include 'includes/_Policy.php';
$id_usuario=$_SESSION['usuario_polla'];

require_once 'includes/Open-Connection.php';
$voto = $_REQUEST['voto'];
$ya_voto = $_REQUEST['ya_voto'];
$id_usuario=$_SESSION['usuario_polla'];

//insertar o cambiar el voto según corresponda
if ($ya_voto)
   $query="UPDATE encuesta01 SET voto='$voto' WHERE id_usuario='$id_usuario'";
else
   $query="INSERT INTO encuesta01 VALUES ('$id_usuario','$voto')";
   
$result = mysql_query($query) or die(mysql_error());   

require_once 'includes/class_equipo.php';
   $eqobj=new equipo();


include 'tabla_campeon_brasil.php';
?>


</center>
<br>

<?
$query="SELECT voto FROM encuesta01 WHERE id_usuario='$id_usuario'";
$result = mysql_query($query) or die(mysql_error());
if (mysql_num_rows($result)>0){
   $row=mysql_fetch_assoc($result);
   $voto=$row['voto'];
   $ya_voto=1;
   $texto_boton="Cambiar mi voto";
   
   require_once 'includes/class_equipo.php';
   $eqobj=new equipo();
   
   print "Mi voto:<br><img src=\"".$eqobj->get_imagen($voto)."\" style=\"width:60px;height:60px;\" class=\"img_thumb\" title=\"".$eqobj->get_nombre($voto)."\"><br><br>";
   
}

require_once 'includes/Close-Connection.php';
?>