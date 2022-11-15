<?php

function extension_imagen($id_equipo)
{
// detectar la extensión de la banderas
if (file_exists("imagenes/logos_equipos/".$id_equipo.".png"))
   $extension=".png";
else if (file_exists("imagenes/logos_equipos/".$id_equipo.".PNG"))
   $extension=".PNG";
else if (file_exists("imagenes/logos_equipos/".$id_equipo.".jpg"))
    $extension=".jpg";
else if (file_exists("imagenes/logos_equipos/".$id_equipo.".JPG"))
    $extension=".JPG";
else if (file_exists("imagenes/logos_equipos/".$id_equipo.".bmp"))
    $extension=".bmp";
else if (file_exists("imagenes/logos_equipos/".$id_equipo.".BMP"))
    $extension=".BMP";

//    print "<br>ext=$extension<br>";

return $extension;
}

function extension_imagen_evento($id_evento)
{
// detectar la extensión de la banderas
if (file_exists("imagenes/logos_eventos/".$id_evento.".png"))
   $extension=".png";
else if (file_exists("imagenes/logos_eventos/".$id_evento.".PNG"))
   $extension=".PNG";
else if (file_exists("imagenes/logos_eventos/".$id_evento.".jpg"))
    $extension=".jpg";
else if (file_exists("imagenes/logos_eventos/".$id_evento.".JPG"))
    $extension=".JPG";
else if (file_exists("imagenes/logos_eventos/".$id_evento.".jpeg"))
    $extension=".jpeg";
else if (file_exists("imagenes/logos_eventos/".$id_evento.".JPEG"))
    $extension=".JPEG";
else if (file_exists("imagenes/logos_eventos/".$id_evento.".bmp"))
    $extension=".bmp";
else if (file_exists("imagenes/logos_eventos/".$id_evento.".BMP"))
    $extension=".BMP";

//    print "<br>ext=$extension<br>";

return $extension;
}


function extension_imagen_usuario($id_usuario)
{
// detectar la extensión de la banderas
if (file_exists("imagenes/logos_usuarios/".$id_usuario.".png"))
   $extension=".png";
else if (file_exists("imagenes/logos_usuarios/".$id_usuario.".PNG"))
   $extension=".PNG";
else if (file_exists("imagenes/logos_usuarios/".$id_usuario.".jpg"))
    $extension=".jpg";
else if (file_exists("imagenes/logos_usuarios/".$id_usuario.".JPG"))
    $extension=".JPG";
else if (file_exists("imagenes/logos_usuarios/".$id_usuario.".bmp"))
    $extension=".bmp";
else if (file_exists("imagenes/logos_usuarios/".$id_usuario.".BMP"))
    $extension=".BMP";
else if (file_exists("imagenes/logos_usuarios/".$id_usuario.".gif"))
    $extension=".gif";
else if (file_exists("imagenes/logos_usuarios/".$id_usuario.".GIF"))
    $extension=".GIF";

return $extension;
}

?>
