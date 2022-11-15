<?php
class partido{   var $id_eq1;
   var $id_eq2;
   var $goles1;
   var $goles2;
   var $fecha;
   var $hora;

   protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }
	
   function get_id_evento($id_partido){
      $query="SELECT id_evento FROM partidos WHERE id_partido='$id_partido'";
      $stmt = $this->db->query($query);
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $id_evento=$row['id_evento'];

      return $id_evento;
   }	

   function get_info_eq1_partido($id_partido){
      $query="SELECT * FROM partidos WHERE id_partido='$id_partido'";
      $stmt = $this->db->query($query);
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $equipo=$row['id_equipo1'];

      return $equipo;
   }

   function get_info_eq2_partido($id_partido){
      $query="SELECT * FROM partidos WHERE id_partido='$id_partido'";
      $stmt = $this->db->query($query);
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $equipo=$row['id_equipo2'];

      return $equipo;
   }


   function get_id_partido_original_from_clon($id_partido){
      $query="SELECT id_partido_original FROM partidos_clon WHERE id_partido='$id_partido'";
      $stmt = $this->db->query($query);
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $id_partido=$row['id_partido_original'];

      //print "respuesta: $id_partido";

      return $id_partido;
   }

   function get_id_partido_clon_from_original($id_partido,$id_evento){
      $query="SELECT id_partido FROM partidos_clon WHERE id_partido_original='$id_partido' AND id_evento='$id_evento'";
      //print "query_partido=$query_partido\n";
      $stmt = $this->db->query($query);
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $id_partido=$row['id_partido'];

      //print "respuesta: $id_partido";
      return $id_partido;
   }

   function editable($id_partido){
      $plantilla=false;
      //validar si partido es clon o no
      $query="SELECT id_evento FROM partidos WHERE id_partido='$id_partido'";
      $stmt = $this->db->query($query);

  	   if ($stmt->rowCount()==0){
         $query="SELECT id_evento FROM partidos_clon WHERE id_partido='$id_partido'";
         $stmt = $this->db->query($query);
         $plantilla=true;
      }

     $row = $stmt->fetch(PDO::FETCH_ASSOC);
     $id_evento=$row['id_evento'];

      if ($plantilla){
         $id_partido=$this->get_id_partido_original_from_clon($id_partido);
      }

      $query_disponible="SELECT editable FROM partidos WHERE id_partido='$id_partido'";
  	  $stmt_disponible = $this->db->query($query_disponible);
  	  $row = $stmt_disponible->fetch(PDO::FETCH_ASSOC);
  	   $editable=$row['editable'];

      return ($editable);
   }

   function en_progreso($id_partido){   	  $query="SELECT id_partido FROM partidos_iniciados WHERE id_partido='$id_partido'";
      $stmt= $this->db->query($query);
      ($stmt->rowCount()==0)? $enprogreso=false : $enprogreso=true;

      return $enprogreso;
   }

}
?>
