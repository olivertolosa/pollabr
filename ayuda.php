<center><style type="text/css">
*, * focus {
	outline: none;
	margin: 0;
	padding: 0;
}

@media (max-width: 480px) {
   .container_ayuda {
   	   width: 300px;
	   margin: 0 auto;
   }
   h2.acc_trigger {  	 width: 300px;
	 font-size: 1em;
   }
   .acc_container {	   width: 300px;
   }
}

@media (min-width: 481px) {   .container_ayuda {
   	   width: 520px;
	   margin: 0 auto;
   }
   h2.acc_trigger {  	 width: 530px;
	 font-size: 2em;
   }
   .acc_container {	   width: 530px;
   }
}

h1 {
	font: 4em normal Georgia, 'Times New Roman', Times, serif;
	text-align:center;
	padding: 20px 0;
	color: #000000;
}
h1 span { color: #666; }
h1 small{
	font: 0.3em normal Verdana, Arial, Helvetica, sans-serif;
	text-transform:uppercase;
	letter-spacing: 0.5em;
	display: block;
	color: #666;
}

h2.acc_trigger {
	padding: 0;	margin: 0 0 5px 0;
	background: url(imagenes/h2_trigger_a.gif) no-repeat;
	height: 46px;	line-height: 46px;
	font-weight: normal;
	float: left;
}
h2.acc_trigger a {
	color: #ffffff;
	text-decoration: none;
	display: block;
	padding: 0 0 0 50px;     //sangria del título
}
h2.acc_trigger a:hover {
	color: #D4D1CD;
}
h2.active {background-position: left bottom;}
.acc_container {
	margin: 0 0 5px; padding: 0;
	overflow: hidden;
	font-size: 1.0em;
	clear: both;
	background: #f0f0f0;
	border: 1px solid #d6d6d6;
	-webkit-border-bottom-right-radius: 5px;
	-webkit-border-bottom-left-radius: 5px;
	-moz-border-radius-bottomright: 5px;
	-moz-border-radius-bottomleft: 5px;
	border-bottom-right-radius: 5px;
	border-bottom-left-radius: 5px;
}
.acc_container .block {
	padding: 20px;
}
.acc_container .block p {
	padding: 5px 0;
	margin: 5px 0;
}
.acc_container h3 {
	font: 1.5em normal Georgia, "Times New Roman", Times, serif;
	margin: 0 0 10px;
	padding: 0 0 5px 0;
	border-bottom: 1px dashed #ccc;
}
</style>

<script type="text/javascript">
$(document).ready(function(){

//Set default open/close settings
$('.acc_container').hide(); //Hide/close all containers
$('.acc_trigger:first').addClass('active').next().show(); //Add "active" class to first trigger, then show/open the immediate next container

//On Click
$('.acc_trigger').click(function(){
	if( $(this).next().is(':hidden') ) { //If immediate next container is closed...
		$('.acc_trigger').removeClass('active').next().slideUp(); //Remove all .acc_trigger classes and slide up the immediate next container
		$(this).toggleClass('active').next().slideDown(); //Add .acc_trigger class to clicked trigger and slide down the immediate next container
	}
	return false; //Prevent the browser jump to the link anchor
});

});
</script>
<script src="includes/jquery.hoverpulse.js" type="text/javascript"></script>
<center>
<div class="container_ayuda">

	<h2 class="acc_trigger"><a href="#">¿Como funciona?</a></h2>
	<div class="acc_container">
		<div class="block">
			<p>Este sitio le permite a los usuarios crear eventos en los que pueden jugar con sus amigos o en general con cualquier persona,
			buscando predecir los marcadores</p>
			<p>En 2015 ELGolGanador introdujo una nueva forma de juego llamada "Bolsa de Acciones" en donde se juega comprando y vendiendo acciones de los
			equipos que participan en un torneo.</p>
			<p>De igual manera pueden participar en eventos creados por otros usuarios, todos relacionados con el fútbol (por ahora).</p>

			<h3>¿Por que me estaba saliendo un aviso de que mi navegador no es compatible?</h3>
			<p>Este sitio utiliza algunas funcionalidades que no están disponibles en navegadores "viejitos". Para garantizar que la experiencia del usuario
			va a ser lo mas placentera posible preferimos exigir que se use un navegador compatible con html5.</p>
			<p>Recomendamos el uso de Chrome, FireFox o Safari.</p>

			<h3>¿Existe versión para usar desde mi teléfono móvil?</h3>
			<p>Claro que si!!!. Entrando a la misma página (www.elgolganador.com) desde tu teléfono verás la versión para móviles. Estamos trabajando para hacerla lo mas completa posible</p>
		</div>
	</div>

	<h2 class="acc_trigger"><a href="#"> Mi cuenta</a></h2>
	<div class="acc_container">
		<div class="block">
			<h3>¿Qué eso de "Mi Cuenta"?</h3>
			<p>Al <a href="index.php?acion=registro">registrarse</a>, cada usuario crea una cuenta en el sitio. Esta cuenta le permite participar en eventos creados por otros usuarios, recibir notificaciones sobre
			sus movimientos, estado, etc.</p>
			<p>También podrá solicitar la creación de un nuevo evento. En este punto, el usuario se vuelve el <k>Administrador</k> del evento, accediendo a
			funcionalidad adicional (Ver sección <k>Eventos</k> Mas adelante.)</p>
	        <h3>¿Quién y como se van a manejar mis datos?</h3>
	        <p>Este sitio sólo le pedirá los datos necesarios para la correcta participación en los eventos que manjea. Los datos no serán entregados a nigún tercero
	        bajo ninguna circunstancia</p>
	        <p>Solamente los datos usuario y nombre serán visibles a otros usuarios</p>
		</div>
	</div>

	<h2 class="acc_trigger"><a href="#">¿Que es un evento?</a></h2>
	<div class="acc_container">
		<div class="block">
			<p>Un evento consiste en una serie de juegos en los que un grupo de usuarios puede participar tratando de acertar los marcadores, obteniendo puntos por los aciertos,
			y en caso de que el organizador del evento así lo haya dispuesto, participando por premios</p>

            <h3>¿No todos los eventos tienen premios?</h3>
			<p>No. Eso depende del organizador del evento</p>

            <h3>¿Puedo hacer eventos privados para que solo mis amigos participen?</h3>
			<p>Si. Los eventos pueden ser públicos, en cuyo caso son visibles a todos los usuarios y cualquiera puede solicitar el ingreso, o privados, para este
			último el usuario solo puede acceder al mismo mediante invitación</p>

            <h3>¿Cómo busco un evento?</h3>
            <p>Es posible buscar eventos por el nombre del evento, por el organizador, o por ambos. En caso de no saber ninguno de los dos, se puede realizar la búsqueda
            sin filtros y obtener la lista completa de todos los eventos <b>públicos</b> disponibles</p>
		</div>
	</div>

	<h2 class="acc_trigger"><a href="#">¿Como apuesto?</a></h2>
	<div class="acc_container">
		<div class="block">
		   <h3>Eventos</h3>
			<p>Una vez registrado en un <strong>evento</strong>, aparecerán disponible en su menú de usuario todas las opciones relacionadas con el evento, tales como apostar, ver la tabla de posiciones, etc</p>
            <h3>¿Que costo tiene participar en un evento?</h3>
			<p>Eso depende del organizador de cada evento.</p>
			<h3>¿Cuanto cuesta organizar un evento?</h3>
			<p>$1.000 por cada usuario que participe en el evento.</p>
			<h3>¿Hasta cuando puedo registrar/modificar mi predicción?</h3>
			<p>Los marcadores para cada partido se pueden registrar hasta 15 minutos antes de empezar el partido.</p>
			<h3>¿Y si se me olvida y no hago mi predicción?</h3>
			<p>El sistema automáticamente le registrará los marcadores de manera aleatoria</p>
			<h3>¿Cómo se garantiza que nadie apueste después de ese momento?</h3>
			<p>El sistema automáticamente bloquea la posibilidad de registrar/modificar marcadores una vez se llegue al límite de tiempo (15 minutos antes de
			empezar el partido). Las predicciones de todos los usuarios se vuelven visibles de modo que ente todos se valide que nadie modifique datos después de este momento</p>
         <br><br>
         <h3>Bolsa de Acciones</h3>
         <p>Para participar en la bolsa de acciones debes comprar un paquete inicial de acciones; posteriormente podrás comprar y vender acciones a otros
          usuarios o a la casa. Los valores de las acciones dependerán de los resultados del equipo correspondiente.</p>
          <h3>¿Tengo que esperar hasta el final del campeonato para sacar mi plata?</h3>
          <p>No. Puedes retirar tu dinero en cualquier momento.
          <br>Nota: Solo puedes retirar el dinero que tengas en efectivo en tu portafolio. Si tienes acciones debes venderlas para poder cobrar su valor.</p>
		</div>
	</div>

	<h2 class="acc_trigger"><a href="#">¿Que equipos hay disponibles?</a></h2>
	<div class="acc_container">
		<div class="block">
			<p>En este momento se tienen mas de 1.400 equipos de todo el mundo disponibles para ser usados en los eventos. Están incluidas todas las selecciones nacionales
			Todos los equipos de las principales ligas del mundo (España, Inglaterra, Italia, Argentina, Colombia, México, ...)</p>

            <h3>Aqui hay una muestra aleatoria de los equipos disponibles.</h3>
            <p>Si quierse ver mas recarga esta página</p>
            <center><table style="border-style: solid;border-spacing: 2px; width:100%">
            <tr>
<?

//    obtener el número total de equipos
;
    $query="SELECT e.id_equipo,e.equipo,g.grupo_equipos FROM equipos as e, grupos_equipos as g
            WHERE e.id_grupo_equipos=g.id_grupo_equipos
            ORDER BY RAND()
            LIMIT 36";
//    print "query=$query<br>";

    $i=0;
    foreach($db->query($query) as $row){       if ($i%6==0){       	   print "<tr>";
       }
       $id_equipo=$row['id_equipo'];
       $equipo=$row['equipo'];
       $grupo=$row['grupo_equipos'];
       $extension=extension_imagen($id_equipo);
       $imagen="imagenes/logos_equipos/".$id_equipo.$extension;
       print "<td><div class=\"thumb\"><img src=\"$imagen\" width=\"55\" height=\"55\" id=\"img$id_equipo\" title=\"$equipo\n$grupo\"></div>";
       $i++;
    }



?>
			</table></center>
		</div>
	</div>

</div>
</center>

<script>
$(document).ready(function() {
    $('div.thumb img').hoverpulse({
        size: 80,  // number of pixels to pulse element (in each direction)
        speed: 400 // speed of the animation
    });
});
</script>

