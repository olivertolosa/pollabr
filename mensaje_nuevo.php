<?
session_start();
include 'includes/_Policy.php';

if ($mobile){
   $size=30;
}else
   $size=40;

?>
<center>
<br>
<form name="mensaje_nuevo" class="form-wrapper" action="mensaje_nuevo_procesar.php" method="POST">
<table class="tabla_simple">
<tr>
   <th>Fecha
<? if ($mobile) print "<tr>"; ?>
   <td><input type="date" name="fecha" required>
<tr>
   <th>Título
<? if ($mobile) print "<tr>"; ?>
   <td><input type="text" name="titulo" size="<?= $size ?>" required>
<tr>
   <th>Mensaje
<? if ($mobile) print "<tr>"; ?>
   <td><textarea rows="7" cols="<?= $size ?>" name="mensaje" required></textarea>
<tr>
    <th>Categoria
<? if ($mobile) print "<tr>"; ?>
    <td><SELECT name="categoria">
        <option>Noticia general</option>
        <option>Noticia de partidos</option>

    </SELECT>
<tr>
   <td colspan="2"><center><input type="submit" class="submit" value="Crear Mensaje"></center>
</form>
<tr>



</table>
</center>