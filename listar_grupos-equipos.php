<?
session_start();
include 'includes/_Policy.php';


?>



<script type="text/javascript">
<?// if (!$mobile) print "$.fn.bootstrapBtn = $.fn.button.noConflict();"; ?>
	$(document).ready(function() {
		$('#liga').on('input', function() {
			var cadena = $(this).val();
			if (cadena.length >= 3) {
				$(document).ajaxStart(function(){
                     //$("#loadingdiv").css("display","block");
                });
                $(document).ajaxComplete(function(){
                      //$("#loadingdiv").css("display","none");
                });
                $("#tabla_ligas").load("tabla_ligas.php?liga="+cadena);
			}
		});
     });



</script>

<br><br>
<center>
Liga: <input type="text" name="liga" id="liga">
<br><br>

<div id="tabla_ligas">
<? include 'tabla_ligas.php';  ?>
</div>
</center>


