<?
session_start();
include 'includes/_Policy.php';

(isset($_POST['pagos']))? $pagos=$_POST['pagos'] : $pagos=-1;

?>
<script type="text/javascript">
<?// if (!$mobile) print "$.fn.bootstrapBtn = $.fn.button.noConflict();"; ?>
	$(document).ready(function() {
		$('#nombre').on('input', function() {
			var cadena = $(this).val();
			if (cadena.length >= 3) {				$('#usuario').val("");
				$(document).ajaxStart(function(){
                     //$("#loadingdiv").css("display","block");
                });
                $(document).ajaxComplete(function(){
                      //$("#loadingdiv").css("display","none");
                });
                $("#tabla_usuarios").load("usuario_tabla.php?nombre="+cadena);
			}
		});
		$('#usuario').on('input', function() {
			var cadena = $(this).val();
			if (cadena.length >= 3) {				$('#nombre').val("");
				$(document).ajaxStart(function(){
                     //$("#loadingdiv").css("display","block");
                });
                $(document).ajaxComplete(function(){
                      //$("#loadingdiv").css("display","none");
                });
                $("#tabla_usuarios").load("usuario_tabla.php?usuario="+cadena);
			}
		});
     });
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">

<script>
    function showDialog(id_usuario) {

        $('<div>').dialog({
            modal: true,
            open: function () {
                $(this).load('usuario_saldo_stats.php?id_usuario='+id_usuario);
            },
            close: function(event, ui) {
                    $(this).remove();
                },
            height: 540,
            width: 540,
            title: 'Saldo histórico',
            position: { my: 'top', at: 'top+50' },
        });

        return false;
    }

    </script>


<center>
Nombre: <input type="text" name="nombre" id="nombre">
<br><br>
Usuario: <input type="text" name="usuario" id="usuario">

<div id="tabla_usuarios">
<? include 'usuario_tabla.php';  ?>
</div>
</center>