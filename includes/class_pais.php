<?php
class pais{   protected $db;
   public $pais;
   public $id_pais;
   public $activo;

    public function __construct($id_pais)
    {
        global $db;
		$this->db = $db;
		$this->id_pais=$id_pais;
		$query="SELECT pais,activo FROM paises WHERE id_pais='$id_pais'";
		$stmt = $this->db->query($query);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->pais=$row['pais'];
		$this->activo=$row['activo'];				
    }

   function get_pais($id_pais){      //obtener el usuario
      $query="SELECT pais FROM paises WHERE id_pais='$id_pais'";
      $stmt = $this->db->query($query);
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $pais=$row['pais'];

      return $user;
   }

   public function get_imagen(){
	   $id_pais=$this->id_pais;      if (file_exists("imagenes/banderas/".$id_pais.".png"))
         $extension=".png";
      else if (file_exists("imagenes/banderas/".$id_pais.".PNG"))
         $extension=".PNG";
      else if (file_exists("imagenes/banderas/".$id_pais.".jpg"))
         $extension=".jpg";
      else if (file_exists("imagenes/banderas/".$id_pais.".JPG"))
         $extension=".JPG";
      else if (file_exists("imagenes/banderas/".$id_pais.".jpeg"))
         $extension=".jpeg";
      else if (file_exists("imagenes/banderas/".$id_pais.".JPEG"))
         $extension=".JPEG";
      else if (file_exists("imagenes/banderas/".$id_pais.".bmp"))
         $extension=".bmp";
      else if (file_exists("imagenes/banderas/".$id_pais.".BMP"))
         $extension=".BMP";

      if ($extension){
         $img="imagenes/banderas/$id_pais$extension";
      }else{
         $img="imagenes/banderas/x.png";
      }
      return $img;

   }

}
?>
