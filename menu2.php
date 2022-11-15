<!-- *****************************************************************************************
                                                INICIO MENU
**********************************************************************************************  -->
<?
require_once 'includes/class_bolsa.php';
$bolsa_obj=new bolsa($db);
?>
<?if ($mobile) {?>
<div id="openModal" class="modalDialog">
	<div>
		<a href="#close" title="Cerrar" class="close">X</a>
		<h2>Ingreso</h2>
		<div id="login">
		    <form action="logon.php" method="post">
					<fieldset id="inputs">
						<input id="username" type="text" name="usuario" placeholder="Usuario" required>
						<input id="password" type="password" name="password" placeholder="Clave">
					</fieldset>
					<fieldset id="actions">
						<input type="submit" id="submit_login" value="Ingresar">
					</fieldset>
				<? //<a href="index.php?accion=recuperar_clave">Olvidé mi clave</a> ?>
				</form></div>
	</div>
</div>
<?
}

//include 'test.html';
//menu fuente: http://www.red-team-design.com/css3-animated-dropdown-menu


//si participa en eventos armar la lista
$query="SELECT ue.id_evento,e.evento FROM usuariosxevento as ue, eventos as e WHERE id_usuario='$id_usuario' AND ue.id_evento=e.id_evento AND e.activo='1'";
//$result = mysql_query($query) or die(mysql_error());
foreach($db->query($query) as $row) {	   $id_evento2=$row['id_evento'];
   	   $evento=$row['evento'];
   	   $eventos_array[$id_evento2]=$evento;
}

//display el menu si no es mobil
($mobile) ? $display="none" : $display="block";


?>

<div id="menu-container" style="display:<?= $display ?>;">


	<ul id="menu-niveles">
	   <li><a href="index.php" class="top_link"><span>Inicio</span></a></li>
	   <li><a href="index.php?accion=reglamento" class="top_link"><span>Reglamento</span></a></li>
<?
//Aqui van todas las opciones de un usuario autenticado
if (isset($id_usuario)){

?>
	 <li><a href="#">Mi Cuenta</a>
	   <ul>
	        <li><a href="index.php?accion=micuenta" class="top_link">Mis Datos</a>
	        <li><a href="index.php?accion=eqfavoritos" class="top_link">Mis Equipos Favoritos</a>
	   </ul>
	</li>

	   <li><a href="#">Pollas</a>
	      <ul>
<?
      if (sizeof($eventos_array)>0){
       foreach($eventos_array as $id_evento2=>$evento){
?>
               <li><a href="#"><?= $evento ?></a>
                  <ul>
			         <li><a href="index.php?accion=apostar&id_evento=<?= $id_evento2 ?>">Mi Pronóstico</a></li>
			         <li><a href="index.php?accion=apuestas_todos&id_evento=<?= $id_evento2 ?>">Ver todos los pronósticos</a></li>
					 <li><a href="index.php?accion=resultados&id_evento=<?= $id_evento2 ?>">Mis Resultados</a></li>
			         <li><a href="index.php?accion=posiciones&id_evento=<?= $id_evento2 ?>">Tabla de posiciones</a></li>
                  </ul>
			</li>

<?
		}
	  }
/*
?>
           <li><a href="#">Apuestas Directas</a>
               <ul>
<? if ($admin){ ?>
                    <li><a href="#">Admin</a>
                      <ul>
                         <li><a href="index.php?accion=apuestad_nueva">Nueva apuesta</a></li>
                         <li><a href="index.php?accion=apuestad_listar">Listar Apuestas</a></li>
                         <li><a href="index.php?accion=apuestad_admin_listar_apus">Ver apuestas de usuarios</a></li>
                         <li><a href="index.php?accion=apuestad_admin_historia_apus">Ver historial de apuestas</a></li>
                         <li><a href="index.php?accion=apuestad_admin_hist_usr">Ver historia de usuario</a></li>

                      </ul>
<?}?>
                    <li><a href="index.php?accion=apuestasd_disponibles">Apuestas Disponibles</a></li>
                    <li><a href="index.php?accion=apuestad_usuario_listar">Mis Apuestas</a></li>
                    <li><a href="index.php?accion=apuestad_usuario_historia">Mis Resultados</a></li>
               </ul>
           </li> <? */ ?>


          </ul>
	   </li>
<? if ($id_usuario==1 or $id_usuario==135 or $id_usuario==292 or $id_usuario==256 or $id_usuario==181){ ?>
	   <li><a href="#">Duelos</a>
               <ul>
<? if ($admin){ ?>
                    <li><a href="#">Admin</a>
                      <ul>
                         <li><a href="index.php?accion=duelo_listar">Listar Duelos</a></li>
                         <li><a href="index.php?accion=duelo_admin_historia">Ver historial de duelos</a></li>
                         <li><a href="index.php?accion=duelo_admin_hist_usr">Ver historia de usuario</a></li>

                      </ul>
<?}?>
                    <li><a href="index.php?accion=duelo_nuevo">Crear un duelo</a></li>
                    <li><a href="index.php?accion=duelo_invitaciones">Ver invitaciones</a></li>
                    <li><a href="index.php?accion=mis_duelos">Ver mis duelos</a></li>
               </ul>
       </li>


<?
}


   if (sizeof($eventos_array)>0){
?>
	<li><a href="#">Resultados</a>
	   <ul>
<?
       foreach($eventos_array as $id_evento2=>$evento){
?>
            <li ><a href="#"><?= $evento ?></a>
               <ul>
		          <li><a href="index.php?accion=posiciones_torneo&id_evento=<?= $id_evento2 ?>">Posiciones</a></li>
		          <li><a href="index.php?accion=fixture&id_evento=<?= $id_evento2 ?>">Fixture</a></li>
           </ul>
			</li>

<?
		}
?>	   </ul>
    </li>
<?
    }
?>

	<li><a href="#"><span class="down">Eventos</span></a>
        <ul>
        <li><a href="index.php?accion=evento_solicitar">Quiero Crear un Evento</a></li>
<?
if ($admin){
?>
           <li><a href="index.php?accion=evento_listar">Listar Eventos</a></li>
           <li><a href="index.php?accion=evento_nuevo">Nuevo Evento</a></li>
<?
}
if ($administra_polla){
           print "       <li><a href=\"#\">Mis Eventos Administrados</a><!-- Inicio Eventos Administrados -->\n                    <ul>\n";


//armar la lista de los eventos administrados
$query="SELECT id_evento,evento FROM eventos WHERE admin='$id_usuario'  AND activo='1' ORDER BY evento ASC";
foreach($db->query($query) as $row) {   $id_evento2=$row['id_evento'];
   $evento=$row['evento'];
   print "<li><a href=\"#\" class=\"fly\">$evento</a>";
?>
        <ul>
   		   <li><a href="">Parámetros</a>
   		      <ul>
                   <li><a href="index.php?accion=evento_admin&id_evento=<?= $id_evento2 ?>&accion2=parametros">Parámetros Generales</a></li>
                   <li><a href="index.php?accion=evento_admin&id_evento=<?= $id_evento2 ?>&accion2=equiposxevento">Equipos Participantes</a></li>
<?
   //validar si alguna ronda maneja grupo de equipos
   $query2="SELECT * FROM rondasxevento WHERE id_evento='$id_evento2' AND grupos>'1'";
   $stmt = $db->query($query2);
   $row_count = $stmt->rowCount();

   if ($row_count>0){
   	  print "<li><a href=\"#\" class=\"fly\">Grupos</a>
   	         <ul>\n";
      while($row2 = $stmt->fetch(PDO::FETCH_ASSOC)) {
          $nombre_ronda=$row2['nombre'];
          $num_ronda=$row2['num_ronda'];
          print "<li><a href=\"index.php?accion=evento_admin&id_evento=$id_evento2&accion2=grupos&ronda=$num_ronda\">$nombre_ronda</a></li>";
      }
    print "</ul></li>\n";
   }
?>
   		      </ul>
   		   </li>
		   <li><a href="#" >Partidos</a>
		      <ul>
		           <li><a href="index.php?accion=evento_admin&id_evento=<?= $id_evento2 ?>&accion2=listar_partidos">Listar Partidos</a></li>
		           <li><a href="index.php?accion=evento_admin&id_evento=<?= $id_evento2 ?>&accion2=partido_nuevo">Incluir Partido</a></li>
		      </ul>
		   </li>
		   <li><a href="#">Usuarios</a>
		      <ul>
		           <li><a href="index.php?accion=evento_admin&id_evento=<?= $id_evento2 ?>&accion2=listar_usuarios">Listar Usuarios</a></li>
		           <li><a href="index.php?accion=evento_admin&id_evento=<?= $id_evento2 ?>&accion2=sin_apuesta">Usuarios sin apuesta</a></li>
		           <li><a href="index.php?accion=evento_admin&id_evento=<?= $id_evento2 ?>&accion2=invitar">Invitar</a></li>
				   <li><a href="index.php?accion=evento_admin&id_evento=<?= $id_evento2 ?>&accion2=impersonar">Impersonar</a></li>
		      </ul>
		   </li>
		   <li><a href="#">Resultados</a>
		     <ul>
		          <li><a href="index.php?accion=evento_admin&id_evento=<?= $id_evento2 ?>&accion2=actualizar_marcadores">Actualizar Marcadores</a></li>
		          <li><a href="index.php?accion=evento_admin&id_evento=<?= $id_evento2 ?>&accion2=actualizar_resultados">Actualizar tabla de resultados</a></li>

		     </ul>
		   </li>
		   <li><a href="index.php?accion=evento_admin&id_evento=<?= $id_evento2 ?>&accion2=notificaciones">Notificaciones</a></li>
		</ul>
<?

      print "</li>";
   }
      print "</ul></li><!-- Fin de eventos administrados -->\n";
}

//si participa en eventos armar la lista
if (sizeof($eventos_array)>0){
   print "         <li><a href=\"#\">Mis Eventos</a><!-- Inicio Eventos en que participa -->\n";
   print "         <ul>\n";

   	   foreach($eventos_array as $id_evento2=>$evento){
   	   print "              <li><a href=\"#\">$evento</a>\n";
   	   print "                 <ul>";
?>
              <li><a href="index.php?accion=apostar&id_evento=<?= $id_evento2 ?>">Apostar</a></li>
			  <li><a href="index.php?accion=apuestas_todos&id_evento=<?= $id_evento2 ?>">Ver Todas las apuestas</a></li>
              <li><a href="index.php?accion=resultados&id_evento=<?= $id_evento2 ?>">Resultados Individuales</a></li>
              <li><a href="index.php?accion=posiciones&id_evento=<?= $id_evento2 ?>">Tabla de posiciones</a></li>
              <li><a href="index.php?accion=reglas&id_evento=<?= $id_evento2 ?>">Reglas de juego</a></li>

<?
       print "     </ul>\n";
       print "    </li>\n";
   }

      print "     </ul>\n";
      print "    </li><!-- Fin Eventos en que participa -->\n";
}

?>
           <li><a href="index.php?accion=evento_buscar">Buscar Evento</a></li>
       </ul>
    </li><!-- Fin sección eventos -->

<!-- Inicio sección Bolsa -->
    <li><a href="#"><span class="down">Bolsa</span></a>
       <ul>
<?
$flag_mostrar_bolsas=false;
//mostrar bolsas solo si es admin o si tiene saldo en alguna bolsa activa
$query="SELECT id_bolsa,nombre_bolsa FROM bolsas WHERE activo='1' AND id_bolsa IN (SELECT id_bolsa FROM bolsa_saldos WHERE id_usuario='$id_usuario')";
$stmt = $db->query($query);
$num_bolsas_participando=$stmt->rowCount();
if ($admin or $num_bolsas_participando>0){   $flag_mostrar_bolsas=true;
?>


<?
if ($admin){
?>
           <li><a href="index.php?accion=bolsa_listar">Listar Bolsas</a></li>
           <li><a href="index.php?accion=bolsa_nueva">Nueva Bolsa</a></li>
<?
}
//armar la lista de bolsas disponibles
   $query="SELECT id_bolsa,nombre_bolsa FROM bolsas WHERE activo='1'";
   //si el usuario no es admin incluir solo donde tenga credito
   if (!$admin){   	   $query.=" AND id_bolsa IN (SELECT id_bolsa FROM bolsa_saldos WHERE id_usuario='$id_usuario')";
   }
   $query.=" ORDER BY nombre_bolsa ASC";
   foreach($db->query($query) as $row){      $id_bolsa=$row['id_bolsa'];
      $nombre_bolsa=$row['nombre_bolsa'];

      print "<li><a href=\"#\">$nombre_bolsa</a>";
      print "    <ul>";
      if ($admin){
         print "        <li><a href=\"index.php?accion=bolsa_admin&id_bolsa=$id_bolsa&accion2=parametros\">Administrar</a>";
         if ($mobile){            print "<ul>
                       <li><a href=\"index.php?accion=bolsa_admin&id_bolsa=$id_bolsa&accion2=credito\">Asignar Crédito</a></li>
                       <li><a href=\"index.php?accion=bolsa_admin&id_bolsa=$id_bolsa&accion2=retiro\">Realizar Retiro</a></li>
		               <li><a href=\"index.php?accion=bolsa_admin&id_bolsa=$id_bolsa&accion2=saldos\">Saldos</a></li>
		               <li><a href=\"index.php?accion=bolsa_admin&id_bolsa=$id_bolsa&accion2=marcarval\">Marcar equipo (valorización)</a></li>
		               <li><a href=\"index.php?accion=bolsa_admin&id_bolsa=$id_bolsa&accion2=depreciar\">Depreciar</a></li>
		               <li><a href=\"index.php?accion=bolsa_admin&id_bolsa=$id_bolsa&accion2=valorizacion\">Ejecutar valorización</a></li>
                   </ul>";
         }
         print "</li>";
      }
      //averiguar si el usuario tiene saldo en la bolsa
      if ($bolsa_obj->saldo($id_bolsa,$id_usuario)>=0){         print "<li><a href=\"index.php?accion=bolsa_tranzar&id_bolsa=$id_bolsa\">Negociar</a></li>";
         //si el usuario tiene puntas ponerle el menu de mis ofertas.
         $query2="SELECT COUNT(*) as num_puntas FROM bolsa_puntas WHERE id_bolsa='$id_bolsa' AND id_usuario='$id_usuario'";
         $stmt = $db->query($query2);
         $row2= $stmt->fetch(PDO::FETCH_ASSOC);
         $num_puntas=$row2['num_puntas'];
         if ($num_puntas>0){            print "<li><a href=\"index.php?accion=bolsa_ofertas&id_bolsa=$id_bolsa\">Mis ofertas</a></li>";
         }
         print "<li><a href=\"index.php?accion=bolsa_portafolio&id_bolsa=$id_bolsa\">Portafolio</a></li>
                <li><a href=\"index.php?accion=bolsa_reporte_acciones&id_bolsa=$id_bolsa\">Reporte de Acciones</a></li>
                <li><a href=\"index.php?accion=bolsa_reporte_operaciones&id_bolsa=$id_bolsa\">Reporte de Operaciones</a></li>";
      }

      print "           </ul></li>";
   }
}
?>

       </ul>
    </li>

<!-- Fin sección Bolsa -->

<!-- Inicio sección álbum 
<li><a href="#"><span class="down">Álbum</span></a>
   <ul>
      <li><a href="index.php?accion=ver_album&id_album=<?= $id_album ?>">Mi Álbum</a></li>
      <li><a href="index.php?accion=album_repetidas">Mis Repetidas</a></li>
      <li><a href="index.php?accion=muro_repetidas">Ver muro de repetidas</a></li>
      <li><a href="index.php?accion=abrir_sobres">Abrir Sobres</a></li>
   </ul>
</li>

<!-- Fin sección álbum -->

    <li><a href="index.php?accion=apostar"><span class="down">Equipos</span></a>

       <ul>
<?
if ($admin){
?>
          <li><a href="#">Ligas</a>
             <ul>
                 <li><a href="index.php?accion=listar_grupos-equipos">Listar Ligas</a></li>

                 <li><a href="index.php?accion=grupos-equipos_nuevo">Incluir Liga</a></li>
                 <li><a href="index.php?accion=traducciones">Traducciones</a></li>
             </ul>
<?
}
?>
          </li>
          <li><a href="#">Equipos</a>
             <ul>
                  <li><a href="index.php?accion=listar_equipos">Listar Equipos</a></li>
<?
if ($admin){
?>
                  <li><a href="index.php?accion=equipo_nuevo">Incluir Equipo</a></li>
                  <li><a href="index.php?accion=equipo_favoritos">Equipos Favoritos</a></li>
<?
}
?>
             </ul>
          </li>
<?
if ($admin){
?>
          <li><a href="#">Partidos</a>
             <ul>
                 <li><a href="index.php?accion=listar_partidos_historicos">Listar Partidos</a></li>
             </ul>
<?
}
?>

       </ul>
    </li>

<?
if ($admin){
?>
    <li><a href=""><span class="down">admin</span></a>
        <ul>
           <li><a href=""><span class="down">Usuarios</span></a>
              <ul>
                 <li><a href="index.php?accion=listar_usuarios">Listar Usuarios</a></li>
                 <li><a href="index.php?accion=usuario_nuevo">Usuario Nuevo</a></li>
                 <li><a href="index.php?accion=notificaciones">Notificaciones</a></li>
              </ul>
           </li>
           <li><a href=""><span class="down">Crones</span></a>
              <ul>
               <li><a href="index.php?accion=cron_carga_marcadores">Cargar Marcadores</a></li>
               <li><a href="index.php?accion=cron_deshabilita_partidos">Deshabilitar Partidos</a></li>
               <li><a href="index.php?accion=cron_noti_favoritos">Notificar Favoritos</a></li>
               <li><a href="index.php?accion=cron_saldos_historia">Historial de Saldos</a></li>
             </ul>
           </li>
          <li><a href=""><span class="down">Albums</span></a>
             <ul>
                <li><a href="index.php?accion=listar_albums">Listar Albums</a></li>
                <li><a href="index.php?accion=album_nuevo">Album Nuevo</a></li>
                <li><a href="index.php?accion=album_asignar_laminas">Asignar Láminas</a></li>
             </ul>
          </li>
          <li><a href=""><span class="down">Mensajes</span></a>
            <ul>
               <li><a href="index.php?accion=listar_mensajes">Listar Mensajes</a></li>
               <li><a href="index.php?accion=mensaje_nuevo">Mensaje Nuevo</a></li>
            </ul>
       </li>
	   <li><a href=""><span class="down">Paises</span></a>
            <ul>
               <li><a href="index.php?accion=listar_paises">Listar Paises</a></li>
               <li><a href="index.php?accion=pais_nuevo">Pais Nuevo</a></li>
            </ul>
       </li>
       <li>
          <a href=""><span class="down">Finanzas</span></a>
          <ul>
              <li><a href="index.php?accion=movimientos_plata">Movimientos de plata</a></li>
          </ul>
       </li>
          <li><a href=""><span class="down">Trivias</span></a>
             <ul>
                <li><a href="index.php?accion=listar_trivias">Listar Trivias</a></li>
                <li><a href="index.php?accion=trivia_nuevo">Nueva Trivia</a></li>
             </ul>
          </li>
       </ul>
    </li>
<?

}

//aqui van las opciones de un usuario no autenticado
}else{
?>
    <li><a href="index.php?accion=registro"><span>Registrarse</span></a></li>



	<li id="login">
<?
if ($mobile){
?>
   <a href="#openModal">Ingreso</a>
<?
}else{
?>
		<a id="login-trigger" href="#">Ingresar<span>&#x25BC;</span></a>
		<div id="login-content">
				<form action="logon.php" method="post">
					<fieldset id="inputs">
						<input id="username" type="text" name="usuario" placeholder="Usuario" required>
						<input id="password" type="password" name="password" placeholder="Clave">
					</fieldset>
					<fieldset id="actions">
						<input type="submit" id="submit_login" value="Ingresar">
					</fieldset>
				<a href="index.php?accion=recuperar_clave">Olvidé mi clave</a>
				</form>
		</div>

<?
}



}
?>
</li>
<li><a href="index.php?accion=ayuda"><span>Ayuda</span></a></li>
<?
if (!$admin){
?>
   <li><a href="index.php?accion=contacto"><span>Contáctanos</span></a></li>
<?
}


if (isset($id_usuario)){
?>
   <li><a href="index.php?accion=logout"><span>Salir</span></a></li>
<?
}
?>
	</ul>
</div>


<? if (!$mobile){ ?>
<script type="text/javascript">
    $(function() {
/*		if ($.browser.msie && $.browser.version.substr(0,1)<7)
		{
		$('li').has('ul').mouseover(function(){
			$(this).children('ul').css('visibility','visible');
			}).mouseout(function(){
			$(this).children('ul').css('visibility','hidden');
			})
		} */

		/* Mobile */
//		$('#menu-wrap').prepend('<div id="menu-niveles-trigger">Menu</div>');
//		$("#menu-niveles-trigger").on("click", function(){
//			$("#menu-niveles").slideToggle();
//		});

		// iPad
		var isiPad = navigator.userAgent.match(/iPad/i) != null;
		if (isiPad) $('#menu-niveles ul').addClass('no-transition');
    });
</script>


<script type="text/javascript">
$(document).ready(function(){
	$('#login-trigger').click(function(){
		$(this).next('#login-content').slideToggle();
		$(this).toggleClass('active');

		if ($(this).hasClass('active')) $(this).find('span').html('&#x25B2;')
			else $(this).find('span').html('&#x25BC;')
		})
});
</script>
<? } ?>


<? if ($mobile){ ?>
   <script>
	   $(function(){
   		$('#menu-niveles').slicknav();
	   });
   </script>
<? } ?>

<!-- *****************************************************************************************
                                                FIN MENU
**********************************************************************************************  -->






