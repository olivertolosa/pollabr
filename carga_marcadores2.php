<?
session_start();

require_once 'includes/Open-Connection.php';
include 'audit.php';
include 'includes/class_equipo.php';
include 'includes/class_bolsa.php';
include 'includes/class_usuario.php';

include 'function_actualiza_estadisticas_equipoxevento.php';

$equipo=new equipo($db);
$bolsa=new bolsa($db);
$usuario=new usuario($db);

//cargar la lista de partidos de la fuente
$url='http://www.livescores.com/';

function get_data($url) {
	$ch = curl_init();
	$timeout = 15;
	$userAgent = 'Mozilla/5.0 (Windows NT 5.1; rv:31.0) Gecko/20100101 Firefox/31.0';
	curl_setopt( $ch, CURLOPT_USERAGENT, $userAgent );
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}


$linea = get_data($url);

    if ($linea==false){
    	 print "REPAILA en carga_fuente";
    	 exit();
    }else{
       print "<br>abri $url<br>";
       print "**$lines[2]**";
    }


print "<table border=\"1\"><tr><td>#<th>Estado<th>Liga<th>Eq1<th>Marcador<th>Eq2";
   unset($matches);
//   $linea=fgets($lines);
   $linea=utf8_encode($linea);
   $encontro=preg_match_all('/<div class="row-gray[\seven]+" data-pid="\d*" data-eid="\d*" data-type="\w*" data-esd="\d*"> <div class="min">[\w\d\/\.=\"\s<>:]* [\S\d]* <\/div> <div class="ply tright name"> [\d\.O&#x27;\w\s-\*]* <\/div> <div class="sco"> .*? [\s\d\?-]+.*?<div class="ply name"> [\d\.O&#x27;\w\s-\*]*?<\/div> <\/div>/',$linea,$matches);
   if ($encontro){
   	   $encontradas=count($matches[0]);
   	   $num_partidos_cargados=1;
   	   foreach ($matches[0] as $cadena){
   	   	   preg_match('/<div class="row-gray[\seven]+" data-pid="\d*" data-eid="\d*" data-type="\w*" data-esd="\d*"> <div class="min">([\w\d\/\.=\"\s<>:]* [\S\d]*) <\/div> <div class="ply tright name"> ([\d\.O&#x27;\w\s-\*]*) <\/div> <div class="sco"> (?:.*>)?([\d\?] - [\d\?]).*?<div class="ply name"> ([\d\.O&#x27;\w\s-\*]*)? <\/div> <\/div>/',$cadena,$matches2);
   	   	   $estado=$matches2[1];
   	   	   $eq1=$matches2[2];
   	   	   $marcador=$matches2[3];
   	   	   $eq2=$matches2[4];

   	   	   //cargar la competencia
   	   	   unset($matches3);
   	   	   $competencia='';
   	   	   preg_match('/<div class="left"> <a href="[\w\d\/.-]*"><strong>([\s\w]*)<\/strong><\/a> - <a href="[\w\d\/.-]*">([\(\)\s\w:äëïöü.-]*)<\/a> <\/div>.*?'.$eq1.'/',$linea,$matches3);


//   	   	   print "enc_comp=$enc_comp<br>";
//   	   	   print "match:*".$matches3[0]."*<br>";
//  	   	   print_r($matches3);
           //en lo encontrado ubicar la última liga

           preg_match_all('/<div class="left"> <a href="[\w\d\/.-]*"><strong>([\s\w]*)<\/strong><\/a> - <a href="([\w\d\/.-]*)">([\(\)\s\w:äëïöü.-]*)<\/a> <\/div>/',$matches3[0],$ligas);

           $ultima_liga=sizeof($ligas[0])-1;
           $competencia = $ligas[1][$ultima_liga]." - ".$ligas[3][$ultima_liga];
           $url="http://www.livescores.com".$ligas[2][$ultima_liga];


           $query="SELECT id_grupo_equipos,grupo_equipos FROM grupos_equipos WHERE link_LS='$url'";
           $stmt = $db->query($query);
   	   	   if ($stmt->rowCount()>0){
   	   	      $row=$stmt->fetch(PDO::FETCH_ASSOC);
   	   	      $competencia=$row['grupo_equipos'];
   	   	      $id_competencia=$row['id_grupo_equipos'];
   	   	   }else{
   	   	   	  $id_competencia=0;
   	   	   }



   	   	   $query="SELECT id_equipo FROM equipos WHERE equipoLS='$eq1'";
   	   	   //print "<br>q=$query<br>";

//if ($num_partidos_cargados==12) exit();


   	   	   $stmt=$db->query($query);
   	   	   if (!empty($stmt) && $stmt->rowCount()>0){
   	   	   	   $row=$stmt->fetch(PDO::FETCH_ASSOC);
               $eq1_ls=$row['id_equipo'];
   	   	   }else{
   	   	   	   $eq1_ls=0;
   	   	   }



   	   	   ($eq1_ls>0)? $fondo1="green" : $fondo1="red";

   	   	   $query="SELECT id_equipo FROM equipos WHERE equipoLS='$eq2'";
   	   	   $stmt = $db->query($query);
   	   	   if (!empty($stmt) && $stmt->rowCount()>0){
   	   	   	   $row=$stmt->fetch(PDO::FETCH_ASSOC);
               $eq2_ls=$row['id_equipo'];
   	   	   }else{
   	   	   	   $eq2_ls=0;
   	   	   }

   	   	   ($eq2_ls>0)? $fondo2="green" : $fondo2="red";

   	   	   ($id_competencia>0)? $fondol="green" : $fondol="red";

           $partidos[$num_partidos_cargados]['estado']=trim($estado);
           $partidos[$num_partidos_cargados]['eq1']=trim($eq1);
           $partidos[$num_partidos_cargados]['id_eq1']=$eq1_ls;
           $partidos[$num_partidos_cargados]['eq2']=trim($eq2);
           $partidos[$num_partidos_cargados]['id_eq2']=$eq2_ls;
           $partidos[$num_partidos_cargados]['marcador']=$marcador;
           $partidos[$num_partidos_cargados]['id_competencia']=$id_competencia;
           $partidos[$num_partidos_cargados]['competencia']=$competencia;

   	   	   print "<tr><td>$num_partidos_cargados<td>$estado<td style=\"background-color: $fondol;\">$competencia<td style=\"background-color: $fondo1;\">$eq1<td>$marcador<td style=\"background-color: $fondo2;\">$eq2\n";
   	   	   $num_partidos_cargados++;

   	   }

   }
print"</table>";


print "termina carga de datos<br><br>";



print "*****************************<br>";
/*************************************************************************************
               Fin de carga de datos
**************************************************************************************/

$i=1;
$partidos_registrados=0;
while ($i<=$num_partidos_cargados){
	$eq1=$partidos[$i]['eq1'];
	$eq2=$partidos[$i]['eq2'];
	$estado=$partidos[$i]['estado'];
	$id_eq1=$partidos[$i]['id_eq1'];
	$id_eq2=$partidos[$i]['id_eq2'];
	$id_eq2=$partidos[$i]['id_eq2'];
	$id_competencia=$partidos[$i]['id_competencia'];
	$competencia=$partidos[$i]['competencia'];
	$marcador=trim($partidos[$i]['marcador']);
	$goles1=substr($marcador,0,strpos('-',$marcador)+1);
	$goles2=substr($marcador,strpos('-',$marcador)+4);
	$hoy=date('Y-m-d');
	$ayer=date('Y-m-d', strtotime($hoy .' -1 day'));

	if ($estado=="FT" and $id_eq1!=0 and $id_eq2!=0){
	   print "<br><br>$i cargando partido $eq1 Vs $eq2<br>";
	   //validar si el partido ya existe
	   $query="SELECT id_partido FROM partidos2 WHERE id_equipo1=$id_eq1 AND id_equipo2=$id_eq2 AND (fecha='$hoy' OR fecha='$ayer')";
	   $stmt = $db->query($query);
	   if ($stmt->rowCount()==0){
	      print "se debe registrar el partido $competencia --> $eq1:$goles1 vs $eq2:$goles2<br>";
	      $query="INSERT INTO partidos2 VALUES('','$id_eq1','$id_eq2','$id_competencia','$goles1','$goles2','0','0','$hoy','$competencia')";
	      $stmt = $db->query($query);
          $partidos_registrados++;
	   }else{
	   	  print "partido $competencia --> $eq1 vs $eq2 ya está registrado<br>";
	   }
	}

   $i++;
}

print "<br><br>Total partidos registrados: $partidos_registrados<br><br>";

print "<br><br>*************fin de carga de partidos históricos*********************<br>";

/*************************************************************************************
              Inicio de Registro de partidos finalizados
**************************************************************************************/



/*************************************************************************************
               Inicio de Registro de partidos finalizados
**************************************************************************************/

//partidos de bolsa y polla
//cargar partidos del dia marcados como iniciados
$hoy=date("Y-m-d");
$query="SELECT id_partido,id_evento,id_equipo1,id_equipo2,tipo_e FROM partidos WHERE id_partido IN(SELECT id_partido FROM partidos_iniciados)";
print "q=$query<br>";
$stmt = $db->query($query);

$num_partidos=$stmt->rowCount();

print "Procesando $num_partidos partidos<br><br>";


while($row=$stmt->fetch(PDO::FETCH_ASSOC)){

	   $cambio_marcador=false;
	   $partido_finalizado=false;

       //obtener los nombres de los equipos
       $id_equipo1=$row['id_equipo1'];
       $id_equipo2=$row['id_equipo2'];
       $nom_eq1=$equipo->get_nombreLS($id_equipo1);
       $nom_eq2=$equipo->get_nombreLS($id_equipo2);
       $id_evento=$row['id_evento'];
       $id_partido=$row['id_partido'];
       $tipo_e=$row['tipo_e'];


print "id_partido=$id_partido....tipoe:$tipo_e<br>$nom_eq1 vs $nom_eq2<br>";
       //recorrer partidos cargados para obtener estado y marcador
       $i=0;
       $marcador="";
       print "num_partidos_cargados=$num_partidos_cargados<br>";
       while ($i<=$num_partidos_cargados){
       	   $nom1=$partidos[$i]['eq1'];
       	   $nom2=$partidos[$i]['eq2'];
//       	   print "<br><br>$i revisando:<br>";
//      	   print_r($partidos[$i]);
       	   if ($partidos[$i]['eq1']==$nom_eq1 and $partidos[$i]['eq1']==$nom_eq1){
//       	   	print "encontre!!!!";
//       	   	print_r($partidos[$i]);
                preg_match_all('!\d+!', $partidos[$i]['marcador'], $matches);
                $m1=$matches[0][0];
                $m2=$matches[0][1];
                $estado=$partidos[$i]['estado'];
                $estado=str_replace(' ', '', $estado);
//       	      print_r($partidos[$i]);
       	   }
           $i++;
       }

       print "partido va: $nom_eq1 *$m1* - $nom_eq2 *$m2* ....".$partidos[$i]['marcador']."<br>estado: *$estado*<br><br>";

       //validar si hay q actualizar marcador
       $query_marcador="SELECT goles1,goles2 FROM partidos WHERE id_partido='$id_partido'";
       $stmt_marcador = $db->query($query_marcador);
   	   $row_marcador=$stmt_marcador->fetch(PDO::FETCH_ASSOC);
       $marc1=$row_marcador['goles1'];
       $marc2=$row_marcador['goles2'];

       if (($marc1!= $m1 or $marc2!=$m2) and ($m1!='?' and $m2!='?')){
           $query_update="UPDATE partidos SET goles1='$m1', goles2='$m2' WHERE id_partido='$id_partido'";
           $stmt_update = $db->query($query_update);
           print "query update=$query_update<br>id_evento=$id_evento<br>";
           $cambio_marcador=true;
       }else{
           print "No ha cambiado el marcador...no hay que actualizar<br>";
           $cambio_marcador=false;
       }

       //si el partido finalizó....quitar marca de partido en progreso.
       if ($estado=="FT"){
          	$query_f="DELETE FROM partidos_iniciados WHERE id_partido=$id_partido";
          	$stmt_update = $db->query($query_f);
          	print "marcando partido finalizado";
           	audit(0,"marcando partido finalizado","partido:$id_partido, ".$equipo->get_nombre($id_equipo1)." vs ".$equipo->get_nombre($id_equipo2));
           	$partido_finalizado=true;
       }

       //si el partido es de evento y cambio marcador actualizar posiciones
       if ($tipo_e=='e' and $cambio_marcador){
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

            //          print_r($eventos);
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


	   if ($partido_finalizado and $tipo_e=="b"){  //es un partido de bolsa y terminó....depreciar y marcar
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
		       audit(0,"Error en valorizaciòn automática","bolsa=$id_evento,equipo=$id_equipo1: ".$equipo->get_nombre($id_equipo1)."equipo=$id_equipo2: ".$equipo->get_nombre($id_equipo2));
		   }

		   //validar si el partido tiene marca para ejecutar valorización
		   $query_val="SELECT marcaval FROM partidos WHERE id_partido='$id_partido'";
		   $stmt_val = $db->query($query_val);
		   $row_val=$stmt_val->fetch(PDO::FETCH_ASSOC);
		   $marcaval=$row_val['marcaval'];

		   if ($marcaval){
		       print "<br>Se debe ejecutar valorización automática<br>";
		       $id_bolsa=$id_evento;
		       $_REQUEST['confirmacion_valorizacion']=1;
		       include 'bolsa_valorizacion.php';
		   }else{
		       print "<br>NO se debe ejecutar valorización automática<br>";
		   }

       }


}

print "<br><br>****** Fin de revisión de partidos******************<br><br>";

//Apuestas Directas
$query="SELECT id_apuesta,id_equipo1,id_equipo2 FROM apuesta_directa WHERE fecha='$hoy' AND editable='0' AND pagado='0'";
print "q=$query<br>";
$stmt = $db->query($query);

$num_partidos=$stmt->rowCount();
print "Procesando $num_partidos apuestas directas<br><br>";


while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
	   $cambio_marcador=false;
	   $partido_finalizado=false;

       //obtener los nombres de los equipos
       $id_equipo1=$row['id_equipo1'];
       $id_equipo2=$row['id_equipo2'];
       $nom_eq1=$equipo->get_nombreLS($id_equipo1);
       $nom_eq2=$equipo->get_nombreLS($id_equipo2);
       $id_apuesta=$row['id_apuesta'];
print "id_apuesta=$id_apuesta....<br>$nom_eq1 vs $nom_eq2<br>";
       //recorrer partidos cargados para obtener estado y marcador
       $i=1;
       print "num_partidos_cargados=$num_partidos_cargados<br>";
       while ($i<=$num_partidos_cargados){
       	   $nom1=$partidos[$i]['eq1'];
       	   $nom2=$partidos[$i]['eq2'];
//       	   print "$i revisando:<br>";
//       	   print_r($partidos[$i]);
       	   if ($partidos[$i]['eq1']==$nom_eq1 and $partidos[$i]['eq1']==$nom_eq1){
                preg_match_all('!\d+!', $partidos[$i]['marcador'], $matches);
                $m1=$matches[0][0];
                $m2=$matches[0][1];
                $estado=$partidos[$i]['estado'];
                $estado=str_replace(' ', '', $estado);
       	      print_r($partidos[$i]);
       	   }
           $i++;
       }

       print "partido va: $nom_eq1 *$m1* - $nom_eq2 *$m2* ....".$partidos[$i]['marcador']."<br>estado: *$estado*<br><br>";

       //validar si hay q actualizar marcador
       $query_marcador="SELECT goles1,goles2 FROM apuesta_directa WHERE id_apuesta='$id_apuesta'";
       $stmt_marcador = $db->query($query_marcador);
   	   $row_marcador=$stmt_marcador->fetch(PDO::FETCH_ASSOC);
       $marc1=$row_marcador['goles1'];
       $marc2=$row_marcador['goles2'];

       if (($marc1!= $m1 or $marc2!=$m2) and ($m1!='?' and $m2!='?')){
           $query_update="UPDATE apuesta_directa SET goles1='$m1', goles2='$m2' WHERE id_apuesta='$id_apuesta'";
           $stmt_update = $db->query($query_update);
           print "query update=$query_update<br>id_evento=$id_evento<br>";
           $cambio_marcador=true;
       }else{
           print "No ha cambiado el marcador...no hay que actualizar<br>";
           $cambio_marcador=false;
       }


       //si el partido finalizó....quitar marca de partido en progreso.
       if ($estado=="FT"){
          	$query_f="UPDATE apuesta_directa SET pagado='1' WHERE id_apuesta=$id_apuesta";
          	$stmt_update = $db->query($query_f);
          	print "marcando partido finalizado";
           	audit(0,"marcando partido de apuesta directa finalizado","apuesta:$id_apuesta, ".$equipo->get_nombre($id_equipo1)." vs ".$equipo->get_nombre($id_equipo2));
           	$partido_finalizado=true;

           	//seleccionar los valores a pagar
           	$query_ap="SELECT * FROM apuesta_directa WHERE id_apuesta='$id_apuesta'";
           	$stmt_ap = $db->query($query_ap);
           	$row_ap=$stmt_ap->fetch(PDO::FETCH_ASSOC);
           	$goles1=$row_ap['goles1'];
           	$goles2=$row_ap['goles2'];
           	$paga1=$row_ap['paga1'];
           	$pagae=$row_ap['pagae'];
           	$paga2=$row_ap['paga2'];

           	//ver quienes ganaron
           	$query_a="SELECT * FROM apuestasd_usuario WHERE id_apuesta='$id_apuesta'";
           	print "query de apuesta:<br>$query_a<br>";
           	$stmt_a = $db->query($query_a);
           	while($row_a=$stmt_a->fetch(PDO::FETCH_ASSOC)){

           	print_r($row_a);
           		  $id_usuario=$row_a['id_usuario'];
           		  $monto=$row_a['monto'];
           		  $apuesta=$row_a['apuesta'];

           		  if ($apuesta==1 and $goles1>$goles2){
                      $monto=$monto*$paga1;
                      $void=$usuario->incrementa_saldo($id_usuario,$monto);
                      print "usuario $id gana apuesta $apuesta<br>";
           		  	  audit(0,"Usuario $id_usuario ganó","apuesta:$id_apuesta, ".$equipo->get_nombre($id_equipo1)." vs ".$equipo->get_nombre($id_equipo2));
           		  }else if ($apuesta==2 and $goles2>$goles1){
                      $monto=$monto*$paga2;
                      $usuario->incrementa_saldo($id_usuario,$monto);
                      print "usuario $id gana apuesta $apuesta<br>";
           		      audit(0,"Usuario $id_usuario ganó","apuesta:$id_apuesta, ".$equipo->get_nombre($id_equipo1)." vs ".$equipo->get_nombre($id_equipo2));
           		  }else if ($apuesta='e' and $goles1==$goles2){
                      $monto=$monto*$pagae;
                      $usuario->incrementa_saldo($id_usuario,$monto);
                      print "usuario $id gana apuesta $apuesta<br>";
                      audit(0,"Usuario $id_usuario ganó","apuesta:$id_apuesta, ".$equipo->get_nombre($id_equipo1)." vs ".$equipo->get_nombre($id_equipo2));
           		  }else{
           		      print "usuario $id no gana apuesta $apuesta<br>";
           		      audit(0,"Usuario $id_usuario no ganó","apuesta:$id_apuesta, ".$equipo->get_nombre($id_equipo1)." vs ".$equipo->get_nombre($id_equipo2));
           		  }

           	}
       }
}



include 'includes/Close-Connection.php';
?>