<?
session_start();
include 'includes/_Policy.php';

$id_evento=rand();

?>

<script>
function show_rondas(){
var xmlhttp;

var num_rondas = document.getElementById("num_rondas").value;

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

   var premios=document.getElementById('top_premios').value;
   var sel = document.getElementById('tipo_premios');
   var val_select = sel.options[sel.selectedIndex].value;
   var cadena_premios=document.getElementById("porcentaje_premios").value;
   var porcentaje=document.getElementById("porcentaje").value;
   var array_premios=cadena_premios.split(",");



if  (val_select=="sin_premios")
   return true;

if (val_select=="porcentaje" && (porcentaje=='' || porcentaje==0)){
   alert ("Por favor indique el porcentaje de premios a repartir");
   return false;
}



//  alert ("premios:"+premios+"  porcentajes="+array_premios.length);
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

   if (premios!=0 && sum_porcentaje!=porcentaje){
   	   alert ("La suma de porcentajes debe sumar "+porcentaje);
       return false;
   }

   return true;
}

</script>

<script>

function cambiar_plantilla(){   var plantilla=document.getElementById("plantilla");
   var params=document.getElementById("param_rondas");
   var id_plantilla = plantilla.options[plantilla.selectedIndex].value;
//   alert (id_plantilla);

  if  (id_plantilla==0){
	   params.style.display='table-row';
  }else{
	   params.style.display='none';
  }
}
</script>

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


<center>
<br>
<form name="evento_nuevo" action="evento_nuevo_procesar.php" method="POST" onsubmit="return validar_form(); return false;">
<table class="tabla_simple" id="tabla_parametros">
<tr>
   <th<? if (!$mobile) print " colspan=\"2\""; ?> style="text-align:center">Datos Generales

<tr>
   <td>Nombre del Evento
   <td title="Solamente se aceptan letras u números"><input type="text" size="40" name="evento" pattern="[a-z,A-Z,0-9,á,é,í,ó,ú,Á,É,Í,Ó,Ú,' ',-]*" required>
<tr>
   <td>Administrador
   <td><SELECT name="admin">
<?
   $query="SELECT id_usuario,usuario FROM usuarios ORDER BY usuario ASC";
   foreach($db->query($query) as $row) {
	   $id_usuario=$row['id_usuario'];
   	   $usuario=$row['usuario'];
   	   print "<option value=\"$id_usuario\">$usuario</option>\n";
   }
?>
   </SELECT>
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
   <td title="Solo usar números y letras"><textarea cols="40" rows="5" name="descripcion" pattern="[a-z,A-Z,0-9,' ','-']*"></textarea>
<tr>
  <td>Máx usuarios
  <td title="Máximo número de usuarios. 0 para ilimitado"><input type="number" name="max_usuarios" min="0" max="10000" pattern="[0-9]*" value="0" required>
<tr>
   <td>Valor
   <td><input type="number" name="valor" id="valor" min="0" pattern="[0-9]*" style="width:80px" required value="0">
<tr>
  <td>Validar usuarios
  <td title="Los usuarios deben ser validados por un adminsitrador antes de ser aceptados. Ej confirmación de pago"><input type="checkbox" name="conf_usuarios">
<tr>
  <td>Evento público
  <td title="el evento es visible a cualquier usuario y cualquier usuario puede solicitar participar">
     <input type="checkbox" name="publica">

<tr>
   <th<? if (!$mobile) print " colspan=\"2\""; ?> style="text-align:center">Premios

<style type="text/css">
tr.toggleable {
    display: none;
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
     <input type="number" name="top_premios" id="top_premios" min="0" max="10000" pattern="[0-9]*" value="<?= $top_premios ?>">
<tr class="toggleable" id="porcent">
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
  <td title="Máximo marcador permitido en una apuesta"><input type="number" name="max_marcador" min="0" max="30" pattern="[0-9]*" required>
<tr>
  <td>Máximo marcador automático
  <td title="Marcador máximo a usar en caso de que un usuario no registre apuesta y se le tenga que poner un marcador aleatorio"><input type="number" name="max_aleatorio" min="0" max="30" pattern="[0-9]*" required>
<tr>
   <td>Plantilla a usar
   <td><SELECT name="plantilla" id="plantilla" onchange="cambiar_plantilla()">
          <option value=0>Ninguna</option>
<?
     $query_plantilla="SELECT * FROM plantillas_eventos ORDER BY nombre_plantilla ASC";
     foreach($db->query($query_plantilla) as $row_plantilla) {     	   $id_evento_plantilla=$row_plantilla['id_evento'];
     	   $nombre_plantilla=$row_plantilla['nombre_plantilla'];
     	   print "<option value=\"$id_evento_plantilla\">$nombre_plantilla</option>\n";
     }
?>
   </SELECT>
<tr id="param_rondas">
   <td>Rondas
   <td title="Cuantas rondas va a tener el evento y como se llama cada una">
   <input type="number" name="num_rondas" id="num_rondas" value="1" min=1 max=20 step=1 required onchange="show_rondas()";>
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

   for ($i=1 ; $i<=1 ; $i++){  //por ahora solo pintar una ronda

//seleccionar el nombre de la ronda existente
   $query_r="SELECT * FROM rondasxevento WHERE id_evento='$id_evento' AND num_ronda='$i'";
   $stmt_r = $db->query($query_r);
   $row_r = $stmt_r->fetch(PDO::FETCH_ASSOC);
   $label_ronda=$row_r['nombre'];




   	   print "Ronda $i :";
   	   print "<SELECT name=\"ronda$i\">\n";
       foreach ($ronda as $label){
       	   print "<option";
       	   if ($label_ronda==$label) print " SELECTED";
       	   print ">$label</option>\n";
       }


   	   print "</SELECT>&nbsp;&nbsp;<input type=\"number\" name=\"gruposronda$i\" min=\"1\" max=\"16\" value=\"1\" maxlength=\"2\" step=\"1\">Grupos<br>\n";
   }
?>
   </div>
</tr>
<tr>
   <td>Logo
   <td>
			   <center><img src="" width="120" height="120" id="imagen_subida">
	    <br><div id="fileuploader">Cargar nueva imagen</div></center>

<tr>
<input type="hidden" name="id_evento" value="<?= $id_evento ?>">
   <td colspan="2" style="text-align: center;"><input type="submit" value="Crear Evento">
</form>
<tr>



</table>
</center>