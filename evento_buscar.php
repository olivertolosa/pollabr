<?
	$evento=$_POST['evento'];
	$admin=$_POST['admin'];
	$conf_usuarios=$_POST['conf_usuarios'];

include 'evento_funciones.php';
?>


<center>
* Si lo sabe, escriba el nombre del evento o del admnistrador, los campos son opcionales.<br>
* Puede dejar ambos campos en blanco para mostrar todos los eventos disponibles.<br><br>
<form name="evento_buscar" class="form-wrapper" action="index.php?accion=evento_buscar" method="POST">
<table class="tabla_simple">


<tr>
   <td>Nombre del Evento
   <td><input type="text" class="form-text" name="evento"  pattern="[a-z,A-Z,0-9,' ','_']*" value="<?= $evento ?>">
<tr>
   <td>Administrador
   <td><input type="text" class="form-text" name="admin" pattern="[a-z,A-Z,0-9,' ','_']*" value="<?= $admin ?>">
<tr>
  <td>Validar usuarios
  <td title="Los usuarios deben ser validados por un adminsitrador antes de ser aceptados. Eventos sin esta opción habilitada permiten el registro automático de cualqueir usuario">
     <input type="checkbox" name="conf_usuarios" <? if ($conf_usuarios) print "CHECKED"; ?>>

<tr>
   <td colspan="2" style="text-align: center;"><input type="submit" class="submit" value="Buscar Evento">

</table>
</form>
<br><br>
<?
if (isset($_POST['evento'])){
require_once 'includes/class_evento.php';
$event=new evento($db);
	print "<table class=\"tabla_con_encabezado\">
	   <th>Evento<th>Administrador<th>Participar\n";
   $query="SELECT e.id_evento,e.evento,u.usuario FROM eventos as e, usuarios as u WHERE ";
   if ($evento!=""){
      $query.="e.evento LIKE '%$evento%'";
      $or=true;//si se agranda el query toca poner el or
      $and=true;
   }
   if ($admin!=""){   	  if ($or)
   	     $query.=" OR ";
   	  $query.="e.admin IN (SELECT id_usuario FROM usuarios WHERE usuario LIKE '%$admin%')";
   	  $and=true;
   }
   if ($and)
      $query.=" AND";
   $query.=" publica='1' AND e.admin=u.id_usuario AND e.activo='1'";


//print "q=$query<br>";
   $stmt = $db->query($query);
   if ($stmt->rowCount()===0){
      print "<tr><td colspan=\"3\">No se encontraron resultados\n";
   }else{
      while ($row=$stmt->fetch(PDO::FETCH_ASSOC)){
         $evento=$row['evento'];
         $id_evento=$row['id_evento'];
         $admin=$row['usuario'];

         //validar si el usuario está inscrito en el evento
         $query_p="SELECT validado FROM usuariosxevento WHERE id_usuario='$id_usuario' AND id_evento='$id_evento'";
         $stmt_p = $db->query($query_p);
         $num=$stmt_p->rowCount();

         $participando=validar_inscripcion($id_usuario,$id_evento);

         if ($participando==0)
            $participar="<a href=\"index.php?accion=ingreso_evento&id_evento=$id_evento\">Ingresar</a>";
         else if ($participando==1)
               $participar="Participando";
         else if ($participando==2)
             $participar="Esperando Validación";


         print "<tr><td><img class=\"img_thumb\" src=\"".$event->get_imagen($id_evento)."\" style=\"width:40px; height:40px\">$evento<td>$admin<td>$participar";
      }
   }
   print "</table>";
}
?>

