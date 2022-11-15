<?
session_start();
include 'includes/_Policy.php';
include 'includes/class_pais.php';
$id_pais=$_GET['id_pais'];

if (!is_numeric($id_pais)) die ("Parámetros inválidos");
?>

<link href="css/uploadfile.css" rel="stylesheet">
<script src="includes/jquery.uploadfile.min.js"></script>
<? // http://hayageek.com/docs/jquery-upload-file.php.....--> upload script ?>

<script>
$(document).ready(function(){
       <? if (!$mobile) print "\$.fn.bootstrapBtn = \$.fn.button.noConflict();"; ?>

	   $("#fileuploader").uploadFile({
	      url:"upload_file.php?id_pais=<? echo $id_pais; ?>",
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
<form name="modifcar_pais" class="form-wrapper" action="pais_editar_procesar.php" method="POST">
<table class="tabla_simple">
<?
/*
$query="SELECT * FROM paises WHERE id_pais='$id_pais'";
$stmt = $db->query($query);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$pais=$row['pais'];
$activo=$row['activo'];*/

$pais=new pais($id_pais);

?>
<tr>
   <td>Id
   <td><? echo $pais->id_pais ?>
<tr>
   <td>País
   <td><input type="text" name="pais" value="<?echo $pais->pais ?>" pattern="[a-z,A-Z,,'á','é','í','ó','ú´','Á','É','Í','Ó','Ú',,'ñ','Ñ',' ']*">
<tr>
   <td>Activo
   <td><input type="checkbox" name="activo" <? if ($pais->activo) print "CHECKED";?>>
<tr>
   <td>Bandera
   <td>
			   <center><img src="<? echo $pais->get_imagen() ?>" class="bandera_big" id="imagen_subida">
	<br><div id="fileuploader">Cargar nueva imagen</div></center>

<tr>
   <td colspan="2" style="text-align: center;"><input type="submit" class="submit" value="Cambiar">
</center>
<input type="hidden" name="id_pais" value="<?= $id_pais ?>">
</form>
</table>
<a href="index.php?accion=listar_paises" alt="Volver" title="Volver"><img src="imagenes/back.png" class="icono"></a>