<?
session_start();
include 'includes/_Policy.php';

?>
<script>
function cambiar_listado() {
    var sel=document.getElementById('validado');
    var valselect=sel.options[sel.selectedIndex].value;


  if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  } else {  // code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function() {
    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
      document.getElementById("listado_usuarios").innerHTML=xmlhttp.responseText;
    }
  }
  xmlhttp.open("GET","listar_usuariosxevento_tabla.php?id_evento=<?php echo $id_evento; ?>&validado="+valselect,true);
  xmlhttp.send();

}
</script>
<?

(isset($_POST['validado']))? $validado=$_POST['validado'] : $validado=-1;


//calcular el desitno del submit de la forma
if ($accion=="evento_admin" && $accion2=="listar_usuarios"){ //está administrando
    $destino="index.php?accion=evento_admin&id_evento=$id_evento&accion2=listar_usuarios";
}else{    $destino="index.php?accion=listar_usuarios";
}
?>
<center>
<?
if ($id_evento>0){  //averiguar si el evento maneja validación de usuarios
   $query="SELECT conf_usuarios FROM eventos WHERE id_evento='$id_evento'";
//print "q=$query";
   $stmt=$db->query($query);
   $row=$stmt->fetch(PDO::FETCH_ASSOC);
   $conf_usuarios=$row['conf_usuarios'];

   if ($conf_usuarios){  //si no se maneja validación no se pinta la forma
?>

<SELECT name="validado" id="validado" onchange="cambiar_listado();">
  <option value="-1"<?if ($validado==-1) print " SELECTED"; ?>>Todos</option>
  <option value=0<?if ($validado==0) print " SELECTED"; ?>>Sin Validar</option>
  <option value=1<?if ($validado==1) print " SELECTED"; ?>>Validados</option>
</SELECT>
<?
   }
}
?>
<br><br>
<div id="listado_usuarios">
<? include 'listar_usuariosxevento_tabla.php'; ?>

</div>

<script>
//cambiar_listado();
</script>


