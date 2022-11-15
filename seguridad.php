<?
//*****************************************************************************
//  Esta validación se usa solo para las opciones de usuarios no administradores.
//*****************************************************************************

$id_usuario=$_SESSION['usuario_polla'];
$pasar=true;

function url_origin($s, $use_forwarded_host=false)
{
    $ssl = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on') ? true:false;
    $sp = strtolower($s['SERVER_PROTOCOL']);
    $protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
    $port = $s['SERVER_PORT'];
    $port = ((!$ssl && $port=='80') || ($ssl && $port=='443')) ? '' : ':'.$port;
    $host = ($use_forwarded_host && isset($s['HTTP_X_FORWARDED_HOST'])) ? $s['HTTP_X_FORWARDED_HOST'] : (isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : null);
    $host = isset($host) ? $host : $s['SERVER_NAME'] . $port;
    return $protocol . '://' . $host;
}
function full_url($s, $use_forwarded_host=false)
{
    return url_origin($s, $use_forwarded_host) . $s['REQUEST_URI'];
}
$absolute_url = full_url($_SERVER);




///validar que el usuario si haga parte del evento

if (isset($_REQUEST['id_evento'])){	$id_evento=$_REQUEST['id_evento'];

    $query="SELECT * FROM usuariosxevento WHERE id_usuario=:id_usuario AND id_evento=:id_evento";
    $stmt= $db->prepare($query);
	$stmt->bindParam(':id_usuario',$id_usuario);
	$stmt->bindParam(':id_evento',$id_evento);
	$stmt->execute();

    if ($stmt->rowCount()==0){
       $pasar=false;
   }
}else if(isset($_REQUEST['id_bolsa'])){ //validar si el usuario está en la bolsa    $id_bolsa=$_REQUEST['id_bolsa'];

    $query="SELECT * FROM bolsa_saldos WHERE id_bolsa=:id_bolsa ANd id_usuario=:id_usuario";
    $stmt->bindParam(':id_usuario',$id_usuario);
	$stmt->bindParam(':id_bolsa',$id_bolsa);
	$stmt->execute();
    if ($stmt->rowCount()==0){
       $pasar=false;
   }
}


if (!$pasar){  //escribir el fin de la pag
   print "<center><span class=\"msg_error\">Acceso no autorizado.</span></center>\n";
   $url=substr($absolute_url,strpos($absolute_url,"?")+1);
   $_SESSION['url']=$url;


if (!$mobile){	print "<td style=\"	vertical-align:top;padding-top: 5px;padding-right: 15px;padding-left: 15px; width: 180px;background:white;\">";
    include "col_der.php";
}
   print "</table>
      </center>";

   include 'includes/Close-Connection.php';

print "   <div class=\"footer\">\n";
print "      <div class=\"container text-center\">\n";
print "        <p class=\"text-muted\">ElGolGanador. Todos los derechos reservados</p>\n";
print "      </div>\n";
print "   </div>   \n";

   exit();
}
?>