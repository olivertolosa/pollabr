<?
session_start();
include 'includes/_Policy.php';

$id_equipo=rand();

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
<form name="modifcar_equipo" class="form-wrapper" action="equipo_nuevo_procesar.php" method="POST">
<table class="tabla_simple">
<tr>
   <td>Equipo
   <td><input type="text" name="equipo" value="<?= $equipo ?>" required>
<tr>
   <td>Nomber en LiveScores
   <td><input type="text" name="equipols" value="<?= $equipols ?>" required>
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
   	   if ($id_grupo==$id_grupo_equipo) print " SELECTED";
   	   print ">$nombre_grupo</option>\n";
   }
?>
</SELECT>

<tr>
   <td>Logo
   <td>
			   <center><img src="" width="120" height="120" id="imagen_subida">
	<br><div id="fileuploader">Cargar nueva imagen</div></center>

<tr>
   <td colspan="2" style="text-align: center;"><input type="submit" class="submit" value="Crear Equipo">
</center>
<input type="hidden" name="id_equipo" value="<?= $id_equipo ?>">
</form>
</table>