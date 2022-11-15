<?php
class bolsa{   var $nombre;
   var $imagen;

   protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }



   function get_nombre($id_bolsa){

      //obtener el usuario
      $query="SELECT nombre_bolsa FROM bolsas WHERE id_bolsa='$id_bolsa'";
      $stmt = $this->db->query($query);
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $nombre_bolsa=$row['nombre_bolsa'];

      return $nombre_bolsa;
   }

   function get_valor_accion($id_bolsa,$id_equipo){
      //obtener el usuario
      $query="SELECT valor FROM bolsa_acciones WHERE id_bolsa='$id_bolsa' AND id_equipo='$id_equipo' ORDER BY fecha DESC LIMIT 0,1";
      $stmt = $this->db->query($query);
      $row = $stmt->fetch(PDO::FETCH_ASSOC);;
      $valor=$row['valor'];

      return $valor;
   }

   function get_valor_accion_fecha($id_bolsa,$id_equipo,$fecha){
      //obtener el usuario
      $query="SELECT valor FROM bolsa_acciones
             WHERE id_bolsa='$id_bolsa'
             AND id_equipo='$id_equipo'
             AND fecha<='$fecha'
             ORDER BY fecha DESC LIMIT 0,1";
//print "<br>q=$query<br>";
      $stmt = $this->db->query($query);
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $valor=$row['valor'];

      return $valor;
   }

   public function get_imagen($id_bolsa){
      if (file_exists("imagenes/logos_bolsas/".$id_bolsa.".png"))
         $extension=".png";
      else if (file_exists("imagenes/logos_bolsas/".$id_bolsa.".PNG"))
         $extension=".PNG";
      else if (file_exists("imagenes/logos_bolsas/".$id_bolsa.".jpg"))
         $extension=".jpg";
      else if (file_exists("imagenes/logos_bolsas/".$id_bolsa.".JPG"))
         $extension=".JPG";
      else if (file_exists("imagenes/logos_bolsas/".$id_bolsa.".jpeg"))
         $extension=".jpeg";
      else if (file_exists("imagenes/logos_bolsas/".$id_bolsa.".JPEG"))
         $extension=".JPEG";
      else if (file_exists("imagenes/logos_bolsas/".$id_bolsa.".bmp"))
         $extension=".bmp";
      else if (file_exists("imagenes/logos_bolsas/".$id_bolsa.".BMP"))
         $extension=".BMP";

      if ($extension){
         $img="imagenes/logos_bolsas/$id_bolsa$extension";
      }else{
         $img="imagenes/logos_bolsas/default.png";
      }
      return $img;

   }

   function saldo($id_bolsa,$id_usuario){

      $query="SELECT saldo FROM bolsa_saldos WHERE id_bolsa='$id_bolsa' AND id_usuario='$id_usuario'";
//      print "q=$query<br>";
      $stmt = $this->db->query($query);

      if ($stmt->rowCount()==0){	     $saldo=-1;  //no existe
      }else{         $row = $stmt->fetch(PDO::FETCH_ASSOC);
         $saldo=$row['saldo'];
      }

      return $saldo;

   }

   function marca_valorizacion($id_bolsa,$id_equipo){
        //validar si ya tiene marca
        $query="SELECT * FROM bolsa_marcaval WHERE id_bolsa='$id_bolsa' AND id_equipo='$id_equipo'";
        $stmt = $this->db->query($query);
        if ( $stmt->rowCount()==0){
           $query="INSERT INTO bolsa_marcaval VALUES ('$id_bolsa','$id_equipo')";
           //print "<br>q=$query<br>";
           $stmt = $this->db->query($query);
        }
   }

   function depreciar($id_bolsa,$id_equipo,$resultado){         require_once 'includes/class_equipo.php';

         $equipo=new equipo($this->db);

   	   //p=perdio....e=empato....x=eliminado
//   	   print "resultado=$resultado<br>";

         $valor_actual=$this->get_valor_accion($id_bolsa,$id_equipo);
         $num_acciones=$this->get_total_acciones($id_bolsa,$id_equipo);

//         print "num_aciciones=$num_acciones<br>";

         $fecha=date('Y-m-d');
         $hora=date('H:i:m');

         if ($resultado=='e'){
            $nuevo_valor=floor($valor_actual*0.85);
            $valor_depreciado=$valor_actual*$num_acciones*0.15;
         }else if ($resultado=='p'){
            $nuevo_valor=floor($valor_actual*0.70);
            $valor_depreciado=$valor_actual*$num_acciones*0.30;
         }else if ($resultado=='x'){
         	$nuevo_valor=0;
            $valor_depreciado=$valor_actual*$num_acciones;
            //si el equipo fue eliminado borrar marca de valorizaciòn
            $query2="DELETE FROM bolsa_marcaval WHERE id_bolsa='$id_bolsa' AND id_equipo='$id_equipo'";
            $this->db->query($query2);
         }


          $query="INSERT INTO bolsa_acciones VALUES('$id_bolsa','$id_equipo','$nuevo_valor','$fecha $hora')";
//          print "q=$query<br>";
          $this->db->query($query);
          audit(0,"Depreciación","bolsa=$id_bolsa,equipo=$id_equipo: ".$equipo->get_nombre($id_equipo).",valor anterior:".$valor_actual.",valor nuevo:".$nuevo_valor.",acciones:".$num_acciones);

          //poner el valor perdido en la bolsa
          $query="UPDATE bolsas SET valorizacion=valorizacion+$valor_depreciado WHERE id_bolsa='$id_bolsa'";
          $this->db->query($query);
/*          if ($resultado=='e'){
             audit(0,"Equipo ELIMINADO!!!......","bolsa=$id_bolsa,equipo=$id_equipo: ".$equipo->get_nombre($id_equipo));
          }*/
          audit(0,"Paso de depreciación a bolsa","bolsa=$id_bolsa,equipo=$id_equipo: ".$equipo->get_nombre($id_equipo).", valor depreciado=$valor_depreciado");
   }



   function get_cantidad_acciones($id_bolsa,$id_equipo,$id_usuario){
      $query="SELECT cantidad FROM usuario_acciones WHERE id_bolsa='$id_bolsa' AND id_equipo='$id_equipo' AND id_usuario='$id_usuario'";
      $stmt = $this->db->query($query);
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      ($stmt->rowCount()==0)? $cantidad=0:  $cantidad=$row['cantidad'];

      return $cantidad;
   }

   function get_total_acciones($id_bolsa,$id_equipo){
      //obtener el usuario
      $query="SELECT SUM(cantidad) as cuantas FROM usuario_acciones WHERE id_bolsa='$id_bolsa' AND id_equipo='$id_equipo'";
//      print "query=$query<br>";
      $stmt = $this->db->query($query);
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      ($stmt->rowCount()==0)? $cantidad=0:  $cantidad=$row['cuantas'];

      return $cantidad;
   }

   function get_individuales($id_bolsa){
      //obtener el usuario
      $query="SELECT individuales FROM bolsas WHERE id_bolsa='$id_bolsa'";
      $stmt = $this->db->query($query);
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $individuales=$row['individuales'];

      return $individuales;
   }

   function get_valorizacion_pendiente($id_bolsa){
      //obtener el usuario
      $query="SELECT valorizacion FROM bolsas WHERE id_bolsa='$id_bolsa'";
      $stmt = $this->db->query($query);
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $valorizacion=$row['valorizacion'];

      return $valorizacion;
   }

   function adicionar_acciones($id_bolsa,$id_equipo,$id_usuario,$cantidad){//   	print "cantidad=$cantidad<br>";      //ver cuantas tenia
      $query="SELECT cantidad FROM usuario_acciones WHERE id_usuario='$id_usuario' AND id_bolsa='$id_bolsa' AND id_equipo='$id_equipo'";
      $stmt = $this->db->query($query);

      if ($stmt->rowCount()>0){  //ya tenia acciones ->UPDATE
         $row=$stmt->fetch(PDO::FETCH_ASSOC);
         $acciones_actuales=$row['cantidad'];
         $acciones_actuales+=$cantidad;
         $query="UPDATE usuario_acciones SET cantidad='$acciones_actuales' WHERE id_bolsa='$id_bolsa' AND id_usuario='$id_usuario' AND id_equipo='$id_equipo'";
      }else{   //no tiene acciones ->INSERT
       	 $query="INSERT INTO usuario_acciones VALUES('$id_usuario','$id_bolsa','$id_equipo','$cantidad')";
      }
      $this->db->query($query);
   }

   function remover_acciones($id_bolsa,$id_equipo,$id_usuario,$cantidad){
      //ver cuantas tenia
      $query="SELECT cantidad FROM usuario_acciones WHERE id_usuario='$id_usuario' AND id_bolsa='$id_bolsa' AND id_equipo='$id_equipo'";
      $stmt=$this->db->query($query);
      $row = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($stmt->rowCount()>0){  //ya tenia acciones ->UPDATE
         $row=$stmt->fetch(PDO::FETCH_ASSOC);
         $acciones_actuales=$row['cantidad'];
         $acciones_actuales-=$cantidad;
         $query="UPDATE usuario_acciones SET cantidad='$acciones_actuales' WHERE id_bolsa='$id_bolsa' AND id_usuario='$id_usuario' AND id_equipo='$id_equipo'";
      }else{   //no tiene acciones ->INSERT
       	 $query="INSERT INTO usuario_acciones VALUES('$id_usuario','$id_bolsa','$id_equipo','$cantidad')";
      }
      $this->db->query($query);
   }

   function valor_portafolio($id_bolsa, $id_usuario){

   $query="SELECT id_equipo
           FROM equipos
           WHERE id_equipo IN (SELECT id_equipo FROM usuario_acciones WHERE id_bolsa='$id_bolsa' AND id_usuario='$id_usuario' AND cantidad>0)
           ORDER BY equipo ASC";
//print "q=$query<br>";

      $valor_acciones=0;
      foreach($this->db->query($query) as $row){
          $id_equipo=$row['id_equipo'];
          $valor=$this->get_valor_accion($id_bolsa,$id_equipo);
          $cantidad=$this->get_cantidad_acciones($id_bolsa,$id_equipo,$id_usuario);
          $valor_total=$valor*$cantidad;
          $valor_acciones+=$valor_total;
    }

    $saldo=$this->saldo($id_bolsa,$id_usuario);
    $valor_portafolio=$saldo+$valor_acciones;

    return  $valor_portafolio;
   }

}
?>
