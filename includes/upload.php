<?
/* este archivo se debe incluir en las páginas que van a hacer uploads

   Se debe configurar la variable upl_data que debe contener el tipo
   de imagen q se sube (equipo, evento, usuario) y el id del objeto

   Ej:id_equipo=$id_equipo

*/
?>
	<script type="text/javascript" src="includes/mootools.js"></script>

	<script type="text/javascript" src="includes/source/Fx.ProgressBar.js"></script>

	<script type="text/javascript" src="includes/source/Swiff.Uploader.js"></script>

	<script type="text/javascript" src="includes/Roar.js"></script>

	<link rel="stylesheet" href="css/Roar.css" type="text/css">


	<!-- See script.js -->
	<script type="text/javascript">
		//<![CDATA[

/**
 * FancyUpload Showcase
 *
 * @license		MIT License
 * @author		Harald Kirschner <mail [at] digitarald [dot] de>
 * @copyright	Authors
 */

window.addEvent('domready', function() {

	// One Roar instance for our notofications, positioned in the top-right corner of our demo.
	var log = new Roar({
		container: $('demo'),
		position: 'topRight',
		duration: 5000
	});

	var link = $('select-0');
	var linkIdle = link.get('html');

	function linkUpdate() {
		if (!swf.uploading) return;
		var size = Swiff.Uploader.formatUnit(swf.size, 'b');
		link.set('html', '<span class="small">' + swf.percentLoaded + '% of ' + size + '</span>');
	}

	// Uploader instance
	var swf = new Swiff.Uploader({
		path: 'includes/source/Swiff.Uploader.swf',
		url: 'upload_file.php?<?= $upl_data  ?>',
		verbose: true,
		queued: false,
		multiple: false,
		target: link,
		instantStart: true,
		typeFilter: {
			'Images (*.jpg, *.jpeg, *.gif, *.png)': '*.jpg; *.jpeg; *.gif; *.png'
		},
		fileSizeMax: 2 * 1024 * 1024,
		onSelectSuccess: function(files) {
			if (Browser.Platform.linux) window.alert('Advertencia: Debido a un comportamiento de Flash en Linux,\nEl navegador se puede congelar mientras el proceso de carga.\nComo ya está advertido...ahí vamos ...');
			log.alert('Iniciando Carga de Archivo', 'Cargando <em>' + files[0].name + '</em> (' + Swiff.Uploader.formatUnit(files[0].size, 'b') + ')');
			this.setEnabled(false);
		},
		onSelectFail: function(files) {
			log.alert('<em>' + files[0].name + '</em> no fue cargada!', 'Por favor seleccione una imagen de menos de 2 Mb. (Error: #' + files[0].validationError + ')');
		},
		appendCookieData: true,
		onQueue: linkUpdate,
		onFileComplete: function(file) {

			// We *don't* save the uploaded images, we only take the md5 value and create a monsterid ;)
			if (file.response.error) {
				log.alert('Carga de archivo fallida', 'Carga de <em>' + this.fileList[0].name + '</em> falló, Por favor intente nuevamente. (Error: #' + this.fileList[0].response.code + ' ' + this.fileList[0].response.error + ')');
			} else {
//				alert (file.response.text);
				var filename = JSON.decode(file.response.text, true).name; // secure decode

//				log.alert('Successful Upload', 'an MD5 hash was created from <em>' + this.fileList[0].name + '</em>: <code>' + md5 + '</code>.<br />gravatar.com generated a fancy and unique monsterid for it, since we did not save the image.');
//				log.alert('Carga exitosa', 'an MD5 hash was created from <em>' + this.fileList[0].name + '</em>: .<br />gravatar.com generated a fancy and unique monsterid for it, since we did not save the image.');
				log.alert('Carga exitosa', 'Se ha cargado correctamente el archivo <em>' + this.fileList[0].name + '</em>.');

//				var img = $('demo-portrait');
//				img.setStyle('background-image', '../../uploads/fifa.png');//
				var img=document.getElementById("imagen_subida");
				alert (filename);
				img.src="uploads/"+filename;
				img.highlight();
			}

			file.remove();
			this.setEnabled(true);
		},
		onComplete: function() {
			link.set('html', linkIdle);
		}
	});

	// Button state
	link.addEvents({
		click: function() {
			return false;
		},
		mouseenter: function() {
			this.addClass('hover');
			swf.reposition();
		},
		mouseleave: function() {
			this.removeClass('hover');
			this.blur();
		},
		mousedown: function() {
			this.focus();
		}
	});

});

		//]]>
	</script>



	<!-- See style.css -->
	<style type="text/css">
		/* Basic layout */

h4 {
	margin-top: 1.25em;
}

a {
	padding: 1px;
}

a:hover, a.hover {
	color: red;
}

/* demo elements */

#demo-portrait {
	float: left;
	position: relative;
	width: 130px;
	height: 153px;
	border: 1px solid #eee;
	background-position: 1px 1px;
	background-repeat: no-repeat;
}

#demo-portrait a {
	position: absolute;
	left: 1px;
	right: 1px;
	bottom: -10px;
	padding: 0;
	line-height: 22px;
	display: block;
	text-align: center;
}
</style>
