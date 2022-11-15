<?
session_start();
include 'includes/_Policy.php';

$id_equipo=$_GET['id_equipo'];
?>

<link href="css/uploadfile.css" rel="stylesheet">
<script src="includes/jquery.uploadfile.min.js"></script>
<? // http://hayageek.com/docs/jquery-upload-file.php.....--> upload script ?>

<script>
$(document).ready(function(){
       <? if (!$mobile) print "\$.fn.bootstrapBtn = \$.fn.button.noConflict();"; ?>

	   $("#fileuploader").uploadFile({
	      url:"upload_file.php?id_equipo=<? echo $id_equipo; ?>",
	      fileName:"Filedata",
	      acceptFiles:"image/*",
	      dataType: "json",
          previewHeight: "100px",
          previewWidth: "100px",
          maxFileCount: 1,
          dragDrop: false,
          uploadStr: "Cargar nueva imagen",
          showFileCounter: false,
          showFileSize: false,
          showProgress: false,
          showPreview:false,
          showStatusAfterSuccess: false,
          onSuccess:function(files,data,xhr,pd){
              //files: list of files
              //data: response from server
              //xhr : jquer xhr object
             console.log(data);
             resp=JSON.parse(data);
             nombre=resp.name;
             $("#imagen_subida").attr("src","uploads/"+nombre);
           },
	   });
});
</script>
<center>
<? echo $_SESSION['msg']; unset ($_SESSION['msg']); ?>
<form name="modifcar_equipo" class="form-wrapper" action="editar_equipo_procesar.php" method="POST">
<table class="tabla_simple">
<?

$query="SELECT * FROM equipos WHERE id_equipo='$id_equipo'";
$stmt = $db->query($query);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$id_equipo=$row['id_equipo'];
$equipo=$row['equipo'];
$equipols=$row['equipoLS'];
$equipols2=$row['equipoLS2'];
$equipols3=$row['equipoLS3'];
$id_grupo=$row['id_grupo_equipos'];


?>
<tr>
   <td>Id
   <td><?= $id_equipo ?>
<tr>
   <td>Equipo
   <td><input type="text" name="equipo" value="<?= $equipo ?>">
<tr>
   <td>Nombre en LiveScores
   <td><input type="text" name="equipols" value="<?= $equipols ?>" required>
<tr>
   <td>Nombre en LiveScores2
   <td><input type="text" name="equipols2" value="<?= $equipols2 ?>">
<tr>
   <td>Nombre en LiveScores3
   <td><input type="text" name="equipols3" value="<?= $equipols3 ?>">
<tr>
   <td>Grupo
   <td>
<SELECT name="grupo_equipos" title="Sleccione un grupo">
<?
   $query="SELECT * FROM grupos_equipos ORDER BY grupo_equipos ASC";
   foreach($db->query($query) as $row) {
   	   $id_grupo_equipo=$row['id_grupo_equipos'];
   	   $nombre_grupo=$row['grupo_equipos'];
   	   print "<option value=\"$id_grupo_equipo\"";
   	   if ($id_grupo==$id_grupo_equipo){   	   	 print " SELECTED";
   	   	 $nombre_grupo_ori=$nombre_grupo;
   	   }
   	   print ">$nombre_grupo</option>\n";
   }
?>
</SELECT>

<tr>
   <td>Logo
   <td>
<?

require_once 'includes/class_equipo.php';

$eq=new equipo($db);

      $imagen=$eq->get_imagen($id_equipo);

?>
			   <center><img src="<?= $imagen ?>" width="120" height="120" id="imagen_subida">
	<br><div id="fileuploader">Cargar nueva imagen</div></center>

<tr>
   <td style="text-align: center;"><input type="submit" class="submit" value="Cambiar">
   <td><a href="index.php?accion=equipo_jugadores&id_equipo=<? echo $id_equipo; ?>"><input type="button" value="Ver Jugadores"></a>
</center>
<input type="hidden" name="id_equipo" value="<?= $id_equipo ?>">
</form>
</table>
<a href="index.php?accion=listar_equipos&grupo_equipos=<?= $id_grupo ?>">Volver a<br> <?= $nombre_grupo_ori ?>