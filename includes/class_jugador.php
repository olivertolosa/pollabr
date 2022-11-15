<?php
class jugador{   protected $db;
   public $nombre;
   public $id_jugador;
   public $activo;
   public $id_equipo;
   public $id_pais;
   public $estatura;
   public $posicion;
   public $fecha_nacimiento;
   public $alias1;
   public $alias2;
   public $selnal;

    public function __construct($id_jugador)
    {
        global $db;
		$this->db = $db;
		$this->id_jugador=$id_jugador;
		$query="SELECT * FROM jugadores WHERE id_jugador='$id_jugador'";
		//print "q=$query<br>";
        $stmt = $db->query($query);
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->nombre=$row['nombre'];
		$this->id_pais=$row['id_pais'];
		$this->id_equipo=$row['id_equipo'];
		$this->estatura=$row['estatura'];
		$this->posicion=$row['posicion'];
		$this->fecha_nacimiento=$row['fecha_nacimiento'];
		$this->alias1=$row['alias1'];
		$this->alias2=$row['alias2'];
		$this->selnal=$row['seleccion_nal'];				
		$this->activo=$row['activo'];
		
		
    }

   function get_nombre($id_jugador){      //obtener el usuario
      $query="SELECT nombre FROM jugadores WHERE id_jugador='$id_jugador'";
      $stmt = $this->db->query($query);
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $nombre=$row['nombre'];

      return $nombre;
   }

   public function get_imagen(){
	   $id_jugador=$this->id_jugador;      if (file_exists("imagenes/img_jugadores/".$id_jugador.".png"))
         $extension=".png";
      else if (file_exists("imagenes/img_jugadores/".$id_jugador.".PNG"))
         $extension=".PNG";
      else if (file_exists("imagenes/img_jugadores/".$id_jugador.".jpg"))
         $extension=".jpg";
      else if (file_exists("imagenes/img_jugadores/".$id_jugador.".JPG"))
         $extension=".JPG";
      else if (file_exists("imagenes/img_jugadores/".$id_jugador.".jpeg"))
         $extension=".jpeg";
      else if (file_exists("imagenes/img_jugadores/".$id_jugador.".JPEG"))
         $extension=".JPEG";
      else if (file_exists("imagenes/img_jugadores/".$id_jugador.".bmp"))
         $extension=".bmp";
      else if (file_exists("imagenes/img_jugadores/".$id_jugador.".BMP"))
         $extension=".BMP";

      if ($extension){
         $img="imagenes/img_jugadores/$id_jugador$extension";
      }else{
         $img="imagenes/img_jugadores/x.png";
      }
      return $img;

   }

}
?>
