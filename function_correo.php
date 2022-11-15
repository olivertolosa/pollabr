<?php
function envio_correo($to,$nombre,$from,$subject,$mensaje){


$file_array=glob("imagenes/banners/*.*");
$cant=sizeof($file_array);
$img=rand(0,$cant-1);


//armar el mensaje
$texto_html="
<center>
<table style=\"background-color: #CCC8C8; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 580 !important;heigh: 100% margin: 0; padding: 0;\">
<tr><td><center>
<tr><td><center><img src=\"http://www.elgolganador.com/$file_array[$img]\" width=\"550\" height=\"80\"></center>
<tr><td><center><table style=\"font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6; width: 550; margin: 0; padding: 10px;\"><tr style=\"font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6; margin: 0; padding: 0;\"><td style=\"font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6; margin: 0; padding: 0;\"></td>
		<td bgcolor=\"#FFFFFF\" style=\"font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6; display: block !important; max-width: 580px !important; clear: both !important; margin: 0 auto; padding: 20px; border: 1px solid #f0f0f0;\">


			<div style=\"font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6; max-width: 600px; display: block; margin: 0 auto; padding: 0;\">
			<table style=\"font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6; width: 100%; margin: 0; padding: 0;\"><tr style=\"font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6; margin: 0; padding: 0;\"><td style=\"font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6; margin: 0; padding: 0;\">
						<p style=\"font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; font-weight: normal; margin: 0 0 10px; padding: 0;\">";
//agregar el mensaje a enviar
$texto_html= $texto_html.stripslashes(nl2br($mensaje));

/*$texto_html= $texto_html."						<table style=\"font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6; width: 100%; margin: 0; padding: 0;\"><tr style=\"font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6; margin: 0; padding: 0;\"><td style=\"font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6; margin: 0; padding: 10px 0;\">
									<p style=\"font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; font-weight: normal; margin: 0 0 10px; padding: 0;\"><a href=\"http://www.elgolganador.com\" style=\"font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 2; color: #FFF; text-decoration: none; font-weight: bold; text-align: center; cursor: pointer; display: inline-block; border-radius: 25px; background-color: #6E6B6B; margin: 0 10px 0 0; padding: 0; border-color: #6E6B6B; border-style: solid; border-width: 10px 20px;\">Ir a \"ElGolGanador\"</a></p>
								</td>
					</td>
				</tr></table></div>


		</td>
		<td style=\"font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6; margin: 0; padding: 0;\"></td>
	</tr></table><table style=\"font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6; width: 100%; clear: both !important; margin: 0; padding: 0;\"><tr style=\"font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6; margin: 0; padding: 0;\"><td style=\"font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6; margin: 0; padding: 0;\"></td>
		<td style=\"font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto; padding: 0;\">


			<div style=\"font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6; max-width: 600px; display: block; margin: 0 auto; padding: 0;\">
				<table style=\"font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6; width: 100%; margin: 0; padding: 0;\"><tr style=\"font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6; margin: 0; padding: 0;\"><td align=\"center\" style=\"font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6; margin: 0; padding: 0;\">
							<p style=\"font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 12px; line-height: 1.6; color: #666; font-weight: normal; margin: 0 0 10px; padding: 0;\">Este mensaje ha sido enviado directamente desde \"ElGolGanador\". No responda a este mensaje; por favor use el formulario de contacto en la página de <a href=\"www.elgolganador.com\">ElGolGanador.com</a>
							</p>
						</td>
					</tr></table></div>


		</td>
	</tr></table></center>
"; */


$texto_html= $texto_html."

	</tr></table></center>
";


$name = $nombre; // Your name, the from name.
$email = $from; // Your e-mail, the from address.
$mailbody = "<hr>$texto_html<hr>";

//print "$mailbody";
//print "para :$to<br>";
mail("$to",
"$subject",
"$mailbody",
"From: $name <$email>\n" .
"BCC: $to\n" .
"MIME-Version: 1.0\n" .
"Content-type: text/html; charset=iso-8859-1");

//$tmp=mail("otolosa@gmail.com","$subject","$mailbody");



//dejar log
date_default_timezone_set ('America/Bogota');

$fecha=date('Y-m-d');
$hora=date('H:i');
$minutos=substr($hora,strpos($hora,":")+1,2);

$archivo_log=fopen('logs/log_correo_'.$fecha.'.log','a+');

fwrite ($archivo_log, "\r\n\r\n*****************************************************\r\n$fecha-$hora - $to - $subject - $mensaje\r\n*****************************************************\r\n");
fclose($archivo_log);

}

?>
