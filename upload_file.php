<?php
/**
 * Swiff.Uploader Example Backend
 *
 * This file represents a simple logging, validation and output.
 *  *
 * WARNING: If you really copy these lines in your backend without
 * any modification, there is something seriously wrong! Drop me a line
 * and I can give you a good rate for fancy and customised installation.
 *
 * No showcase represents 100% an actual real world file handling,
 * you need to move and process the file in your own code!
 * Just like you would do it with other uploaded files, nothing
 * special.
 *
 * @license		MIT License
 *
 * @author		Harald Kirschner <mail [at] digitarald [dot] de>
 * @copyright	Authors
 *
 */


/**
 * Only needed if you have a logged in user, see option appendCookieData,
 * which adds session id and other available cookies to the sent data.
 *
 * session_id($_POST['SID']); // whatever your session name is, adapt that!
 * session_start();
 */

// Request log

/**
 * You don't need to log, this is just for the showcase. Better remove
 * those lines for production since the log contains detailed file
 * information.
 */
if (isset($_GET['id_equipo']))
   $id_equipo=$_GET['id_equipo'];
else if (isset($_GET['id_evento']))
   $id_evento=$_GET['id_evento'];
else if (isset($_GET['id_usuario']))
   $id_usuario=$_GET['id_usuario'];
else if (isset($_GET['id_liga']))
   $id_liga=$_GET['id_liga'];
else if (isset($_GET['id_bolsa']))
   $id_bolsa=$_GET['id_bolsa'];
else if (isset($_GET['id_album']))
   $id_album=$_GET['id_album'];
else if (isset($_GET['id_lamina']))
   $id_lamina=$_GET['id_lamina'];
else if (isset($_GET['id_pais']))
   $id_pais=$_GET['id_pais'];
else if (isset($_GET['id_jugador']))
   $id_jugador=$_GET['id_jugador'];
else
   $id_equipo=rand(0,999);

//   print "id_evento=$id_evento\n";


$result = array();

$result['time'] = date('r');
$result['addr'] = substr_replace(gethostbyaddr($_SERVER['REMOTE_ADDR']), '******', 0, 6);
$result['agent'] = $_SERVER['HTTP_USER_AGENT'];

if (count($_GET)) {
	$result['get'] = $_GET;
}
if (count($_POST)) {
	$result['post'] = $_POST;
}
if (count($_FILES)) {
	$result['files'] = $_FILES;
}

// we kill an old file to keep the size small
if (file_exists('script.log') && filesize('script.log') > 102400) {
	unlink('script.log');
}

$log = @fopen('script.log', 'a');
if ($log) {
	fputs($log, print_r($result, true) . "\n---\n");
	fclose($log);
}


// Validation

$error = false;

if (!isset($_FILES['Filedata']) || !is_uploaded_file($_FILES['Filedata']['tmp_name'])) {
	$error = 'Carga inválida';
}


$extension=pathinfo($_FILES['Filedata']['name'], PATHINFO_EXTENSION);

if (isset($_GET['id_equipo']))
   $nombre_archivo=$id_equipo.".".$extension;
else if (isset($_GET['id_evento']))
   $nombre_archivo="e".$id_evento.".".$extension;
else if (isset($_GET['id_usuario']))
   $nombre_archivo="u".$id_usuario.".".$extension;
else if (isset($_GET['id_bolsa']))
   $nombre_archivo="b".$id_bolsa.".".$extension;
else if (isset($_GET['id_liga']))
   $nombre_archivo="l".$id_liga.".".$extension;
else if (isset($_GET['id_album']))
   $nombre_archivo="a".$id_album.".".$extension;
else if (isset($_GET['id_lamina']))
   $nombre_archivo="n".$id_lamina.".".$extension;
else if (isset($_GET['id_pais']))
   $nombre_archivo="p".$id_pais.".".$extension;
else if (isset($_GET['id_jugador']))
   $nombre_archivo="j".$id_jugador.".".$extension;
else
   $nombre_archivo="indefinido".$extension;

  move_uploaded_file($_FILES['Filedata']['tmp_name'], 'uploads/' . $nombre_archivo);
  $return['src'] = 'uploads/'. $nombre_archivo;
/* *
 * or
 **/
// $return['link'] = YourImageLibrary::createThumbnail($_FILES['Filedata']['tmp_name']);
// *
// */

if ($error) {

	$return = array(
		'status' => '0',
		'error' => $error
	);

} else {

	$return = array(
		'status' => '1',
		'name' => $nombre_archivo
	);

	// Our processing, we get a hash value from the file
	$return['hash'] = md5_file($_FILES['Filedata']['name']);

	// ... and if available, we get image data
	$info = @getimagesize($_FILES['Filedata']['tmp_name']);

	if ($info) {
		$return['width'] = $info[0];
		$return['height'] = $info[1];
		$return['mime'] = $info['mime'];
	}

}


// Output

/**
 * Again, a demo case. We can switch here, for different showcases
 * between different formats. You can also return plain data, like an URL
 * or whatever you want.
 *
 * The Content-type headers are uncommented, since Flash doesn't care for them
 * anyway. This way also the IFrame-based uploader sees the content.
 */

if (isset($_REQUEST['response']) && $_REQUEST['response'] == 'xml') {
	// header('Content-type: text/xml');

	// Really dirty, use DOM and CDATA section!
	echo '<response>';
	foreach ($return as $key => $value) {
		echo "<$key><![CDATA[$value]]></$key>";
	}
	echo '</response>';
} else {
	// header('Content-type: application/json');

	echo json_encode($return);
}

?>