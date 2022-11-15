<?
$id_evento=$_REQUEST['id_evento'];
$id_usuario=$_REQUEST['id_usuario'];

$confirmado=$_POST['confirmado'];

$query="SELECT * FROM eventos WHERE id_evento=:id_evento";
//print "q=$query<br>";
$stmt= $db->prepare($query);
$stmt->bindParam(':id_evento',$id_evento);
$stmt->execute();

$row=$stmt->fetch(PDO::FETCH_ASSOC);
$evento=$row['evento'];
$id_admin=$row['admin'];
$descripcion=$row['descripcion'];
$max_usuarios=$row['max_usuarios'];
$admin=$row['admin'];
$valor=$row['valor'];
$top_premios=$row['top_premios'];
$conf_usuarios=$row['conf_usuarios'];
$max_marcador=$row['max_marcador'];
$max_aleatorio=$row['max_aleatorio'];
$num_rondas=$row['num_rondas'];
$porcentaje_premios=$row['porcentaje_premios'];
$publica=$row['publica'];
$plantilla=$row['plantilla'];
$activo=$row['activo'];
$porcentaje=$row['porcentaje'];
$tipo_premios=$row['tipo_premios'];




?>
<center>
<? echo $_SESSION['msg']; unset($_SESSION['msg']); ?>
<script>
function show_rondas(){
var xmlhttp;

var num_rondas = document.getElementById("num_rondas").value;

if (num_rondas<1){
   alert ("Valor no válido");
   aux=document.getElementById("num_rondas_aux").value;
   document.getElementById("num_rondas").value=aux;
   return;
}

document.getElementById("num_rondas_aux").value=document.getElementById("num_rondas").value;
//alert ("num_rondas:"+num_rondas);

if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("rondas").innerHTML=xmlhttp.responseText;
    }

  }

xmlhttp.open("GET","rondas_nombres.php?id_evento=<?= $id_evento ?>&num_rondas="+num_rondas,true);
xmlhttp.send();

}
</script>

<script>
function validar_form(){
   var premios=document.getElementById("top_premios").value;
    var sel = document.getElementById('tipo_premios');
    var val_select = sel.options[sel.selectedIndex].value;
   var cadena_premios=document.getElementById("porcentaje_premios").value;
   var porcentaje=document.getElementById("porcentaje").value;
   var array_premios=cadena_premios.split(",");

	//alert (val_select);

if  (val_select=="sin_premios")
   return true;

if (val_select=="porcentaje" && (porcentaje=='' || porcentaje==0)){
	alert ("Por favor indique el porcentaje de premios a repartir");
   return false;
}



// alert ("premios:"+premios+"  porcentajes="+array_premios.length);
   if (premios!=0 && premios!=array_premios.length){
   	   alert ("Los premios no coinciden con el número de usuarios a premiar");
   	   return false;
   }

   //sumar los premios y validar que sumen lo mismo que está indicado
   var sum_porcentaje=0;
   for (i=0 ; i<array_premios.length ; i++){
   	   var porcentaje_parcial=parseInt(array_premios[i]);
   	   sum_porcentaje=sum_porcentaje+porcentaje_parcial;
   }

   if (val_select=="porcentaje" && (premios!=0 && sum_porcentaje!=porcentaje)){
   	   alert ("La suma de porcentajes debe sumar "+porcentaje);
       return false;
   }

<?
if ($plantilla!=0){
?>
	//validar si se seleccionó una plantilla
	var plantilla=document.getElementById('plantilla');
	var valor_plantilla=plantilla.options[plantilla.selectedIndex].value;

	if (valor_plantilla!=0){
	   if (confirm("Atención\n\rHa decidido usar una plantilla; Si desea modificar esta opción mas adelante deberá solicitarlo a un administrador. Esta seguro de haber seleccionado la plantilla correcta?"))
	      return true;
	   else
	      return false;
	}
<?
}
?>


	return true;

}

</script>

<script>
function cambiar_plantilla(){
	var plantilla=document.getElementById('plantilla');
	var valor_plantilla=plantilla.options[plantilla.selectedIndex].value;

	if (valor_plantilla!=0) {
	//code
		if (confirm("Atención\n\rSeleccionar una plantilla es una decisión irreversible; solo un Administrador puede modificar esta opción una vez elegida. Por favor valide que va a usar la plantilla deseada.")) {
         var plantilla=document.getElementById("plantilla");
         var params=document.getElementById("param_rondas");
         var id_plantilla = plantilla.options[plantilla.selectedIndex].value;
      //   alert (id_plantilla);

        if  (id_plantilla==0){
           params.style.display='table-row';
        }else{
           params.style.display='none';
        }
	   }
	}
}
</script>

<script>
function mostrar_campos_plantilla(){
	var plantilla=document.getElementById("campos_plantilla");
	plantilla.style.display='block';
}
</script>


<? if (!$mobile){ ?>
<link href="css/uploadfile.css" rel="stylesheet">
<script src="includes/jquery.uploadfile.min.js"></script>
<? // http://hayageek.com/docs/jquery-upload-file.php.....--> upload script ?>

<script>
$(document).ready(function(){
       <? if (!$mobile) print "\$.fn.bootstrapBtn = \$.fn.button.noConflict();"; ?>

	   $("#fileuploader").uploadFile({
	      url:"upload_file.php?id_evento=<? echo $id_evento; ?>",
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

<?
}


if ($mobile){
   $size=30;
}else
   $size=40;
?>

<form name="evento_detalle" method="POST" action="evento_detalle_procesar.php" onsubmit="return validar_form();return false;">
<table class="tabla_simple" id="tabla_parametros">
<tr>
   <th<? if (!$mobile) print " colspan=\"2\""; ?> style="text-align:center">Datos Generales
<tr>
   <td>Nombre del Evento
<? if ($mobile) print "<tr>"; ?>
   <td><input type="text" size="<?= $size ?>" name="evento" value="<?= $evento ?>" pattern="[a-z,A-Z,0-9,á,é,í,ó,ú,Á,É,Í,Ó,Ú,' ',-]*" required>
<?
//si el usuario es administrador puede cambiar el admin...de lo contrario no
if ($admin){
?>
<tr>
   <td>Administrador
<? if ($mobile) print "<tr>"; ?>
   <td><SELECT name="admin">
<?
   $query="SELECT id_usuario,usuario FROM usuarios ORDER BY usuario ASC";
   $stmt= $db->prepare($query);
	$stmt->execute();

   while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
   	   $id_usuario=$row['id_usuario'];
   	   $usuario=$row['usuario'];
   	   print "<option value=\"$id_usuario\"";
   	   if ($id_usuario==$id_admin) print " SELECTED";
   	   print ">$usuario</option>\n";
   }
?>
   </SEECT>
<?
}
?>
<tr>
   <td>Descripción
<script>
function validate() {
    var val = document.getElementById('desc').value;
//    alert (val);
    if (/[^a-z,A-Z,0-9,'.','\n','\r',' ']+/g.test(val)) {
        alert('Contenido no válido!');
        document.getElementById('desc').focus();
        return false;
    }
    return true;
}
</script>

<? if ($mobile) print "<tr>"; ?>
   <td title="Solo usar números y letras"><textarea cols="<?= $size ?>" rows="5" name="descripcion" pattern="[a-z,A-Z,0-9,' ','-']*" id="desc"><?= $descripcion ?></textarea>

<tr>
  <td>Máx usuarios
<? if ($mobile) print "<tr>"; ?>
  <td title="Máximo número de usuarios. 0 para ilimitado">
     <input type="number" name="max_usuarios" min="0" max="10000" pattern="[0-9]*" value="<?= $max_usuarios ?>"required>
<tr>
   <td>Valor
<? if ($mobile) print "<tr>"; ?>
   <td><input type="number" name="valor" id="valor" min="0" pattern="[0-9]*" style="width:80px" required value="<?= $valor ?>" required>
<tr>
  <td>Validar usuarios
<? if ($mobile) print "<tr>"; ?>
  <td title="Los usuarios deben ser validados por un adminsitrador antes de ser aceptados. Ej confirmación de pago">
     <input type="checkbox" name="conf_usuarios" <? if ($conf_usuarios) print "CHECKED"; ?>>
<tr>
  <td>Evento público
<? if ($mobile) print "<tr>"; ?>
  <td title="el evento es visible a cualquier usuario y cualquier usuario puede solicitar participar">
     <input type="checkbox" name="publica" <? if ($publica) print "CHECKED"; ?>>
<tr>
  <td>Activo
<? if ($mobile) print "<tr>"; ?>
  <td title="el evento es visible a cualquier usuario y cualquier usuario puede solicitar participar">
     <input type="checkbox" name="activo" <? if ($activo) print "CHECKED"; ?>>

<tr>
   <th<? if (!$mobile) print " colspan=\"2\""; ?> style="text-align:center">Premios


<?
//definir la visibilidad de los campos
($tipo_premios=="sin_premios")? $visibilidad="none" : $visibility="table-row";
?>
<style type="text/css">
tr.toggleable {
    display: <?= $visibilidad ?>;
}
</style>

<script type="text/javascript" language="javascript">
function mostrar_premios(){
    var sel = document.getElementById('tipo_premios');
    var val = sel.options[sel.selectedIndex].value;
    var i = 0;
    table = document.getElementById('tabla_parametros');
    var toggles = table.getElementsByTagName('tr');
    while (tr = toggles.item(i++)){
    	if (tr.className == 'toggleable')
                if (val=="sin_premios")
                   tr.style.display = 'none';
                else
                   tr.style.display = 'table-row';
    }

    if (val=="fijo"){
    	document.getElementById('porcent').style.display='none';
    }
}
</script>


<tr>
   <td>Premios por:
<? if ($mobile) print "<tr>"; ?>
   <td><SELECT name="tipo_premios" id="tipo_premios" onchange="mostrar_premios()">
        <option value="sin_premios"<? if ($tipo_premios=="sin_premios") print " SELECTED"; ?>>Sin Premios</option>
        <option value="porcentaje"<? if ($tipo_premios=="porcentaje") print " SELECTED"; ?>>Porcentaje</option>
        <option value="fijo"<? if ($tipo_premios=="fijo") print " SELECTED"; ?>>Valores fijos</option>

   </SELECT>

<tr class="toggleable">
  <td>Usuarios a premiar
<? if ($mobile) print "<tr>"; ?>
  <td title="Número de usuarios en pimeras posiciones a premiar">
     <input type="number" name="top_premios" id="top_premios" min="0" max="10000" pattern="[0-9]*" value="<?= $top_premios ?>" required>
<tr class="toggleable" id="porcent" <?if ($tipo_premios=="fijo") print "style=\"display:none\""; ?>>
  <td title="El valor a repartir como porcentaje de lo recogido">Porcentaje de premios
  <td><input type="number" name="porcentaje" id="porcentaje" min="0" max="100" pattern="[0-9]*" value="<?= $porcentaje ?>" default="100">
<tr class="toggleable">
  <td>Repartición de premios
<? if ($mobile) print "<tr>"; ?>
  <td title="El valor debe sumar lo indicado en el campo "Porcentaje de premios. Los valores deben estar separados por coma">
     <input type="text" name="porcentaje_premios" id="porcentaje_premios" size="30" pattern="[0-9,',']*" placeholder="Valores separados por coma" value="<?= $porcentaje_premios ?>">

<tr>
   <th<? if (!$mobile) print " colspan=\"2\""; ?> style="text-align:center">Marcadores

<tr>
  <td>Máximo marcador permitido
<? if ($mobile) print "<tr>"; ?>
  <td title="Máximo marcador permitido en una apuesta">
     <input type="number" name="max_marcador" min="0" max="30" pattern="[0-9]*" value="<?= $max_marcador ?>"  required>
<tr>
  <td>Máximo marcador automático
<? if ($mobile) print "<tr>"; ?>
  <td title="Marcador máximo a usar en caso de que un usuario no registre apuesta y se le tenga que poner un marcador aleatorio">
     <input type="number" name="max_aleatorio" min="0" max="30" pattern="[0-9]*" value="<?= $max_aleatorio ?>" required>
   <input type="hidden" name="id_evento" value="<?= $id_evento ?>">

<input type="hidden" name="accion2" value="<?= $accion2 ?>">
<tr>
   <td>Plantilla a usar
	<td>
<?
if ($plantilla==0){
?>
   <SELECT name="plantilla" id="plantilla" onchange="cambiar_plantilla()">
          <option value=0>Ninguna</option>
<?
     $visibilidad_rondas="table_row";

     $query_plantilla="SELECT * FROM plantillas_eventos WHERE id_evento<>:id_evento ORDER BY nombre_plantilla ASC";
     $stmt_plantilla= $db->prepare($query_plantilla);
	 $stmt_plantilla->bindParam(':id_evento',$id_evento);
	 $stmt_plantilla->execute();

     while ($row_plantilla=$stmt_plantilla->fetch(PDO::FETCH_ASSOC)){
     	   $id_evento_plantilla=$row_plantilla['id_evento'];
     	   $nombre_plantilla=$row_plantilla['nombre_plantilla'];
     	   print "<option value=\"$id_evento_plantilla\"";
     	   if ($plantilla==$id_evento_plantilla){
     	   	   print " SELECTED";
     	   	   $visibilidad_rondas="none";
     	   }
     	   print ">$nombre_plantilla</option>\n";
     }
?>
   </SELECT>
<?
}else{
	$query_plantilla="SELECT nombre_plantilla FROM plantillas_eventos WHERE id_evento=:plantilla";
     $stmt_plantilla= $db->prepare($query_plantilla);
	 $stmt_plantilla->bindParam(':plantilla',$plantilla);
	 $stmt_plantilla->execute();
	$row_plantilla=$stmt_plantilla->fetch(PDO::FETCH_ASSOC);
	$nombre_plantilla=$row_plantilla['nombre_plantilla'];
	print "$nombre_plantilla";
	$visibilidad_rondas="none";
	print "<input type=\"hidden\" name=\"plantilla\" value=\"$plantilla\">";
}
?>
<tr id="param_rondas" style="display: <?= $visibilidad_rondas ?>">
   <td>Rondas
<? if ($mobile) print "<tr>"; ?>
   <td title="Cuantas rondas va a tener el evento, como se llama cada una, cuantos grupos hay en cada una, se maneja eliminación directa">
   <input type="number" name="num_rondas" id="num_rondas" value="<?= $num_rondas ?>" min=1 max=20 step=1 required onchange="show_rondas()";>
   <input type="hidden" name="num_rondas_aux" id="num_rondas_aux" value="<?= $num_rondas ?>">
   <div id="rondas">
<?

$ronda[]="Primera Fase";
$ronda[]="Segunda Fase";
$ronda[]="Tercera Fase";
$ronda[]="Cuarta Fase";
$ronda[]="Todos contra Todos";
$ronda[]="Fase de Grupos";
$ronda[]="Dieciseisavos de Final";
$ronda[]="Octavos de Final";
$ronda[]="Cuartos de Final";
$ronda[]="Semifinal";
$ronda[]="Tercero y Cuarto";
$ronda[]="Final";
   for ($i=1 ; $i<=$num_rondas ; $i++){

//seleccionar el nombre de la ronda existente
   $query_r="SELECT * FROM rondasxevento WHERE id_evento=:id_evento AND num_ronda=:i";
   $result_r= $db->prepare($query_r);
	$result_r->bindParam(':id_evento',$id_evento);
	$result_r->bindParam(':i',$i);
	$result_r->execute();

   $row_r=$result_r->fetch(PDO::FETCH_ASSOC);
   $label_ronda=$row_r['nombre'];
   $grupos=$row_r['grupos'];




   	   print "Ronda $i :";
   	   print "<SELECT name=\"ronda$i\">\n";
       foreach ($ronda as $label){
       	   print "<option";
       	   if ($label_ronda==$label) print " SELECTED";
       	   print ">$label</option>\n";
       }


   	   print "</SELECT>&nbsp;&nbsp;<input type=\"number\" name=\"gruposronda$i\" min=\"1\" max=\"16\" value=\"$grupos\" maxlength=\"2\" step=\"1\" required>Grupos";
       if ($grupos==1) print "<input type=\"checkbox\" name=\"elimdirecta$i\" id=\"elimdirecta$i\" title=\" Eliminación Directa\">";
   	   print "<br>\n";
   }
?>
   </div>
<tr>
<tr>
   <td>Logo
<? if ($mobile) print "<tr>"; ?>

<?
$extension=extension_imagen_evento($id_evento);
$logo_evento ="imagenes/logos_eventos/$id_evento$extension";
?>
   <td><center><img src="<?= $logo_evento ?>" width="120" height="120" id="imagen_subida">
<? if (!$mobile){ ?>	<br><div id="fileuploader">Cargar nueva imagen</div> <? } ?>
       </center>
<tr>
   <td colspan="2" style="text-align: center;"><input type="submit" value="Modificar Evento"></form>
</table>

<br>
<?
   if ($admin){
?>
     <a href="javascript:mostrar_campos_plantilla()">Volver Plantilla</a>
     <div id="campos_plantilla" style="display:none;">
         <form name="crear_plantilla" method="POST" action="crear_plantilla.php">
         <table class="tabla_simple">
            <tr><th>Nombre
                <td><input type="text" name="nombre_plantilla">
                <input type="hidden" name="id_evento" value="<?= $id_evento ?>">
             <tr><td colspan="2" style="text-align:center"><input type="submit" value="Crear Plantilla">
          </table>
         </form>
     </div>
<?
   }
?>
</center>
