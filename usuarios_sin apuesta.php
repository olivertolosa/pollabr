<script type="text/javascript" language="JavaScript">
<!-- Copyright 2006,2007 Bontrager Connection, LLC
// http://www.willmaster.com/
// Version: July 28, 2007
var cX = 0; var cY = 0; var rX = 0; var rY = 0;
function UpdateCursorPosition(e){ cX = e.pageX; cY = e.pageY;}
function UpdateCursorPositionDocAll(e){ cX = event.clientX; cY = event.clientY;}
if(document.all) { document.onmousemove = UpdateCursorPositionDocAll; }
else { document.onmousemove = UpdateCursorPosition; }
function AssignPosition(d) {
if(self.pageYOffset) {
	rX = self.pageXOffset;
	rY = self.pageYOffset;
	}
else if(document.documentElement && document.documentElement.scrollTop) {
	rX = document.documentElement.scrollLeft;
	rY = document.documentElement.scrollTop;
	}
else if(document.body) {
	rX = document.body.scrollLeft;
	rY = document.body.scrollTop;
	}
if(document.all) {
	cX += rX;
	cY += rY;
	}
d.style.left = (300) + "px";
d.style.top = (cY-50) + "px";
}
function HideContent(d) {
if(d.length < 1) { return; }
document.getElementById(d).style.display = "none";
}
function ShowContent(d) {
if(d.length < 1) { return; }
var dd = document.getElementById(d);
AssignPosition(dd);
dd.style.display = "block";
}
function ReverseContentDisplay(d) {
if(d.length < 1) { return; }
var dd = document.getElementById(d);
AssignPosition(dd);
if(dd.style.display == "none") { dd.style.display = "block"; }
else { dd.style.display = "none"; }
}
//-->
</script>

<script type="text/javascript">
<!--
 function seleccionar_sel(obj)
  { var selected=document.sel_fecha.fecha.options[obj.selectedIndex].value;
   // invalid selection
    if(selected=="999"){
       return;
    }
     // ------------
   // valid selection
    else{
        document.sel_fecha.submit();
     }
  }
//-->
</script>
<?
session_start();
include 'includes/_Policy.php';
$fecha=$_POST['fecha'];

?>
<center>
<form name="sel_fecha" method="POST" action="index.php?accion=usuarios_sin_apuesta">
<SELECT name="fecha" id="fecha" onchange="seleccionar_sel(this)">
   <option value="999">Seleccione una fecha</option>
<?
  $query="SELECT DISTINCT fecha FROM partidos ORDER BY fecha ASC";
  $result = mysql_query($query) or die(mysql_error());
  while($row=mysql_fetch_assoc($result)){     $fecha_partido=$row['fecha'];
     print "<option";
     if ($fecha==$fecha_partido) print " SELECTED";
     print ">$fecha_partido</option>";
  }
  mysql_free_result($result);
?>
   </optgroup>
</SELECT>
</form>

<?
if (isset($fecha) and $fecha!=""){
//print "fecha=$fecha";?>

<table class="tabla_con_encabezado">
<tr>
   <th>#<th>login<th>Nombre
<?php



$query="SELECT DISTINCT id_usuario,usuario,nombre
        FROM usuarios
        WHERE id_usuario NOT IN(SELECT id_usuario FROM apuestas
           WHERE id_partido IN (SELECT id_partido FROM partidos WHERE fecha='$fecha')) ORDER BY nombre ASC";
//print "q=$query<br>";

$result = mysql_query($query) or die(mysql_error());
$cadena="";
$num=mysql_num_rows($result);
//print "num=$num<br>";
$i=1;
//lista para correo
$lista_correo="";
while($row=mysql_fetch_assoc($result)){
   $id_usuario=$row['id_usuario'];
   $usuario=$row['usuario'];
   $nombre=$row['nombre'];
   $lista_correo.=$usuario.";";

   print "<tr><td>$i<td onmouseover=\"ShowContent('$usuario'); return true;\" onmouseout=\"HideContent('$usuario'); return true;\"><a href=\"index.php?accion=editar_usuario&id_usuario=$id_usuario\">$usuario
              <td>";
print "<div\n";
print "   id=\"$usuario\"\n";
print "   style=\"display:none;\n";
print "      position:absolute;\n";
print "      border-style: solid;\n";
print "      background-color: white;\n";
print "      padding: 5px;\">\n";
print "<img width=\"85\" height=\"85\" src=\"http://infoapp/fotos/$usuario.jpg\">";
print "</div>\n";

   print "           $nombre\n";
   $i++;
}
print "</table><br><br>";
  $lista_correo=substr($lista_correo,0,strlen($lista_correo)-1);
  print "lista correo=$lista_correo";
}
?>
</center>