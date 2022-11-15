<?php
function debug($variable,$valor,$mostrar=false){	if ($_SESSION['admin'] and $_SESSION['debug'] and $mostrar)      print "<br><span style=\"background-color: #FA5A7D;\">$variable : $valor\n\r</span><br>";

}


?>
