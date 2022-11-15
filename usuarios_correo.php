<?php

require_once 'includes/Open-Connection.php';

$i=0;

$query="SELECT email FROM usuarios WHERE recibir_correos='1' and email LIKE '%@banrep.gov.co' and id_usuario IN (SELECT id_usuario FROM usuariosxevento WHERE id_evento=50)";

$result = $db->query($query);
while($row=$result->fetch(PDO::FETCH_ASSOC)){
   $usuario=$row['email'];
   print "$usuario;";
   $i++;
}
include 'includes/Close-Connection.php';

print "<br><br>total: $i";
?>
