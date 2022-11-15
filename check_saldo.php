<?

include 'includes/Open-Connection.php';

$id_usuario=$_POST['id_usuario'];
$query="SELECT saldo FROM usuarios WHERE id_usuario='$id_usuario'";
$stmt = $db->query($query);
$row=$stmt->fetch(PDO::FETCH_ASSOC);
$saldo=$row['saldo'];

print "$saldo";

include 'includes/Close-Connection.php';
?>