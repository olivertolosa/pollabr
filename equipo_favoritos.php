<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <script type="text/javascript">

    function showDialog(id_equipo) {

        $('<div>').dialog({
            modal: true,
            open: function () {
                $(this).load('carga_eqfavorito_usuarios.php?id_equipo='+id_equipo);
            },
            close: function(event, ui) {
                    $(this).remove();
                },
            height: 440,
            width: 340,
            title: 'Usuarios',
            position: { my: 'top', at: 'top+150' },
        });

        return false;
    }

    </script>

<h2>Equipos Favoritos</h2>

<br>
<center>
<table class="tabla_simple">
<tr><th>#<th colspan="2">Equipo<th>Favoritos
<?

require_once 'includes/class_equipo.php';
$eq=new equipo($db);

$query="SELECT count(id_equipo) as cuantos, id_equipo FROM equipos_favoritos GROUP BY id_equipo order by cuantos desc;";
$stmt = $db->query($query);
$i=1;
while ($row=$stmt->fetch(PDO::FETCH_ASSOC)){   $id_equipo=$row['id_equipo'];
   $cuantos=$row['cuantos'];
   print "<tr><td>$i<td><img src=\"".$eq->get_imagen($id_equipo)."\" class=\"img_thumb\">
          <td><p style=\"cursor:pointer\" onclick=\"showDialog($id_equipo)\">".$eq->get_nombre($id_equipo)."</p>
          <td style=\"text-align:center\">$cuantos";
   $i++;
}


?>
</table>
</center>