<!-- *****************************************************************************************
                                                INICIO MENU
**********************************************************************************************  -->
<?
//menu fuente: http://www.red-team-design.com/css3-animated-dropdown-menu


?>
<nav id="menu-wrap-superadmin">
	<ul id="menu-niveles-superadmin">
	   <li><a href="#">Parámetros</a>
	      <ul>
              <li><a href="index.php?accion=evento_admin&id_evento=<?= $id_evento ?>&accion2=parametros">Parámetros Generales</a></li>
              <li><a href="index.php?accion=evento_admin&id_evento=<?= $id_evento ?>&accion2=equiposxevento">Equipos Participantes</a></li>

<?
   //validar si alguna ronda maneja grupo de equipos
   $query2="SELECT * FROM rondasxevento WHERE id_evento=:id_evento AND grupos>'1'";
   $stmt2= $db->prepare($query2,array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
	$stmt2->bindParam(':id_evento',$id_evento);
	$stmt2->execute();
   if ($stmt2->rowCount()>0){
   	  print "       <li><a href=\"#\">Grupos</a>
   	         <ul>\n";
      while($row2=$stmt2->fetch(PDO::FETCH_ASSOC)){
          $nombre_ronda=$row2['nombre'];
          $num_ronda=$row2['num_ronda'];
          print "       <li><a href=\"index.php?accion=evento_admin&id_evento=$id_evento&accion2=grupos&ronda=$num_ronda\">$nombre_ronda</a></li>";
      }
      print "</ul></li>";
   }
?>
     </ul></li>
      <li><a href="#" >Partidos</a>
		      <ul>
		           <li><a href="index.php?accion=evento_admin&id_evento=<?= $id_evento ?>&accion2=listar_partidos">Listar Partidos</a></li>
		           <li><a href="index.php?accion=evento_admin&id_evento=<?= $id_evento ?>&accion2=partido_nuevo">Incluir Partido</a></li>
		      </ul>
		   </li>
		   <li><a href="#">Usuarios</a>
		      <ul>
		           <li><a href="index.php?accion=evento_admin&id_evento=<?= $id_evento ?>&accion2=listar_usuarios">Listar Usuarios</a></li>
		           <li><a href="index.php?accion=evento_admin&id_evento=<?= $id_evento ?>&accion2=sin_apuesta">Usuarios sin apuesta</a></li>
		      <!--     <li><a href="index.php?accion=evento_admin&id_evento=<?= $id_evento ?>&accion2=invitar">Invitar</a></li>  -->
				   <li><a href="index.php?accion=evento_admin&id_evento=<?= $id_evento ?>&accion2=impersonar">Impersonar</a></li>
		      </ul>
		   </li>
		   <li><a href="#">Resultados</a>
		     <ul>
		          <li><a href="index.php?accion=evento_admin&id_evento=<?= $id_evento ?>&accion2=actualizar_marcadores">Actualizar Marcadores</a></li>
		          <li><a href="index.php?accion=evento_admin&id_evento=<?= $id_evento ?>&accion2=actualizar_resultados">Actualizar tabla de resultados</a></li>

		     </ul>
		   </li>
<!--		   <li><a href="index.php?accion=evento_admin&id_evento=<?= $id_evento ?>&accion2=notificaciones">Notificaciones</a></li>  -->
	</ul>
</nav>

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
		$('#menu-wrap-superadmin').prepend('<div id="menu-niveles-trigger">Menu</div>');
		$("#menu-niveles-trigger").on("click", function(){
			$("#menu-niveles-superadmin").slideToggle();
		});

		// iPad
		var isiPad = navigator.userAgent.match(/iPad/i) != null;
		if (isiPad) $('#menu-niveles-superadmin ul').addClass('no-transition');
    });
</script>

<? if ($mobile){ ?>
   <script>
	   $(function(){
   		$('#menu-evento-superadmin').slicknav();
	   });
   </script>
<? } ?>


<!-- *****************************************************************************************
                                                FIN MENU  SUPERADMIN
**********************************************************************************************  -->
