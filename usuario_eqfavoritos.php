<script>
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

<h2>Equipos Favoritos</h2>


<?
$id_usuario_favoritos=$id_usuario;

print "<center>";
include 'equipo_buscar.php';
print "</center>";

?>