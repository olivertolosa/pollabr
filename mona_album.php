<table class="tabla_simple_pequena">
<tr>
   <th>Álbum Copa América Centenario
<tr style="background-color:white"><td>
<tr style="background-color:white"><td style="text-align:center;padding: 0px"><center>
    <div style="position: relative; left: 0; top: 0;">
         <table style="width:194px;border-spacing: 0px; border-collapse: separate;" border="0">
              <tr>
                  <td style="width:194px;height:225px;padding-left: 5px;text-align:center"><?if (isset($_SESSION['usuario_polla'])){  ?><a href="index.php?accion=ver_album&id_album=1"><? } ?>
                     <img src="imagenes/albums/1.png" style="position: absolute;max-width:158px;max-height:150px;top: 20px;left: 35px"/>
                     <img src="imagenes/marco_lamina.png" style="position: absolute; top: 1px; left: 15px;"/><?if (isset($_SESSION['usuario_polla'])){  ?></a><? } ?>
          </table>
    </div>
<tr>
   <td>
<?if (isset($_SESSION['usuario_polla'])){  ?>
<tr>
   <th>Estado del álbum</th>
<tr>
   <td>
<?
   $query="SELECT COUNT(*) as cuantas FROM album_laminas_usuario WHERE id_usuario='$id_usuario'";
   $stmt=$db->query($query);
   $row=$stmt->fetch(PDO::FETCH_ASSOC);
   $cuantas=$row['cuantas'];
   $_SESSION['cuantas_monas_tengo']=$cuantas;

/*   $query="SELECT COUNT(*) as cuantas FROM album_laminas WHERE id_album='$id_album'";
   $stmt=$db->query($query);
   $row=$stmt->fetch(PDO::FETCH_ASSOC);
   $cuantas_monas_totales=$row['cuantas'];   */


   print "Tienes $cuantas de $cuantas_monas_totales láminas";
?>
<tr>
   <th>Láminas pendientes</th>
<?
    $query="SELECT cantidad FROM album_sobres WHERE id_usuario=$id_usuario AND id_album='$id_album'";
//    print "q=$query<br>";
    $stmt=$db->query($query);
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    $cantidad=$row['cantidad'];

    if (!$cantidad) $cantidad=0;
?>
<tr> <td> Tienes <?= $cantidad ?> sobres pendientes por abrir
<? if ($cantidad>0){ ?>
<tr><td style="text-align: center"><a href="index.php?accion=abrir_sobres">Abrir sobres!!!!</a>
<?} } ?>
<tr>
   <th>Top 5</th>

<?
$query="select id_usuario,count(*) as cuantas from album_laminas_usuario group by id_usuario order by cuantas DESC limit 0,5";
$stmt=$db->query($query);
require_once 'includes/class_usuario.php';
$usr=new usuario($db);
while ($row=$stmt->fetch(PDO::FETCH_ASSOC)){
	$id_usu=$row['id_usuario'];
	$tiene=$row['cuantas'];
   print "<tr><td>".$usr->get_usuario($id_usu)." : $tiene";
}
?>

</table>



