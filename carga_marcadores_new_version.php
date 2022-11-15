<?

require_once 'includes/Open-Connection.php';
include 'includes/class_bolsa.php';
include 'includes/class_equipo.php';
include 'audit.php';
include 'function_actualiza_estadisticas_equipoxevento.php';

$bolsa=new bolsa($db);
$equipo=new equipo($db);

$partido_encontrado=true;

function quitar_tildes($cadena) {
$no_permitidas= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹");
$permitidas= array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E");
$texto = str_replace($no_permitidas, $permitidas ,$cadena);
return $texto;
}


//cargar la lista de partidos de la fuente
$url='http://www.livescores.com';
//$url='fuente.txt';
$lines = file($url);

    if ($lines==false){
    	 print "REPAILA en carga_fuente";
    	 exit();
    }else{
       print "<br>abri $link<br>";
    }

//para cada partido buscar estado y marcador

//cargar partidos del dia marcados como iniciados
$hoy=date("Y-m-d");
$query="SELECT id_partido,id_evento,id_equipo1,id_equipo2,tipo_e FROM partidos WHERE id_partido IN(SELECT id_partido FROM partidos_iniciados)";
print "q=$query<br>";
$stmt = $db->query($query);

print "Procesando ".$stmt->rowCount()." partidos<br><br>";


while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
   $id_partido=$row['id_partido'];
   $id_equipo1=$row['id_equipo1'];
   $id_equipo2=$row['id_equipo2'];
   $id_evento=$row['id_evento'];
   $tipo_e=$row['tipo_e'];

//print_r($row);

   //obtener los nombres de los equipos
   $nombre_equipo1=$equipo->get_nombre($id_equipo1);
   $nombre_equipo2=$equipo->get_nombre($id_equipo2);

//   $nombre_equipo1="CFR Cluj";
//   $nombre_equipo2="Concordia Chiajna";


   print "en el partido $id_partido juegan $nombre_equipo1 vs $nombre_equipo2<br>";

//   buscar el partido
   $i=0;
   $seguir_buscando=true;
   while ($lines[$i] and $seguir_buscando){
//   	print "i=$i<br>buscando: <div class=\"ply tright name\"> $nombre_equipo1<br>";
  	$lines[$i]=utf8_encode($lines[$i]);


        if ($pos1=strpos($lines[$i],"<div class=\"ply tright name\"> $nombre_equipo1") or $pos2=strpos($lines[$i],"<div class=\"ply name\"> $nombre_equipo1")){
           print "econtré en linea $i<br>\n\n";
           $partido_encontrado=true;


        //cortar cerca de donde empieza el tr   }
           $pos1=strpos($lines[$i],"<div class=\"ply tright name\"> $nombre_equipo1");

print "buscando : <div class=\"ply name\"> $nombre_equipo2<br>\n\n";
           $pos2=strpos($lines[$i],"<div class=\"ply name\"> $nombre_equipo2");
print "pos1=$pos1*****pos2=$pos2<br>";
           ($pos1>$pos2)? $pos=$pos1 : $pos=$pos2;

           $pos_corte=$pos1;
           while (substr($lines[$i],$pos_corte,13)!="</div> </div>"){
           	   $pos_corte--;
           }
           $len=$pos-$pos_corte;
           print "pos=$pos1--pos_cort=$pos_corte....len=$len<br>";
           //print "dato en caracter $pos<br>";
           $linea=substr($lines[$i],$pos,$len);
           print "\n\nlinea=$linea\n\n<br><br>*********************************";


        //cortar buscando el tr

           $pos_corte=0;
           while (substr($lines[$i],$pos_corte,17)!="div class=\"min\"" and $pos_corte<700){
           	   $pos_corte++;
           	   //print "\n<br>char en $pos_corte: ".substr($linea[$pos_corte],0,1)."\n";
           }

//print "<br><br>pos corte2=$pos_corte<br>";
           $linea=substr($linea,0,$pos_corte);
           print "\n\nlinea=<code>$linea</code><br>\n\n";

           //validar si en la linea esta el segundo equipo
           if ($pos1!==false and $pos2!== false){

           //buscar estado y marcadores los marcadores
              $estado=substr($linea,strpos($linea,"<div class=\"min\"")+16,80);
print "\n\nestado=$estado<br>\n";

	      if (strpos($estado,"FT") or strpos($estado,"AET")){
	         $estado="FT";
	      }else if (strpos($estado,"<img")){
                 //calcular el tiempo
                 print "dtectda img<br>\nestado1:$estado\n";
					  $estado=substr($estado,strpos($estado,">")+2);
					  $estado=substr($estado,strpos($estado,">")+2);
				print "\nestado2:$estado\n";
					  $estado=substr($estado,0,strpos($estado,"&#x"));
         }else if (substr($estado,1,2)=="FT" or $estado>90){
                 $estado="FT";
              }


              $equipo1=substr($linea,strpos($linea,"class=\"ply tright name\">")+24,30);
              $equipo1=substr($equipo1,0,strpos($equipo1,"<")-1);
              $equipo1=ltrim(rtrim($equipo1));
              $equipo2=substr($linea,strpos($linea,"class=\"ply name\"")+18,30);
              $equipo2=substr($equipo2,0,strpos($equipo2,"<")-1);
              $equipo2=ltrim(rtrim($equipo2));
              $marcador=substr($linea,strpos($linea,"class=\"sco\">")+10,150);
              $marcador=substr($marcador,strpos($marcador," - ")-1);
              $marcador1=$marcador[0];
              $marcador2=$marcador[4];

              print "\nestado=*$estado*<br> \n<br> eq1=$equipo1<br>\neq2=$equipo2<br>\nMarcador=$marcador1-$marcador2<br><br>";
              $seguir_buscando=false;

//             validar si los equipos corresponden a los programados
              if (($equipo1==$nombre_equipo1 and $equipo2==$nombre_equipo2) or($equipo1==$nombre_equipo2 and $equipo2==$nombre_equipo1)){
              	   if ($equipo1==$nombre_equipo1){
              	   	   $m1=$marcador1;
             	   	   $m2=$marcador2;
             	   }else{
           	     	   $m1=$marcador2;
           	     	   $m2=$marcador1;
             	   }
            	   //update de los datos

            	   //validar si hubo cambio de marcador
            	   $query_marcador="SELECT goles1,goles2 FROM partidos WHERE id_partido='$id_partido'";
print "\n\n q=$query_marcador<br><br>\n\n";

            	   $stmt_marcador = $db->query($query_marcador);
            	   $row_marcador = $stmt_marcador->fetch(PDO::FETCH_ASSOC);
            	   $marc1=$row_marcador['goles1'];
            	   $marc2=$row_marcador['goles2'];

            	   if (($marc1!= $m1 or $marc2!=$m2) and ($marc1!='?' and $marc2!='?')){
                	  $query_update="UPDATE partidos SET goles1='$m1', goles2='$m2' WHERE id_partido='$id_partido'";
             	         $db->query($query_update);
            	         print "query update=$query_update<br>id_evento=$id_evento<br>";

               //si el partido es de evento actualizar posiciones
               if ($tipo_e=='e'){


print "<br><br>*******************************************************************<br>Generando posiciones<br>
                ****************************************************************<br>";

                   //cargar el listado de eventos original y clones donde está el partido

					    $eventos=array();

					    $query_part="SELECT id_evento FROM partidos WHERE id_partido='$id_partido'";
					    $stmt_part = $db->query($query_part);
                        $row_part = $stmt_part->fetch(PDO::FETCH_ASSOC);
					    $id_evento=$row_part['id_evento'];

					    $eventos[0]=$id_evento;
                        $i=1;

                       $query_clones="SELECT id_evento FROM eventos WHERE plantilla='$id_evento'";
                       //print "q=$query_clones<br>";
                       foreach($db->query($query_clones) as $row_clones) {
	                       $eventos[$i]=$row_clones['id_evento'];
	                       $i++;
                      }

                      print_r($eventos);

                      print"<br>eventos:".sizeof($eventos);



                      for ($num_eventos=0 ; $num_eventos<sizeof($eventos) ; $num_eventos++){
                         print "<br><b>Actualziando posiciones en evento $num_eventos =$eventos[$num_eventos] por partido $id_partido</b><br>";
    	                 $id_evento=$eventos[$num_eventos];
    					 include 'genera_posiciones.php';

                         //actualzar estadisticas del equipo en el evento
                         actualizar_estadisticas_equipoxevento($id_equipo1,$id_evento);
                         actualizar_estadisticas_equipoxevento($id_equipo2,$id_evento);
					   }
				}
               }else{
                     print "No ha cambiado el marcador...no hay que actualizar puntajes<br>";
               }
             	   //si el partido finalizó removerlo de la tabla de partidos inciados y actualizar la tabla

          	   if ($estado=="FT"){
             	   	   $query_f="DELETE FROM partidos_iniciados WHERE id_partido=$id_partido";
             	   	   $result_f = $db->query($query_f);
                		   print "marcando partido finalizado";
                		   audit(0,"marcando partido finalizado","partido:$id_partido, ".$equipo->get_nombre($id_equipo1)." vs ".$equipo->get_nombre($id_equipo2));

                  if ($tipo_e=='b'){ //partido de bolsa
                    audit(0,"  ","  ");
                    audit(0,"Si es partido de bolsa","partido: $id_partido...poniendo marca valorización y depreciando");
                    //obtener el id de la bolsa
				     if ($m1>$m2){
                        //si la acción vale 0 no se pone marca
                        if ($bolsa->get_valor_accion($id_evento,$id_equipo1)>0){
                        	audit(0,"Marca automática para valorización","bolsa=$id_evento,equipo=$id_equipo1: ".$equipo->get_nombre($id_equipo1));
				    	    $bolsa->marca_valorizacion($id_evento,$id_equipo1);
				    	}else{
                           audit(0,"Equipo eliminado....no se marca para valorización","bolsa=$id_evento,equipo=$id_equipo2: ".$equipo->get_nombre($id_equipo2));
                        }
                        audit(0,"Depreciación automática","bolsa=$id_evento,equipo=$id_equipo2: ".$equipo->get_nombre($id_equipo2));
                        $bolsa->depreciar($id_evento,$id_equipo2,"p");
				     }else if ($m2>$m1){
				     	if ($bolsa->get_valor_accion($id_evento,$id_equipo2)>0){
                           audit($id_usuario,"Marca automática para valorización","bolsa=$id_evento,equipo=$id_equipo2: ".$equipo->get_nombre($id_equipo2));
				    	   $bolsa->marca_valorizacion($id_evento,$id_equipo2);
				    	}else{
                           audit(0,"Equipo eliminado....no se marca para valorización","bolsa=$id_evento,equipo=$id_equipo2: ".$equipo->get_nombre($id_equipo2));
                        }
                        audit(0,"Depreciación automática","bolsa=$id_bolsa,equipo=$id_equipo1: ".$equipo->get_nombre($id_equipo1));
                        $bolsa->depreciar($id_evento,$id_equipo1,"p");
				     }else if ($m2==$m1){
                        audit($id_usuario,"Depreciación automática","bolsa=$id_evento,equipo=$id_equipo21 ".$equipo->get_nombre($id_equipo1));
                        $bolsa->depreciar($id_evento,$id_equipo1,"e");
                        audit(0,"Depreciación automática","bolsa=$id_bolsa,equipo=$id_equipo2: ".$equipo->get_nombre($id_equipo2));
                        $bolsa->depreciar($id_evento,$id_equipo2,"e");
				     }else{
				     	audit(0,"Error en valorizaciòn automàtica","bolsa=$id_evento,equipo=$id_equipo1: ".$equipo->get_nombre($id_equipo1)."equipo=$id_equipo2: ".$equipo->get_nombre($id_equipo2));
				     }

				     //validar si el partido tiene marca para ejecutar valorización
				     $query_val="SELECT marcaval FROM partidos WHERE id_partido='$id_partido'";
				     $stmt_val = $db->query($query_val);
                     $row_val = $stmt_val->fetch(PDO::FETCH_ASSOC);
				     $marcaval=$row_val['marcaval'];

				     if ($marcaval){
				        print "<br>Se debe ejecutar valorización automática<br>";
				        $id_bolsa=$id_evento;
				        $_REQUEST['confirmacion_valorizacion']=1;
				        include 'bolsa_valorizacion.php';
				     }else{
				     	print "<br>NO se debe ejecutar valorización automática<br>";
				     }
				  }else{
				     audit(0,"No es partido de bolsa","partido: $id_partido");
				  }
             	}
             }
           }else{
              $partido_encontrado=false;

           }
         }else {
      	  $partido_encontrado=false;
         }

    $i++;
   }

}

if(!$partido_encontrado){
      print "PAILA...no se encontró el primer equipo\n";
      	   //mandar correo
           require_once 'function_correo.php';
           $nombre="ElGolGanador - Alerta Cargando Marcadores";
           $from="notificaciones@elgolganador.com";
           $subject="Alerta Cargando Marcadores";

   	       $mensaje="Alerta!!!!<br><br>
                     no se pudo cargar el marcador del partido $nombre_equipo1 Vs $nombre_equipo2
                     <br><br>Por favor revise que los nombres o la estructura no hayan cambiado";
              //print "menasje:<br>$mensaje<br>";
         $correo=envio_correo('otolosa@gmail.com',$nombre,$from,$subject,$mensaje);
}

include 'includes/Close-Connection.php';
?>
