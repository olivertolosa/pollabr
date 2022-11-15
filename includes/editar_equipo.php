<?
session_start();
include 'includes/_Policy.php';

$id_equipo=$_GET['id_equipo'];
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
		url: 'upload_file.php?id_equipo=<?= $id_equipo ?>',
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
			if (Browser.Platform.linux) window.alert('Warning: Due to a misbehaviour of Adobe Flash Player on Linux,\nthe browser will probably freeze during the upload process.\nSince you are prepared now, the upload will start right away ...');
			log.alert('Starting Upload', 'Uploading <em>' + files[0].name + '</em> (' + Swiff.Uploader.formatUnit(files[0].size, 'b') + ')');
			this.setEnabled(false);
		},
		onSelectFail: function(files) {
			log.alert('<em>' + files[0].name + '</em> was not added!', 'Please select an image smaller than 2 Mb. (Error: #' + files[0].validationError + ')');
		},
		appendCookieData: true,
		onQueue: linkUpdate,
		onFileComplete: function(file) {

			// We *don't* save the uploaded images, we only take the md5 value and create a monsterid ;)
			if (file.response.error) {
				log.alert('Failed Upload', 'Uploading <em>' + this.fileList[0].name + '</em> failed, please try again. (Error: #' + this.fileList[0].response.code + ' ' + this.fileList[0].response.error + ')');
			} else {
//				alert (file.response.text);
				var filename = JSON.decode(file.response.text, true).name; // secure decode

//				log.alert('Successful Upload', 'an MD5 hash was created from <em>' + this.fileList[0].name + '</em>: <code>' + md5 + '</code>.<br />gravatar.com generated a fancy and unique monsterid for it, since we did not save the image.');
				log.alert('Successful Upload', 'an MD5 hash was created from <em>' + this.fileList[0].name + '</em>: .<br />gravatar.com generated a fancy and unique monsterid for it, since we did not save the image.');

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
	bottom: 1px;
	padding: 0;
	line-height: 22px;
	display: block;
	text-align: center;
}	</style>


<center>
<? echo $_SESSION['msg']; unset ($_SESSION['msg']); ?>
<form name="modifcar_equipo" action="editar_equipo_procesar.php" method="POST">
<table class="tabla_simple">
<?

$query="SELECT * FROM equipos WHERE id_equipo='$id_equipo'";
$result = mysql_query($query) or die(mysql_error());
$row=mysql_fetch_assoc($result);
$id_equipo=$row['id_equipo'];
$equipo=$row['equipo'];
$id_grupo=$row['id_grupo_equipos'];


?>
<tr>
   <td>Id
   <td><?= $id_equipo ?>
<tr>
   <td>Equipo
   <td><input type="text" name="equipo" value="<?= $equipo ?>">
<tr>
   <td>Grupo
   <td>
<SELECT name="grupo_equipos" title="Sleccione un grupo">
<?
   $query="SELECT * FROM grupos_equipos ORDER BY grupo_equipos ASC";
   $result = mysql_query($query) or die(mysql_error());
   while($row=mysql_fetch_assoc($result)){
   	   $id_grupo_equipo=$row['id_grupo_equipos'];
   	   $nombre_grupo=$row['grupo_equipos'];
   	   print "<option value=\"$id_grupo_equipo\"";
   	   if ($id_grupo==$id_grupo_equipo) print " SELECTED";
   	   print ">$nombre_grupo</option>\n";
   }
?>
</SELECT>

<tr>
   <td>Logo
   <td>			<div id="demo-portrait">
<?
      // detectar la extensión de la bandera
      if (file_exists("imagenes/logos_equipos/".$id_equipo.".png"))
         $extension=".png";
      else if (file_exists("imagenes/logos_equipos/".$id_equipo.".PNG"))
         $extension=".PNG";
      else if (file_exists("imagenes/logos_equipos/".$id_equipo.".jpg"))
          $extension=".jpg";
      else if (file_exists("imagenes/logos_equipos/".$id_equipo.".JPG"))
         $extension=".JPG";
      else if (file_exists("imagenes/logos_equipos/".$id_equipo.".bmp"))
          $extension=".bmp";
      else if (file_exists("imagenes/logos_equipos/".$id_equipo.".BMP"))
          $extension=".BMP";

      $imagen=$id_equipo.$extension;

?>
			   <img src="imagenes/logos_equipos/<?= $imagen ?>" width="120" height="120" id="imagen_subida">
	<a href="#" id="select-0" title="Máximo 2 MB">Cargar Nueva Imagen</a>
</div>
<tr>
   <td colspan="2" style="text-align: center;"><input type="submit" value="Cambiar">
</center>
<input type="hidden" name="id_equipo" value="<?= $id_equipo ?>">
</form>
</table>