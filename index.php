<?php
session_start();



function microtime_float()
{
list($useg, $seg) = explode(" ", microtime());
return ((float)$useg + (float)$seg);
}

$tiempo_inicio = microtime_float();

require 'config.php';
include 'debug.php';
include 'common.php';
require_once 'audit.php';

require_once 'includes/Open-Connection.php';
date_default_timezone_set ('America/Bogota');


//include 'includes/db.php';


if (!isset($_SESSION['android'])){
   require_once 'includes/Mobile_Detect.php';
   $detect1 = new Mobile_Detect;
   if($detect1->isAndroidOS()){
      //print "si android";
      $_SESSION['android']=true;
      $android=true;
   }else{
      //print "NO android";
      $_SESSION['android']=false;
      $android=false;
   }
}

//validar si ya se revisó si es mobil o no y e tal caso setearlo
if (!isset($_SESSION['mobile'])){
   require_once 'includes/Mobile_Detect.php';
   $detect2 = new Mobile_Detect;

   if ($detect2->isMobile() && !$detect2->isTablet()){
      $_SESSION['mobile']=true;
      $mobile=true;
   }else
      $_SESSION['mobile']=false;
}else{
   $mobile=$_SESSION['mobile'];
}
//print_r($_SESSION);




if (isset($_REQUEST['accion'])) $accion=$_REQUEST['accion'];
$id_usuario=$_SESSION['usuario_polla'];
$admin=$_SESSION['admin'];
$administra_polla=$_SESSION['administra_polla'];
$id_evento=$_REQUEST['id_evento'];
$id_bolsa=$_REQUEST['id_bolsa'];
$accion2=$_REQUEST['accion2'];

//setear el id del album vigente
$id_album=1;


if ($accion=="logout"){
    $redirect="logout2.php";
   header('Location: '.$redirect);
}

//*********************************************************
//auditoria

//*********************************************************

audit_max();

//print "id_evento al puro inicio=$id_evento";

//antes de todo validar compatibilidad con html5
?>
<script>
var element = document.createElement("input");
element.setAttribute("type", "number");
//return element.type !== "text";
if (!(element.type !== "text"))
   window.location="incompatible.php";
//   alert("Su navegador no es del todo compatible con html5. Esto podría ocasionar errores inesperados. Le agradecemos usar un navegador mas actualziado.");
</script>


<?

if (isset($_SESSION['cambia_clave']) and $accion!="reglamento" and $accion!="logon" and $accion!=""){
   require_once 'includes/Open-Connection.php';
   include 'head.php';
   include 'menu.php';
   include 'micuenta.php';
   include 'includes/Close-Connection.php';
   exit();
}

//validar que no se está solicitando una operación de admin sin serlo
if ( ($accion=="apostarxusuario" or $accion=="editar_partido" or $accion=="generar_posiciones"
      or $accion=="editar_equipo") and !$admin){
   $accion="";
}

//validar si se está haciendo logon --> No se debe cargar el menu xq anula el redirect
if ($accion=="logon" and isset($_POST['clave'])){
   if (isset($_SESSION['msg']))
      include 'logon.php';
   else
      include 'login.php';
}



//print "admin=$admin";
//print "usuario=$id_usuario<br>";


include 'head.php';



//($android)? print "SI ES ANDROID" : "No es android";
//print_r($_SESSION);

//actualizar la cantidad de sobres pendientes para que quede la cantidad que es.
//sobres x abir
if ($accion=="abrir_sobres" AND isset($_POST['abrir'])){
   $query="SELECT cantidad FROM album_sobres WHERE id_usuario=$id_usuario AND id_album='$id_album'";
   $stmt=$db->query($query);
   $row=$stmt->fetch(PDO::FETCH_ASSOC);
   $cantidad_sobres=$row['cantidad'];
   //borrar los sobres pendientes
   $query="UPDATE album_sobres SET cantidad='0' WHERE id_album='$id_album' AND id_usuario='$id_usuario'";
   $stmt=$db->query($query);
}

//inicio del cuerpo
include 'menu.php';




//print "accion=$accion<br>";
if ($accion=="logon"){
    $contenido= 'login.php';
}else if ($accion=="ayuda"){
    $contenido= 'ayuda.php';
}else if ($accion=="contacto"){
    $contenido= 'contacto.php';
}else if ($accion=="recuperar_clave"){
    $contenido= 'recuperar_clave.php';
}else if ($accion=="reglamento"){
    $contenido= 'reglamento.php';
}else if ($accion=="registro"){
    $contenido= 'registro.php';
//}else if ($accion!=""){
//   $contenido= 'includes/_Policy.php';
//   print "policy!!!!";


//apuestas y resultados
}else if ($accion=="fixture"){
    $contenido= 'fixture.php';
}else if ($accion=="posiciones_torneo"){
    $contenido= 'posiciones_torneo.php';
}else if ($accion=="apostar"){
    ($mobile) ? $contenido= 'apuestas_mobile.php' : $contenido= 'apuestas.php';;
}else if ($accion=="apuestas_todos"){
    $contenido= 'apuestas_todos.php';
}else if ($accion=="resultados"){
     //   ($mobile)? $contenido='resultados_mobile.php' : $contenido= 'resultados.php';
$contenido= 'resultados.php';
}else if ($accion=="apuestad_usuario_listar"){
    $contenido= 'apuestad_usuario_listar.php';
}else if ($accion=="apuestad_usuario"){
    $contenido= 'apuestad_usuario.php';
}else if ($accion=="apuestasd_disponibles"){
    $contenido= 'apuestasd_disponibles_big.php';
}else if ($accion=="apuestad_usuario_historia"){
    $contenido= 'apuestad_usuario_historia.php';
}else if ($accion=="apuestad_usuario_historia"){
    $contenido= 'apuestad_usuario_historia.php';
}else if ($accion=="apuestad_nueva"){
    $contenido= 'apuestad_nueva.php';
}else if ($accion=="apuestad_listar" && $admin){
    $contenido= 'listar_apuestasd.php';
}else if ($accion=="apuestad_modificar" && $admin){
    $contenido= 'apuestad_modificar.php';
}else if ($accion=="apuestad_admin_listar_apus" && $admin){
    $contenido= 'apuestad_admin_usuario_listar.php';
}else if ($accion=="apuestad_admin_historia_apus" && $admin){
    $contenido= 'apuestad_admin_usuario_historia.php';
}else if ($accion=="apuestad_admin_hist_usr" && $admin){
    $contenido= 'apuestad_admin_hist_usr.php';



// duelos
}else if ($accion=="duelo_nuevo"){
    $contenido= 'duelo_nuevo.php';
}else if ($accion=="duelo_invitaciones"){
    $contenido= 'duelo_invitaciones.php';
}else if ($accion=="mis_duelos"){
    $contenido= 'duelo_mis_duelos.php';
}else if ($accion=="duelo_acre"){
    $contenido= 'duelo_acre.php';


//duelos admin
}else if ($accion=="duelo_listar" && $admin){
    $contenido= 'duelos_admin_listar.php';


}else if ($accion=="posiciones"){
    $contenido= 'posiciones.php';
}else if ($accion=="micuenta"){
    $contenido= 'micuenta.php';
}else if ($accion=="eqfavoritos"){
    $contenido= 'usuario_eqfavoritos.php';
}else if ($accion=="reglas"){
    $contenido= 'reglas.php';




//********************************************
//admin
//********************************************


//eventos
}else if ($accion=="evento_editar" && $admin){
    $contenido= 'evento_editar.php';
}else if ($accion=="evento_listar" && $admin){
    $contenido= 'listar_eventos.php';
}else if ($accion=="evento_detalle" && $admin){
    $contenido= 'evento_detalle.php';
}else if ($accion=="evento_nuevo" && $admin){
    $contenido= 'evento_nuevo.php';
}else if ($accion=="evento_eliminar" && $admin){
    $contenido= 'evento_eliminar.php';
}else if ($accion=="evento_admin"){
    $contenido= 'evento_admin.php';
}else if ($accion=="evento_buscar"){
    $contenido= 'evento_buscar.php';
}else if ($accion=="evento_solicitar"){
    $contenido= 'evento_solicitar.php';
}else if ($accion=="ingreso_evento"){
    $contenido= 'evento_ingreso.php';



//********************************************
//crones
//********************************************
}else if ($accion=="cron_carga_marcadores"){
    $contenido= 'carga_marcadores.php';
}else if ($accion=="cron_deshabilita_partidos"){
    $contenido= 'deshabilitar_partido_cron.php';
}else if ($accion=="cron_noti_favoritos"){
    $contenido= 'cron_noti_favoritos.php';
}else if ($accion=="cron_saldos_historia"){
    $contenido= 'cron_saldos_historia.php';


//bolsa
}else if ($accion=="bolsa_nueva" && $admin){
    $contenido= 'bolsa_nueva.php';
}else if ($accion=="bolsa_listar" && $admin){
    $contenido= 'listar_bolsas.php';
}else if ($accion=="bolsa_admin"){
    $contenido= 'bolsa_admin.php';
}else if ($accion=="bolsa_tranzar"){
    $contenido= 'bolsa_tranzar.php';
}else if ($accion=="bolsa_portafolio"){
    $contenido= 'bolsa_portafolio.php';
}else if ($accion=="bolsa_punta"){
    $contenido= 'bolsa_punta.php';
}else if ($accion=="bolsa_reporte_acciones"){
    $contenido= 'bolsa_reporte_acciones.php';
}else if ($accion=="bolsa_reporte_operaciones"){
    $contenido= 'bolsa_reporte_operaciones.php';
}else if ($accion=="bolsa_ofertas"){
    $contenido= 'bolsa_ofertas.php';
}else if ($accion=="mis_operaciones"){
    $contenido= 'bolsa_reporte_mis_operaciones.php';
}else if ($accion=="bolsa_alertas"){
    $contenido= 'bolsa_alertas.php';
}else if ($accion=="bolsa_alerta_nueva"){
    $contenido= 'bolsa_noti_usuario.php';


//equipos y ligas
}else if ($accion=="listar_grupos-equipos" && admin){
    $contenido= 'listar_grupos-equipos.php';
}else if ($accion=="grupos-equipos_nuevo" && $admin){
    $contenido= 'grupo-equipo_nuevo.php';
}else if ($accion=="traducciones" && $admin){
    $contenido= 'traducciones_listar.php';
}else if ($accion=="traduccion_nueva" && $admin){
    $contenido= 'traduccion_nueva.php';
}else if ($accion=="traduccion_alias" && $admin){
    $contenido= 'alias.php';
}else if ($accion=="grupo-equipos_detalle" && $admin){
    $contenido= 'grupo-equipo_detalle.php';
}else if ($accion=="grupo-equipo_validar" && $admin){
    $contenido= 'grupo_equipo_validar.php';
}else if ($accion=="listar_equipos"){  //se quita la restricción de admin para que cualquiera pueda ver los equipos
    $contenido= 'listar_equipos.php';
}else if ($accion=="editar_equipo" && $admin){
    $contenido= 'editar_equipo.php';
}else if ($accion=="equipo_nuevo" && $admin){
    $contenido= 'equipo_nuevo.php';
}else if ($accion=="equipo_favoritos" && $admin){
    $contenido= 'equipo_favoritos.php';
}else if ($accion=="listar_partidos_historicos" && $admin){
    $contenido= 'listar_partidos_historicos.php';
}else if ($accion=="editar_partido_historico" && $admin){
    $contenido= 'partido_historico_editar.php';
}else if ($accion=="equipo_jugadores" && $admin){
    $contenido= 'equipo_jugadores.php';
	
	





//usuarios
}else if ($accion=="listar_usuarios" && $admin){
    $contenido= 'listar_usuarios.php';
}else if ($accion=="usuario_nuevo" && $admin){
    $contenido= 'usuario_nuevo.php';
}else if ($accion=="editar_usuario" && $admin){
    $contenido= 'editar_usuario.php';
}else if ($accion=="procesar_invitacion"){
    $contenido= 'procesar_invitacion.php';
}else if ($accion=="notificaciones" && $admin){
    $contenido= 'usuario_notificaciones.php';
}else if ($accion=="usuario_finanzas" && $admin){
    $contenido= 'usuario_finanzas.php';


//finanzas
}else if ($accion=="movimientos_plata" && $admin){
    $contenido= 'movimientos_plata.php';


//albums
}else if ($accion=="ver_album"){
    $contenido= 'album_ver.php';
}else if ($accion=="album_repetidas"){
    $contenido= 'album_repetidas.php';
}else if ($accion=="muro_repetidas"){
    $contenido= 'album_muro_repetidas.php';
}else if ($accion=="album_cambiar"){
    $contenido= 'album_cambiar.php';
}else if ($accion=="abrir_sobres"){
    $contenido= 'album_abrir_sobres.php';
}else if ($accion=="listar_albums" && $admin){
    $contenido= 'listar_albums.php';
}else if ($accion=="album_nuevo" && $admin){
    $contenido= 'album_nuevo.php';
}else if ($accion=="album_detalle" && $admin){
    $contenido= 'album_detalle.php';
}else if ($accion=="album_laminas_listar" && $admin){
    $contenido= 'album_laminas_listar.php';
}else if ($accion=="album_lamina_nueva" && $admin){
    $contenido= 'album_lamina_nueva.php';
}else if ($accion=="album_lamina_editar" && $admin){
    $contenido= 'album_lamina_detalle.php';
}else if ($accion=="album_asignar_laminas"){
    $contenido= 'album_asignar_laminas.php';


//mensajes
}else if ($accion=="listar_mensajes" && $admin){
    $contenido= 'listar_mensajes.php';
}else if ($accion=="mensaje_nuevo" && $admin){
    $contenido= 'mensaje_nuevo.php';
}else if ($accion=="editar_mensaje" && $admin){
    $contenido= 'editar_mensaje.php';
//}else if ($accion=="usuarios_sin_apuesta" && $admin){
//    include 'usuarios_sin apuesta.php';
//}else if ($accion=="pagos" && $admin){
//    include 'pagos.php';



//paises
}else if ($accion=="listar_paises" && $admin){
    $contenido= 'pais_listar.php';
}else if ($accion=="pais_nuevo" && $admin){
    $contenido= 'pais_nuevo.php';
}else if ($accion=="pais_editar" && $admin){
    $contenido= 'pais_editar.php';
	
//jugadores
}else if ($accion=="jugador_nuevo" && $admin){
    $contenido= 'jugador_nuevo.php';
}else if ($accion=="jugador_editar" && $admin){
    $contenido= 'jugador_editar.php';	
}else if ($accion=="jugador_carga_masiva" && $admin){
    $contenido= 'jugador_carga_masiva.php';	
	
	
	
	
//trivias
}else if ($accion=="listar_trivias" && $admin){
    $contenido= 'trivia_listar.php';
}else if ($accion=="trivia_nuevo" && $admin){
    $contenido= 'trivia_nuevo.php';
}else if ($accion=="trivia_preguntas" && $admin){
    $contenido= 'trivia_preguntas.php';	
}else if ($accion=="trivia_pregunta_editar" && $admin){
    $contenido= 'trivia_pregunta_editar.php';
}else if ($accion=="trivia_pregunta_nueva" && $admin){
    $contenido= 'trivia_pregunta_nueva.php';	


//}else if ($accion!="logon" and $accion!="reglamento" and $accion!="registro" and $accion!="ayuda"
//      and $accion!="recuperar_clave" and $accion!="contacto")	{
}else{
	    $contenido= 'inicio.php';
}
if (!isset($_REQUEST['id_evento'])) $id_evento="";

//print "contenido = $contenido<br>";

print "<center>";
print"        <table class=\"tabla_index\">
           <tr>";
if (!$mobile){

   print "    <td style=\"vertical-align:top;padding-top: 5px;padding-left: 15px;padding-right: 15px; width: 180px;background:white;\">";
?>
<!-- *****************************************************************************************
                                                INICIO Columna Izquierda
**********************************************************************************************  -->
<?
      include "col_izq.php";
?>
<!-- *****************************************************************************************
                                                Fin Columna Izquierda
**********************************************************************************************  -->
<?
}

     print "     <td style=\"vertical-align:top;padding-top: 5px;padding-left: 0px;padding-right: 0px; width:100%;background:white;\">";   //columna central principal
if (isset($_SESSION['msg']) && ($accion!="evento_admin" || $accion!="bolsa_admin") && $accion!="ingreso_evento"){  //se exceptua cualquier opción de admin de evento para que el mensaje salgadebajo del encabezado
//   echo $_SESSION['msg'];
//   unset ($_SESSION['msg']);
}

if ( (isset($_REQUEST['id_evento']) or (isset($_REQUEST['id_bolsa'])))and !$mobile){
   $id_bolsa=$_REQUEST['id_bolsa']; //se vuelve a setear el parámetro xq fue modificado por el menu
//	$extension=extension_imagen_evento($id_evento);
    if (isset($_REQUEST['id_evento'])){
      require_once 'includes/class_evento.php';
      $evento=new evento($db);
      $imagen_evento=$evento->get_imagen($id_evento);
    }else if (isset($_REQUEST['id_bolsa'])){
      require_once 'includes/class_bolsa.php';
      $bolsa=new bolsa($db);
      $imagen_evento=$bolsa->get_imagen($_REQUEST['id_bolsa']);
    }
	//nombre del evento
	if (isset($_REQUEST['id_evento'])){
   	   $query_nom_evento="SELECT evento FROM eventos WHERE id_evento='$id_evento'";
	   $stmt_nom_evento = $db->query($query_nom_evento);
	   $row_nom_evento=$stmt_nom_evento->fetch(PDO::FETCH_ASSOC);
	   $nombre_evento=$row_nom_evento['evento'];
	 }else if (isset($_REQUEST['id_bolsa'])){
   	   require_once 'includes/class_bolsa.php';
   	   $bolsa=new bolsa($db);
   	   $nombre_evento=$bolsa->get_nombre($_REQUEST['id_bolsa']);
	 }

    $rnd=rand();  //cheat para el cache
    $logo_evento=$imagen_evento."?rnd";
   print "<center><table style=\"border:0;width: 95%\">
           <tr><td style=\"width: 580px\"><h1>$nombre_evento</h1>
           <td style=\"width:80px; vertical-align:text-top; padding-left: 15px;padding-top: 5px;\"><img src=\"$logo_evento\" style=\"border:0px;width: 120px\">
           </table>";

   //si es un superadmin y se está administrando el evento poner menú de admon del evento
   if (isset($_SESSION['admin']) and $_SESSION['admin']==1 and $accion=="evento_admin"){
      include 'menu_evento_superadmin.php';
   }

//si es un superadmin y se está administrando la bolsa poner menú de admon del evento
   if (isset($_SESSION['admin']) and $_SESSION['admin']==1 and $accion=="bolsa_admin"){
      include 'menu_bolsa_superadmin.php';
   }


//   print "</center><br>"; //columna derecha dodne va la imagen del evento cuando aplica

}

      include $contenido;


if (!$mobile){
?>
<!-- *****************************************************************************************
                                                INICIO Columna Derecha
**********************************************************************************************  -->
<?
    print "<td style=\"	vertical-align:top;padding-top: 5px;padding-right: 15px;padding-left: 15px; width: 180px;background:white;\">";
    include "col_der.php";
?>
<!-- *****************************************************************************************
                                                Fin Columna Derecha
**********************************************************************************************  -->
<?
}
print "</table>
      </center>";

include 'includes/Close-Connection.php';

?>


</div>  <!-- /.container principal-->
</div>  <!-- /.fondo_global-->



<?
if ($accion!="apuestas_todos"){
?>



<div class="footer">
      <div class="container text-center">
        <p class="text-muted">ElGolGanador. Todos los derechos reservados</p>
      </div>
</div>

<?
}
?>
<script src="includes/bootstrap-3.1.1-dist/js/bootstrap.min.js"></script>


</body>
<script>
//var width = document.getElementById('tablita').offsetWidth;
//alert (width);

</script>

<script>
$body = $("body");

$(document).on({
    ajaxStart: function() { $body.addClass("loading");    },
     ajaxStop: function() { $body.removeClass("loading"); }
});
</script>
<? if ($accion=="apostar"){?>
<script>
        $(document).ready(function() {
            $('.tooltip').tooltipster();
        });
    </script>
<? }
$tiempo_fin = microtime_float();


//echo "<br><br>Tiempo empleado: " . ($tiempo_fin - $tiempo_inicio);

 ?>

<a href="#" class="scrollToTop" ></a>
<div class="modal"><!-- Place at bottom of page --></div>


</html>

<script>
$(document).ready(function(){

	//Check to see if the window is top if not then display button
	$(window).scroll(function(){
		if ($(this).scrollTop() > 100) {
			$('.scrollToTop').fadeIn();
		} else {
			$('.scrollToTop').fadeOut();
		}
	});

	//Click event to scroll to top
	$('.scrollToTop').click(function(){
		$('html, body').animate({scrollTop : 0},800);
		return false;
	});

});
</script>
