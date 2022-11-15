<?

//guarda el valor del saldo de los usuarios activos en la fecha de ejecución en la tabla saldos_historia

require_once 'includes/Open-Connection.php';
require_once 'audit.php';

date_default_timezone_set ('America/Bogota');

$fecha=date('Y-m-d');

//seleecionar los usuarios activos y su saldo
$query="SELECT id_usuario,saldo FROM usuarios";
$stmt = $db->query($query);
audit_saldos(0,0);
while ($row=$stmt->fetch(PDO::FETCH_ASSOC)){	 $id_usuario=$row['id_usuario'];
	 $saldo=$row['saldo'];

     print "usr: $id_usuario......\$$saldo<br>";
     //validar si ya existe un registro para ver si se hace insert o update
     $query2="SELECT count(*) as cuantos FROM saldos_historia WHERE id_usuario='$id_usuario' and FECHA='$fecha'";
     $stmt2 = $db->query($query2);
     $row2=$stmt2->fetch(PDO::FETCH_ASSOC);
     $cuantos=$row2['cuantos'];
     if ($cuantos==0)
         $query2="INSERT INTO saldos_historia VALUES('$id_usuario','$fecha','$saldo')";
     else
         $query2="UPDATE saldos_historia SET saldo='$saldo' WHERE id_usuario='$id_usuario' and FECHA='$fecha'";
	 $stmt2 = $db->query($query2);
	 audit_saldos($id_usuario,$saldo);
}

//registrar el total de plata en EGG
$query="SELECT SUM(saldo) as total FROM usuarios";
$stmt = $db->query($query);
$row=$stmt->fetch(PDO::FETCH_ASSOC);
$saldo_total=$row['total'];

$query="SELECT count(*) as cuantos FROM plata_total WHERE fecha='$fecha'";
$stmt = $db->query($query);
$row=$stmt->fetch(PDO::FETCH_ASSOC);
$cuantos=$row['cuantos'];
if ($cuantos==0)
    $query2="INSERT INTO plata_total VALUES('$fecha','$saldo_total')";
else
$query2="UPDATE plata_total SET total='$saldo_total' WHERE fecha='$fecha'";
$stmt2 = $db->query($query2);


if (!isset($_SESSION['usuario_polla']))
   include 'includes/Close-Connection.php';

?>