<?php
class liga{   var $usuario;
   var $nombre;
   var $imagen;

   protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

   function get_nombre($id_liga){
      //obtener el nombre de la liga
      $query="SELECT grupo_equipos FROM grupos_equipos WHERE id_grupo_equipos='$id_liga'";
      $stmt=$this->db->query($query);
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $liga=$row['grupo_equipos'];

      return $liga;
   }

   function get_linkLS($id_liga){
      //obtener el usuario
      $query="SELECT link_LS FROM grupos_equipos WHERE id_grupo_equipos='$id_liga'";
      $stmt = $this->db->query($query);
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $link=$row['link_LS'];

      return $link;
   }

   function get_imagen($id_liga){      if ($id_liga==0){
         $imagen="imagenes/logos_eventos/default.png";
      }else{
         if (file_exists("imagenes/logos_ligas/".$id_liga.".png"))
            $extension=".png";
         else if (file_exists("imagenes/logos_ligas/".$id_liga.".PNG"))
            $extension=".PNG";
         else if (file_exists("imagenes/logos_ligas/".$id_liga.".jpg"))
            $extension=".jpg";
         else if (file_exists("imagenes/logos_ligas/".$id_liga.".JPG"))
            $extension=".JPG";
         else if (file_exists("imagenes/logos_ligas/".$id_liga.".jpeg"))
            $extension=".jpeg";
         else if (file_exists("imagenes/logos_ligas/".$id_liga.".JPEG"))
            $extension=".JPEG";
         else if (file_exists("imagenes/logos_ligas/".$id_liga.".gif"))
            $extension=".gif";
         else if (file_exists("imagenes/logos_ligas/".$id_liga.".GIF"))
            $extension=".GIF";
         else if (file_exists("imagenes/logos_ligas/".$id_liga.".bmp"))
            $extension=".bmp";
         else if (file_exists("imagenes/logos_ligas/".$id_liga.".BMP"))
            $extension=".BMP";

          $imagen="imagenes/logos_ligas/".$id_liga.$extension;
      }


      return $imagen;

   }

}
?>
