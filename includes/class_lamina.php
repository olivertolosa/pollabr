<?php
class lamina{   var $nombre;
   var $imagen;

   protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

   function get_nombre($id_lamina){
      $query="SELECT nombre FROM album_laminas WHERE id_lamina='$id_lamina'";
      $stmt = $this->db->query($query);
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $nombre=$row['nombre'];

      return $nombre;
   }

   function get_equipo($id_lamina){
      $query="SELECT id_equipo FROM album_laminas WHERE id_lamina='$id_lamina'";
      $stmt = $this->db->query($query);
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $id_equipo=$row['id_equipo'];

      return $id_equipo;
   }

   function lamina_latengo ($id_lamina,$id_usuario){   	   $query="SELECT * FROM album_laminas_usuario WHERE id_lamina='$id_lamina' AND id_usuario='$id_usuario'";
//print "q=$query<br>";
   	   $stmt = $this->db->query($query);
   	   $num=$stmt->rowCount();

   	   if ($num==0)
   	      return false;
   	   else
   	      return true;
   }

   function get_imagen($id_lamina){      if (file_exists("imagenes/laminas/".$id_lamina.".png"))
         $extension=".png";
      else if (file_exists("imagenes/laminas/".$id_lamina.".PNG"))
         $extension=".PNG";
      else if (file_exists("imagenes/laminas/".$id_lamina.".jpg"))
         $extension=".jpg";
      else if (file_exists("imagenes/laminas/".$id_lamina.".jpeg"))
         $extension=".jpeg";
      else if (file_exists("imagenes/laminas/".$id_lamina.".gif"))
         $extension=".gif";
      else if (file_exists("imagenes/laminas/".$id_lamina.".JPG"))
         $extension=".JPG";
      else if (file_exists("imagenes/laminas/".$id_lamina.".JPEG"))
         $extension=".JPEG";
      else if (file_exists("imagenes/laminas/".$id_lamina.".bmp"))
         $extension=".bmp";
      else if (file_exists("imagenes/laminas/".$id_lamina.".BMP"))
         $extension=".BMP";
      else
       $extension=".NPI";  //para descartar
//    print "extension=$extension2<br>";
    $img="imagenes/laminas/".$id_lamina.$extension;

    if (!file_exists($img))  $img="imagenes/person_placeholder.png";

      return $img;

   }

}
?>
