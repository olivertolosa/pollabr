<?php
class usuario{   var $usuario;
   var $nombre;
   var $imagen;

   protected $db;

    public function __construct($id_usuario)
    {
        global $db;
		$this->db = $db;
		$this->id_usuario=$id_usuario;
		$query="SELECT usuario,nombre FROM usuarios WHERE id_usuario='$id_usuario'";
		$stmt = $this->db->query($query);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->usuario=$row['usuario'];
		$this->nombre=$row['nombre'];
    }

	function set_clave($clave){
      $query="UPDATE usuarios SET password='' WHERE id_usuario='$this->id_usuario'";
      $stmt = $this->db->query($query);
   }
	
   function get_usuario(){      //obtener el usuario
      $query="SELECT usuario FROM usuarios WHERE id_usuario='$this->id_usuario'";
      $stmt = $this->db->query($query);
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $user=$row['usuario'];

      return $user;
   }

   function get_nombre(){
      $query="SELECT nombre FROM usuarios WHERE id_usuario='$this->id_usuario'";
      $stmt = $this->db->query($query);
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $nombre=$row['nombre'];

      return $nombre;
   }

   function get_nombre_corto(){
      $query="SELECT nombre FROM usuarios WHERE id_usuario='$this->id_usuario'";
      $stmt =$this->db->query($query);
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $nombre=$row['nombre'];

      $nombre=substr($nombre,0,strpos($nombre," "));
	   $nombre=ucfirst(strtolower($nombre));

      return $nombre;
   }

   function get_email(){
      $query="SELECT email FROM usuarios WHERE id_usuario='$this->id_usuario'";
      $stmt = $this->db->query($query);
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $email=$row['email'];

      return $email;
   }

   function get_saldo(){
      $query="SELECT saldo FROM usuarios WHERE id_usuario='$this->id_usuario'";
      $stmt = $this->db->query($query);
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $saldo=$row['saldo'];

      return $saldo;
   }
   function incrementa_saldo($monto){
      $query="UPDATE usuarios SET saldo=saldo+$monto WHERE id_usuario='$this->id_usuario'";
      $stmt = $this->db->query($query);
   }

   public function get_imagen(){
      if (file_exists("imagenes/logos_usuarios/".$this->id_usuario.".png"))
         $extension=".png";
      else if (file_exists("imagenes/logos_usuarios/".$this->id_usuario.".PNG"))
         $extension=".PNG";
      else if (file_exists("imagenes/logos_usuarios/".$this->id_usuario.".jpg"))
         $extension=".jpg";
      else if (file_exists("imagenes/logos_usuarios/".$this->id_usuario.".JPG"))
         $extension=".JPG";
      else if (file_exists("imagenes/logos_usuarios/".$this->id_usuario.".jpeg"))
         $extension=".jpeg";
      else if (file_exists("imagenes/logos_usuarios/".$this->id_usuario.".JPEG"))
         $extension=".JPEG";
      else if (file_exists("imagenes/logos_usuarios/".$this->id_usuario.".bmp"))
         $extension=".bmp";
      else if (file_exists("imagenes/logos_usuarios/".$this->id_usuario.".BMP"))
         $extension=".BMP";

      if ($extension){
         $img="imagenes/logos_usuarios/$this->id_usuario$extension";
      }else{
         $img="imagenes/person_placeholder.png";
      }
      return $img;

   }

   public function tiene_acciones(){
        $query="SELECT DIFERENT id_bolsa FROM usuario_acciones WHERE id_usuario='$this->id_usuario'";
        $resp="";
        $i=0;

        foreach($db->query($query) as $row){        	 $resp[$i]=$row['id_bolsa'];
        	 $i++;
        }
        $resp=serialize($resp);
        return resp;
   }

}
?>
