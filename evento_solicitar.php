<?
session_start();
include 'includes/_Policy.php';
if ($mobile){   $size=30;
}else
   $size=40;
?>

<center>
<br>
<form name="evento_solicitar" class="form-wrapper" action="evento_solicitar_procesar.php" method="POST" onsubmit="return validar_form(); return false;">
<table class="tabla_simple">
<tr>
   <td>Nombre del Evento
<? if ($mobile) print "<tr>"; ?>
   <td title="Solamente se aceptan letras u números"><input type="text" size="<?= $size ?>" name="evento" pattern="[a-z,A-Z,0-9,á,é,í,ó,ú,Á,É,Í,Ó,Ú,' ',-]*" required>
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
   <td title="Solo usar números y letras"><textarea cols="<?= $size ?>" rows="5" name="descripcion" pattern="[a-z,A-Z,0-9,' ','-']*"></textarea>
<tr>
  <td>Máx usuarios
<? if ($mobile) print "<tr>"; ?>
  <td title="Máximo número de usuarios. 0 para ilimitado"><input type="number" name="max_usuarios" min="0" max="10000" pattern="[0-9]*" value="0" required>
<tr>
  <td>Evento público
<? if ($mobile) print "<tr>"; ?>
  <td title="el evento es visible a cualquier usuario y cualquier usuario puede solicitar participar">
     <input type="checkbox" name="publica">
<tr>
   <td>Fecha de inicio
<? if ($mobile) print "<tr>"; ?>
   <td><input type="date" name="fecha_inicio">
<tr>
   <td>Fecha de finalización
<? if ($mobile) print "<tr>"; ?>
   <td><input type="date" name="fecha_fin">
<tr>
<input type="hidden" name="id_evento" value="<?= $id_evento ?>">
   <td colspan="2" style="text-align: center;"><input type="submit" class="submit" value="Crear Evento">
</form>
<tr>



</table>
</center>