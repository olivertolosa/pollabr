<?php
class evento{   var $nombre;
   var $imagen;

   protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

   function get_nombre($id_evento){
      //obtener el usuario
      $query="SELECT evento FROM eventos WHERE id_evento='$id_evento'";
      $stmt = $this->db->query($query);
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $evento=$row['evento'];

      return $evento;
   }

   function get_valor($id_evento){
      //obtener el usuario
      $query="SELECT valor FROM eventos WHERE id_evento='$id_evento'";
      $stmt = $this->db->query($query);
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $valor=$row['valor'];

      return $valor;
   }

   public function get_imagen($id_evento){
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

      if ($extension){
         $img="imagenes/logos_eventos/$id_evento$extension";
      }else{
         $img="imagenes/logos_eventos/default.png";
      }
      return $img;

   }

   function tiene_plantilla($id_evento){
      $query="SELECT plantilla FROM eventos WHERE id_evento='$id_evento'";
      $stmt = $this->db->query($query);
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $plantilla=$row['plantilla'];

      return $plantilla;

   }


   function get_numrondas($id_evento){
      $query="SELECT num_rondas,plantilla FROM eventos WHERE id_evento='$id_evento'";
      $stmt = $this->db->query($query);
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $plantilla=$row['plantilla'];
      $num_rondas=$row['num_rondas'];

      if ($plantilla==0)
         return $num_rondas;
      else{
         $query2="SELECT num_rondas FROM eventos WHERE id_evento='$plantilla'";
         $stmt2 = $this->db->query($query2);
         $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
         $num_rondas=$row2['num_rondas'];
         return $num_rondas;
      }
   }

   function get_nombre_ronda($id_evento,$ronda){
      if (($id_evento2=$this->tiene_plantilla($id_evento))!=0){
          $id_evento=$id_evento2;
      }
      $query="SELECT nombre FROM rondasxevento WHERE id_evento='$id_evento' AND num_ronda='$ronda'";
      $stmt = $this->db->query($query);
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $nombre_ronda=$row['nombre'];
      return $nombre_ronda;
   }

   function listar_partidos($id_evento,$params=""){
      $plantilla=false;
      if (($id_evento2=$this->tiene_plantilla($id_evento))!=0){
          $id_evento_original=$id_evento;
          $id_evento=$id_evento2;
          $plantilla=true;
      }
      $query="SELECT id_partido FROM partidos WHERE id_evento='$id_evento'";
      if ($params!="")
         $query.=" AND $params";
      foreach($db->query('SELECT * FROM table') as $row){
         $resp[]=$row['id_partido'];
      }


      //si el evento tiene plantilla traducir los id's
      if ($plantilla){
         require_once 'includes/class_partido.php';
         $partidoobj=new partido($db);
         for ($i=0 ; $i< count($resp) ; $i++){
            $resp2[$i]=$partidoobj->get_id_partido_clon_from_original($resp[$i],$id_evento_original);
         }

         return $resp2;
      }else{
         return $resp;
      }
   }


}
?>
