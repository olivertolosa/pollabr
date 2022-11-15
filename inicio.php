<?
echo $_SESSION['msg'];
unset ($_SESSION['msg']);

require_once 'includes/class_usuario.php';
$usuarioobj=new usuario($id_usuario);
$nombre_usuario=$usuarioobj->get_nombre_corto($id_usuario);

$saldo=$usuarioobj->get_saldo($id_usuario);
$saldo=number_format($saldo,0,'.','.');

/*
if (isset($_SESSION['usuario_polla'])) {
   print "<h2>Hola $nombre_usuario</h2>";
   print "<br>Tu saldo es: \$$saldo<br>";
}*/




?>

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/es_LA/sdk.js#xfbml=1&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<br>
<!-- <p style="text-align:left;width:90%">¿Te gusta la página de ElGolGanador? ... danos un "Me gusta" y/o compartelo con tus amigos.</p>  -->
        <!--Botón de Facebook -->
<?
//valor del ancho dependiendo de si es movil o no
($mobile)? $ancho_fb=280 : $ancho_fb=600;
?>
<!--        <div class="fb-like" data-href="http://www.elgolganador.com" data-width="<?= $ancho_fb ?>" data-layout="standard" data-action="like" data-show-faces="true" data-share="true"></div>   -->
        <!-- Fin Botón de Facebook -->
<br>

<table style="border: solid 0px;width:90%">
  <tr>
     <td>
     <td style="text-align:center"><img src="imagenes/logo_col.png" class="logo_gol">
</table>
<br><br>

<table border="0" width="90%">


<?
//Inicio Encuesta
//solo para usuarios registrados
if (isset($id_usuario)){
?>
<script language="javascript">
function selectoff(){    var sel=document.getElementById('voto_select');
    var texto=document.getElementById('voto_propuesta');
    var tipo=document.getElementById('tipo_voto');
    tipo.value="texto";
    sel.style.display='none';
    texto.style.display='block';
}

function selecton(){
    var sel=document.getElementById('voto_select');
    var texto=document.getElementById('voto_propuesta');
    var tipo=document.getElementById('tipo_voto');
    tipo.value="select";
    sel.style.display='block';
    texto.style.display='none';
}

</script>

<?

//*****************Invitaciones******************************

	$query="SELECT id_evento,evento FROM eventos
	       WHERE id_evento IN
	          (SELECT id_evento FROM invitaciones
	          WHERE email=(SELECT email FROM usuarios WHERE id_usuario='$id_usuario'))";
   $stmt=$db->query($query);
   if ($stmt->rowCount()>0){   	print "<tr><td><h3><img src=\"imagenes/invitation_icon.png\" style=\"width:55px;height:55px\"> Invitaciones pendientes</h3>
   	       Tienes invitaciones pendientes a los siguientes eventos:<br><br>";
   	print "<ul>";
   	while ($row=$stmt->fetch(PDO::FETCH_ASSOC)){   		$id_evento=$row['id_evento'];
   		$evento=$row['evento'];
   		print "<li><a href=\"index.php?accion=ingreso_evento&id_evento=$id_evento\">$evento <img src=\"imagenes/aceptar.jpg\" style=\"width:35px;height:35px\" title=\"Aceptar la invitación\"></a>
   		           <a href=\"invitacion_rechazar.php?id_evento=$id_evento\"><img src=\"imagenes/rechazar.jpg\" style=\"width:35px;height:35px\" onclick=\"javascript: return confirm('Esta seguro de rechazar esta invitación')\" title=\"Rechazar invitación\"></a></li>\n";
   	}
   	print "</ul><br><br>";   }

//*****************FIN Invitaciones******************************


//*****************Encuesta para el nombre******************************
/*
   //validar si acaba de realizar el voto
   if (isset($_SESSION['msg'])){   	   echo "<center>".$_SESSION['msg']."</center><br><br>";
   	   unset ($_SESSION['msg']);
   }
   //validar si el usuario no ha votado
   $query="SELECT id_usuario FROM encuesta_votos WHERE id_usuario='$id_usuario'";
   $result = mysql_query($query) or die(mysql_error());
   if (mysql_num_rows($result)==0){
      print "<tr><td><h3><img src=\"imagenes/encuesta.png\" style=\"width:55px;height:55px\"> Encuesta para el dominio</h3>";

      print "Estamos seleccionando el nombre mas adecuado para el website. Te invitamos a darnos tu opnión votando por alguno de los nombres propuestos
           o proponiendo uno.<br><br>El nombre elegido será usado en el dominio <i>www.elnombreelegido.com</i>. No debe contener caracteres especiales ni tildes.<br><br>Muchas gracias por la colaboración.<br><br>";

      print "<form name=\"encuesta\" class=\"form-wrapper\" method=\"POST\" action=\"encuesta_procesar.php\">
              <input type=\"hidden\" name=\"tipo_voto\" id=\"tipo_voto\" value=\"select\">";
      print "<div id=\"voto_select\">www.<select name=\"voto\">";
      $query_op="SELECT nombre FROM encuesta_nombres ORDER BY nombre ASC";
      $result_op = mysql_query($query_op) or die(mysql_error());
      while ($row_op=mysql_fetch_assoc($result_op)){         $nombre=$row_op['nombre'];
         print "<option>$nombre</option>";
      }

      print "</select>.com<br>";
      print "<a href=\"javascript:selectoff()\">No me gusta ninguna...quiero proponer un nombre yo mismo</a></div>";
      print "<div id=\"voto_propuesta\" style=\"display:none;\">
                 www.<input type=\"text\" name=\"propuesta\" pattern=\"[a-z,0-9,'-']*\" maxlength=\"30\" size=\"30\">.com
                 <br><b>Nota:</b>Solo minúsculas sin tilde, números y guión<br>
                 <br><a href=\"javascript:selecton()\">Se me acabó la inspiración, mejor escojo una opción.</a>
             </div>";
      print "<br><input type=\"submit\" class=\"submit\" value=\"Enviar\"></form><br><br>";
   }
   */
//*****************FIN Encuesta para el nombre******************************

}  //fin de if para ver si es usuario

if (isset($_GET['desde']) and is_numeric($_GET['desde'])){   $desde=$_GET['desde'];
}else{   $desde=0;
}
//validar que el desde sea inferior a la cantidad de mensajes
$query="SELECT count(*) as cuantos FROM mensajes";
$stmt= $db->prepare($query);
$stmt->execute();
$row=$stmt->fetch(PDO::FETCH_ASSOC);
$cuantos=$row['cuantos'];
if ($desde>=$cuantos) $desde=0;

$query="SELECT * FROM mensajes WHERE categoria='Noticia general' ORDER BY fecha DESC, id_mensaje DESC offset $desde rows fetch next $cantidad_mensajes rows only ";
$stmt= $db->prepare($query);
//$stmt->debugDumpParams();

$stmt->execute();
//debug("query",$query);
while($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
   $id_mensaje=$row['id_mensaje'];   $fecha=$row['fecha'];
   $titulo=$row['titulo'];
   $mensaje=$row['mensaje'];
   $mensaje=stripslashes($mensaje);
   $dia=substr($fecha,8,2);
   $mes=substr($fecha,5,2);

switch ($mes){
   	  case 1 : $mes="Ene";
               break;
   	  case 2 : $mes="Feb";
               break;
   	  case 3 : $mes="Mar";
               break;
   	  case 4 : $mes="Abr";
               break;
   	  case 5 : $mes="May";
               break;
   	  case 6 : $mes="Jun";
               break;
   	  case 7 : $mes="Jul";
               break;
   	  case 8 : $mes="Ago";
               break;
   	  case 9 : $mes="Sep";
               break;
   	  case 10 : $mes="Oct";
               break;
   	  case 11 : $mes="Nov";
               break;
   	  case 12 : $mes="Dic";
               break;
   }

   print "<tr><td><div id=\"container_fechas\">

	<div class=\"date\">
		<p>$dia <span>$mes</span></p>
	</div>

</div><h3 style=\"position:relative;left:70px;top: -50px;\">$titulo</h3>
        $mensaje<br>";
  //excepción para la lista de jugadores
  if ($id_mensaje==27) include 'seleccion.php';
   //excepción para nacho
  if ($id_mensaje==51) include 'nacho.php';
  print "      <br>
        <div style=\"border: 0;
height: 1px;
background-image: -webkit-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.75),rgba(0,0,0,0));
background-image: -moz-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));
background-image: -ms-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));
background-image: -o-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));\"></div>

        <br>\n";



}

// Links a anterior y suguiente

//validar si hay q poner link para entradas anteriores
print "<table style=\"width:90%; border: solid 0px\">
         <tr><td style=\"text-align:left;\">";

if ($desde>0){
   $desde_sig-=$cantidad_mensajes;
   if ($desde_sig<0)      print "<a href=\"index.php\">&lt;&lt; Entradas Recientes</a>";
   else
      print "<a href=\"index.php?desde=$desde_sig\"> &lt;&lt; Entradas Recientes</a>";
}

print "<td style=\"text-align:right;\">";
//validar si hay que poner link a mas entradas

debug("cuantos",$cuantos);
debug("cantidad mensajes",$cantidad_mensajes);
debug("desde",$desde);

if ($cuantos-$desde-$cantidad_mensajes-1>0){	$desde+=$cantidad_mensajes;
	debug("desde",$desde);    print "<a href=\"index.php?desde=$desde\">Entradas Anteriores &gt;&gt;</a>";
}



?>
</table>
</table>
<br><br>
