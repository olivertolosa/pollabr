<?php
class equipo{   var $usuario;
   var $nombre;
   var $imagen;

   protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

   function get_nombre($id_equipo){
      //obtener el usuario
      $query="SELECT equipo FROM equipos WHERE id_equipo='$id_equipo'";
      $stmt = $this->db->query($query);
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $equipo=$row['equipo'];

      return $equipo;
   }

   function get_id_liga($id_equipo){
      //obtener el usuario
      $query="SELECT id_grupo_equipos FROM equipos WHERE id_equipo='$id_equipo'";
      $stmt = $this->db->query($query);
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $id_liga=$row['id_grupo_equipos'];

      return $id_liga;
   }

   function get_imagen($id_equipo){

   if (file_exists("imagenes/logos_equipos/".$id_equipo.".png"))
         $extension=".png";
      else if (file_exists("imagenes/logos_equipos/".$id_equipo.".PNG"))
         $extension=".PNG";
      else if (file_exists("imagenes/logos_equipos/".$id_equipo.".jpg"))
         $extension=".jpg";
      else if (file_exists("imagenes/logos_equipos/".$id_equipo.".gif"))
         $extension=".gif";
      else if (file_exists("imagenes/logos_equipos/".$id_equipo.".JPG"))
         $extension=".JPG";
      else if (file_exists("imagenes/logos_equipos/".$id_equipo.".bmp"))
         $extension=".bmp";
      else if (file_exists("imagenes/logos_equipos/".$id_equipo.".BMP"))
         $extension=".BMP";
      else
         $extension="xxx";

      if ($extension!="xxx")
          $img="imagenes/logos_equipos/".$id_equipo.$extension;
      else
          $img="imagenes/logo_equipo.png";

      return $img;

   }
   function get_nombreLS($id_equipo){
      //obtener el usuario
      $query="SELECT equipoLS FROM equipos WHERE id_equipo='$id_equipo'";
      $stmt = $this->db->query($query);
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $equipo=$row['equipoLS'];

      return $equipo;
   }

      function es_favorito($id_usuario,$id_equipo){
      //obtener el usuario
      $query="SELECT * FROM equipos_favoritos WHERE id_usuario='$id_usuario' AND id_equipo='$id_equipo'";
//      print "q=$query<br>";
      $stmt = $this->db->query($query);
     $num=$stmt->rowCount();

      return $num;
   }

}
?>
