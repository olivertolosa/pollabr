<?

include 'includes/Open-Connection.php';

$usuario=$_POST['user'];
$query="SELECT id_usuario FROM usuarios WHERE usuario='$usuario'";
$stmt = $db->query($query);

$num=$stmt->rowCount();

if ($num==0) print "0";
else{
   $id_usuario=$row['id_usuario'];
   print $id_usuario;
}

include 'includes/Close-Connection.php';
?>