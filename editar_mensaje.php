<?
session_start();
include 'includes/_Policy.php';

$id_mensaje=$_GET['id_mensaje'];
?>
<script type="text/javascript">
function confirmar_borrado(){   if (confirm("Está seguro de borrar este mensaje?")){   	  top.location = "editar_mensaje_procesar.php?id_mensaje=<?= $id_mensaje ?>&accion=eliminar_mensaje"
   }

}

</script>

<center>
<?
if (isset ($_SESSION['msg'])){
   echo $_SESSION['msg']."<br><br>";
   unset ($_SESSION['msg']);
}
?>
<form name="modifcar_mensaje" class="form-wrapper" action="editar_mensaje_procesar.php" method="POST">
<table class="tabla_simple">
<?php


$query="SELECT * FROM mensajes WHERE id_mensaje='$id_mensaje'";
$stmt = $db->query($query);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$fecha=$row['fecha'];
$titulo=$row['titulo'];
$mensaje=$row['mensaje'];
$categoria=$row['categoria'];
$mensaje=str_replace("<br />","",$mensaje);
$mensaje=str_replace('\"','"',$mensaje);


if ($mobile){
   $size=30;
}else
   $size=40;

?>
<script type="text/javascript" src="includes/calendarDateInput_es.js"></script>

<tr>
   <th>Id
<? if ($mobile) print "<tr>"; ?>
   <td><?= $id_mensaje ?>
<tr>
   <th>Fecha
<? if ($mobile) print "<tr>"; ?>
   <td><input type="date" name="fecha" value="<?= $fecha ?>">
<tr>
   <th>Título
<? if ($mobile) print "<tr>"; ?>
   <td><input type="text" name="titulo" value="<?= $titulo ?>" size="<?= $size ?>" required>
<tr>
   <th>Mensaje
<? if ($mobile) print "<tr>"; ?>
   <td><textarea rows="7" cols="<?= $size ?>" name="mensaje" required><?= $mensaje ?></textarea>
<tr>
   <th>Categoria
<? if ($mobile) print "<tr>"; ?>
    <td><SELECT name="categoria">
        <option<? if ($categoria=="Noticia general") print " SELECTED"; ?>>Noticia general</option>
        <option<? if ($categoria=="Noticia de partidos") print " SELECTED"; ?>>Noticia de partidos</option>

    </SELECT>
<tr>
   <input type="hidden" name="id_mensaje" value="<?= $id_mensaje ?>">
   <td colspan="2" align="center"><center><input type="submit" class="submit" value="Modificar"></center>
<tr>
   <td colspan="2" align="center"><center><input type="button" class="submit" value="Eliminar" onclick="confirmar_borrado()";></center>
</form>
<tr>
</table>
</center>