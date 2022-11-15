<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>

<script>
function reload_results()
{
   type = $('#type').val();
   id = $('#torneo').val();

   alert (type + "**" + id);

  $.ajax({

     type: "GET",
     url: 'widget_resultados_ajax.php',
     data: 'id=' + id + '&type=' + type,
     success: function(data) {
           // data is ur summary
          $('#contenido_widget_resultados').html(data);
     }

   });

}
</script>

<div>
   <span>Selecciona torneo</span>
   <br>
   <select name="torneo" id="torneo" onchange="reload_results();">
       <option value="42">Colombia</option>
       <option value="26">Argentina</option>
       <option value="1">España</option>
       <option value="27">Champions League</option>
   </select>
</div>
<br>
<div>
  <span>Selecciona el tipo de widget</span>
  <br>
  <select name="type" id="type" onchange="reload_results();">
     <option  value="w_results">Resultados</option>
     <option  value="w_tables&completed=1">Clasificaciones</option>
     <option  value="w_tables&completed=0">Clasificaciones Reducidas</option>
  </select>
</div>
<br>

<div id="contenido_widget_resultados">
<script type="text/javascript">
<!--
padding = "5";
width = "220px";
bgColor = "#454545";
linkColor = "#FFFFFF";
textColorA = "#7CA726";
textColorB = "#52701B";
border = "1px solid #DDDDDD";
textFont = "12px Arial, Helvetica, Sans serif";
 //-->
</script>
<script src="http://www.resultados-futbol.com/scripts/api/api.php?tz=Europe/Madrid&format=widget&req=w_resultstables&key=41d55642f6613d82dae41eac2ecc4d65&league=1&group=1"></script>

<script language="javascript" src="http://www.resultados-futbol.com/scripts/api/api.php?key=24c3f336167d878a6dd01c257ab6640f&format=widget&req=w_results&category=1&grated=1&extra=logo&comments=1"></script>
<a target="_blank" style="margin-left:110px;font-size:10px;color:#426200;" href="http://www.resultados-futbol.com/">Resultados de F&uacute;tbol</a>


</div>
