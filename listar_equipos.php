<?
session_start();
include 'includes/_Policy.php';

require_once 'includes/class_equipo.php';
$eq=new equipo($db);

$grupo_equipos=$_REQUEST['grupo_equipos'];


?>

<h2>Listado de equipos</h2>

<br>
<p>Esta es la lista de todos los equipos disponibles para ser usados en la creación de eventos.
<br><br>Si encuentras una inconsistencia o algún dato equivocado <a href="index.php?accion=contacto">avísanos</a>.</p>

<br><br>
<center>
<form name="sel_equipo" method="POST" action="index.php?accion=listar_equipos">
Liga:

<SELECT id="grupo_equipos" name="grupo_equipos" title="Selecciona una liga">
   <option value="999">Seleccione una liga</option>
<? if ($admin){ ?>
      <option value="-1"<? if ($grupo_equipos==-1) print " SELECTED"; ?>>Todos</option>
<?
}
   $query="SELECT * FROM grupos_equipos ORDER BY grupo_equipos ASC";
   foreach($db->query($query) as $row) {   	   $id_grupo_equipo=$row['id_grupo_equipos'];
   	   $nombre_grupo=$row['grupo_equipos'];
   	   print "<option value=\"$id_grupo_equipo\"";
   	   if ($grupo_equipos==$id_grupo_equipo) print " SELECTED";
   	   print ">$nombre_grupo</option>\n";
   }
?>
</SELECT>
</form>
<br>
O busca por el nombre del equipo
<br>

<input type="text" id="nombre_equipo">
<br><br>

<script type="text/javascript">
	$(document).ready(function() {
		$('#nombre_equipo').on('input', function() {
			var cadena = $(this).val();
			if (cadena.length >= 3) {				$("#grupo_equipos").val(999);
				  $(document).ajaxStart(function(){
                     //$("#loadingdiv").css("display","block");
                  });
                  $(document).ajaxComplete(function(){
                      //$("#loadingdiv").css("display","none");
                  });
                  $("#resultados").load("equipo_buscar.php?cadena="+cadena);
			}
		});
		$('#grupo_equipos').on('change', function() {			 $("#nombre_equipo").val('');
		     var liga = $(this).val();
             $(document).ajaxStart(function(){
                     //$("#loadingdiv").css("display","block");
                  });
             $(document).ajaxComplete(function(){
                      //$("#loadingdiv").css("display","none");
                  });
             $("#resultados").load("equipo_buscar.php?id_liga="+liga);
        });

        $('#grupo_equipos')
             .val('<?= $grupo_equipos ?>')
             .trigger('change');



	});

	function favorito(id_usuario,id_equipo){
        var req=$.ajax({
                    type: "GET",
                    url: "equipo_favorito.php",
                    data: "id_usuario=" + id_usuario + "&id_equipo=" + id_equipo,
                 });

        var success=function(resp){
         console.log("*"+resp+"*");
//        		alert (resp);
        		if (resp=='1'){
        		   img="favorito.png";
                   alt_img="favorito_del.png";
                   texto="Remover de favoritos";
                   //alert ("fav");
        		}else{
        		   img="favorito_no.png";
                   alt_img="favorito_add.png";
                   texto="Agregar de favoritos";
                   //alert ("NO fav");
        		}
        		var contenido="<img class=\"img_favorito\" src=\"imagenes/"+img+"\" title=\""+texto+"\" onmouseover=\"this.src='imagenes/"+alt_img+"';\" onmouseout=\"this.src='imagenes/"+img+"';\">";

                setTimeout(function() {       //tocó ponerle un delay xq no estaba tomando la respuesta

                    $("#fav_"+id_equipo).html(contenido);
                 }, 500);

         };

         var err = function( req, status, err ) {
                       console.log('something went wrong');
                    };


         req.done(success);

	}


</script>

<div id="loadingdiv" style="position: relative; top: 50px; display: none;">
             <img src="imagenes/loading.gif" style="width:35px;height:35px;">
</div>

<div id="resultados">

</div>
</center>
