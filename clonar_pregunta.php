<?
require_once 'includes/Open-Connection.php';

$trivia_desde=1;
$trivia_hasta=2;

$id_pregunta=$_GET['id_pregunta'];
$query="SELECT * FROM trivia_preguntas WHERE id_pregunta='$id_pregunta' and id_trivia='$trivia_desde'";
$stmt = $db->query($query);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$id_pregunta=$row['id_pregunta'];
$pregunta=$row['pregunta'];
$resp1=$row['resp1'];
$resp2=$row['resp2'];
$resp3=$row['resp3'];
$resp4=$row['resp4'];
$resp_correcta=$row['resp_correcta'];
$info_extra=$row['info_extra'];

print_r ($info_extra);

$query="INSERT INTO trivia_preguntas VALUES ('','$trivia_hasta','$pregunta','$resp1','$resp2','$resp3','$resp4','$resp_correcta','$info_extra')";
print "<br><br>q=$query<br>";
$stmt = $db->query($query);

require_once 'includes/Close-Connection.php';
?>