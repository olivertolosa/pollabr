<?

session_start();
include 'includes/_Policy.php';

?>
<link rel="stylesheet" type="text/css" href="css/polla.css" />
<?php



include 'includes/Open-Connection.php';
include 'includes/class_usuario.php';

$id_usuario=$_GET['id_usuario'];

$user=new usuario($db);
$img=$user->get_imagen($id_usuario);
//print_r($_REQUEST);



print "<center><table style=\"width:100%\">
          <tr><td style=\"text-align:center;vertical-align:middle;\"><img src=\"".$user->get_imagen($id_usuario)."\" style=\"max-height:80px; max-width:80px\">
              <td style=\"text-align:center;vertical-align:middle;\"><span class=\"titulo_medio\">".$user->get_nombre($id_usuario)."</span>
          </table><br>";

print "<img src=\"saldo_graph.php?id_usuario=$id_usuario\">";
print "</center>";
?>

