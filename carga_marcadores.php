<meta charset="UTF-8">
<script>

setTimeout(function(){
   window.location.reload(1);
}, 300000);

</script>
<?
session_start();
if (isset($_SESION['usuario_polla']))
   $id_usuario=$_SESION['usuario_polla'];
else
   $id_usuario=0;	


date_default_timezone_set ('America/Bogota');
$fecha=date('Y-m-d H:i');
print $fecha."<br><br>";


if (isset($_GET['usar_alias']))
   $usar_alias=$_GET['usar_alias'];
else
  $usar_alias=true;

if (isset($_GET['fecha']))
   $fecha=$_GET['fecha'];
else
  $fecha=date('Y-m-d');


$debug=$_GET['debug'];

require_once 'includes/Open-Connection.php';
require_once 'audit.php';
require_once 'includes/class_equipo.php';
require_once 'includes/class_bolsa.php';
require_once 'includes/class_usuario.php';
require_once 'includes/class_liga.php';
require_once 'includes/class_partido.php';
require_once 'function_movimiento_plata.php';



require_once 'function_actualiza_estadisticas_equipoxevento.php';


$equipo=new equipo($db);
$bolsa=new bolsa($db);
$usuario=new usuario($id_usuario);
$liga_obj=new liga($db);
$partido_obj=new partido($db);

require_once 'function_paga_apuestad.php';



//cargar la lista de partidos de la fuente
(isset($_GET['fecha'])) ? $url='http://www.livescores.com/soccer/'.$fecha.'/' : $url='http://www.livescores.com';

//$url="http://localhost:8080/polla/fuente.txt";

print "url=$url<br>";


function get_data($url) {
	$ch = curl_init();
	$timeout = 15;
	$userAgent = 'Mozilla/5.0 (Windows NT 5.1; rv:31.0) Gecko/20100101 Firefox/31.0';
	curl_setopt( $ch, CURLOPT_USERAGENT, $userAgent );
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_ENCODING ,"");
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}


$linea = get_data($url);
//$linea=file($url);

    if ($linea==false){
    	 print "REPAILA en carga_fuente";
    	 exit();
    }else{
       print "<br>abri $url<br>";
       print "**$lines[0]**";
    }


print "<table class=\"tabla_simple\"><tr><td>#<th>Estado<th>Liga<th>Eq1<th>Marcador<th>Eq2";
   unset($matches);
//   $linea=fgets($lines);
//   $linea=utf8_encode($linea);
   $encontro=preg_match_all('/<div class="row-gray[\seven]+" data-pid="\d*" data-eid="\d*" data-type="\w*" data-esd="\d*"> <div class="min">[\w\d\/\.=\"\s<>:]* [\S\d]* <\/div> <div class="ply tright name"> [\d\.O&#x27;\w\s-\*]* <\/div> <div class="sco"> .*? [\s\d\?-]+.*?<div class="ply name"> [\d\.O&#x27;\w\s-\*]*?<\/div> <\/div>/',$linea,$matches);
   if ($encontro){
   	   $encontradas=count($matches[0]);
   	   $num_partidos_cargados=1;
   	   foreach ($matches[0] as $cadena){
   	   	   preg_match('/<div class="row-gray[\seven]+" data-pid="\d*" data-eid="\d*" data-type="\w*" data-esd="\d*"> <div class="min">([\w\d\/\.=\"\s<>:]* [\S\d]*) <\/div> <div class="ply tright name"> ([\d\.O&#x27;\w\s-\*]*) <\/div> <div class="sco"> (?:.*>)?([\d\?] - [\d\?]).*?<div class="ply name"> ([\d\.O&#x27;\w\s-\*]*)? <\/div> <\/div>/',$cadena,$matches2);
   	   	   $estado=$matches2[1];
   	   	   $eq1=$matches2[2];
   	   	   $eq1=str_replace('*','',$eq1); //remover el * que aparece a veces

   	   	   $marcador=$matches2[3];
   	   	   $eq2=$matches2[4];
   	   	   $eq2=str_replace('*','',$eq2); //remover el * que aparece a veces

   	   	   //cargar la competencia
   	   	   unset($matches3);
   	   	   $competencia='';
   	   	   preg_match('/<div class="left"> <a href="[\w\d\/\.-]*"><strong>([\s\w]*)<\/strong><\/a> - <a href="[\w\d\/\.-]*">([\(\)\s\w:äëïöüáéíóú\.-|]*)<\/a> <\/div>.*?'.$eq1.'/',$linea,$matches3);


//   	   	   print "enc_comp=$enc_comp<br>";
//   	   	   print "match:*".$matches3[0]."*<br>";
//  	   	   print_r($matches3);
           //en lo encontrado ubicar la última liga

           preg_match_all('/<div class="left"> <a href="[\w\d\/.-]*"><strong>([\(\)\s\w:äëïöüáéíóú.-]*)<\/strong><\/a> - <a href="([\w\d\/\.-]*)">([\(\)\s\w:äëïöüáéíóú.-|-]*)<\/a> <\/div>/',$matches3[0],$ligas);
//print_r($ligas);
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

   	   	   	  if (substr($competencia,0,strpos($competencia,'::'))){
      	   	   	  $competencia=substr($competencia,0,strpos($competencia,':'));
              }


   	   	   	  if ($usar_alias){
      	   	   	  //validar si la competencia tiene un alias
      	   	   	  $query_alias="SELECT * FROM traducciones WHERE original='$competencia'";
if ($debug) print "q=$query_alias<br>";
//exit();
     	   	   	  $stmt_alias = $db->query($query_alias);
   	     	   	  if ($stmt_alias->rowCount()>0){
if ($debug) print "si tiene alias<br>";
   	   	     	     $row_alias=$stmt_alias->fetch(PDO::FETCH_ASSOC);
   	   	     	     $id_competencia=$row_alias['id_liga'];
   	   	   	         if ($id_competencia==0)
      	   	   	       $competencia=$row_alias['traducido'];
         	   	   	  else{
                        $competencia=$liga_obj->get_nombre($id_competencia);
         	   	   	  }
   	   	      	  }
   	   	       }
   	   	  }

   	   	   $query="SELECT id_equipo,id_grupo_equipos FROM equipos WHERE equipoLS='$eq1' OR equipoLS2='$eq1' OR equipoLS3='$eq1'";
   	   	   //print "<br>q=$query<br>";

//if ($num_partidos_cargados==12) exit();


   	   	   $stmt=$db->query($query);
   	   	   if (!empty($stmt) && $stmt->rowCount()>0){
			   if ($debug) print "confirmando equipo en la misma liga del contrario...<br>";
   	   	   	   $row=$stmt->fetch(PDO::FETCH_ASSOC);
               $eq1_ls=$row['id_equipo'];
               $cuantas1=$stmt->rowCount();
			   if ($debug) print "equipo encontrado hasta ahora :$eq1_ls<br>";
               if ($cuantas1>1){
                  if($debug) print "hay ma de un equipo con el mismo nombre.....revalidando!!!....$eq1_ls<br>";
               	   $query="SELECT id_equipo FROM equipos WHERE (equipoLS='$eq1' OR equipoLS2='$eq1' OR equipoLS3='$eq1') AND id_grupo_equipos='$id_competencia'";
               	   $stmt = $db->query($query);
               	   if ($stmt->rowCount()==1){
					   $row=$stmt->fetch(PDO::FETCH_ASSOC);
                       $eq1_ls=$row['id_equipo'];
                   }else { //hay mas de uno y no es de la misma liga.....poner cualquiera para q se registre el partido
		               if ($debug) print "hay mas equipos pero no en la misma liga....obteniendo primer nombre<br>";
		               $query="SELECT id_equipo,id_grupo_equipos FROM equipos WHERE equipoLS='$eq1' OR equipoLS2='$eq1' OR equipoLS3='$eq1'";
			           $stmt = $db->query($query);
					   $row=$stmt->fetch(PDO::FETCH_ASSOC);
                       $eq1_ls=$row['id_equipo'];
			       }
			    }	   
		   }else{
   	   	   	   $eq1_ls=0;
   	   	   }



   	   	   ($eq1_ls>0)? $fondo1="green" : $fondo1="red";

   	   	   $query="SELECT id_equipo,id_grupo_equipos FROM equipos WHERE equipoLS='$eq2' OR equipoLS2='$eq2' OR equipoLS3='$eq2'";
   	   	   $stmt = $db->query($query);
   	   	   if (!empty($stmt) && $stmt->rowCount()>0){
   	   	   	   $row=$stmt->fetch(PDO::FETCH_ASSOC);
               $eq2_ls=$row['id_equipo'];
               $cuantas2=$stmt->rowCount();
               if ($cuantas2>1){
                   if($debug) print "hay ma de un equipo con el mismo nombre.....revalidando!!!....$eq1_ls<br>";
               	   $query="SELECT id_equipo FROM equipos WHERE (equipoLS='$eq2' OR equipoLS2='$eq2' OR equipoLS3='$eq2') AND id_grupo_equipos='$id_competencia'";
               	   $stmt = $db->query($query);
               	   if ($stmt->rowCount()==1){
					   $row=$stmt->fetch(PDO::FETCH_ASSOC);
                       $eq2_ls=$row['id_equipo'];
                   }else { //hay mas de uno y no es de la misma liga.....poner cualquiera para q se registre el partido
		               if ($debug) print "hay mas equipos pero no en la misma liga....obteniendo primer nombre<br>";
		               $query="SELECT id_equipo,id_grupo_equipos FROM equipos WHERE equipoLS='$eq2' OR equipoLS2='$eq2' OR equipoLS3='$eq2'";
			           $stmt = $db->query($query);
					   $row=$stmt->fetch(PDO::FETCH_ASSOC);
                       $eq2_ls=$row['id_equipo'];
			       }
			    }	   
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

   	   	   print "<tr><td>$num_partidos_cargados<td>$estado<td style=\"background-color: $fondol;\">$competencia<td style=\"background-color: $fondo1;\">$cuantas 1 .. $eq1_ls- $eq1<td>$marcador<td style=\"background-color: $fondo2;\">$cuantas2 .. $eq2_ls - $eq2\n";
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
	$id_competencia=$partidos[$i]['id_competencia'];
	$competencia=$partidos[$i]['competencia'];
	$marcador=trim($partidos[$i]['marcador']);
	$goles1=substr($marcador,0,strpos('-',$marcador)+1);
	$goles2=substr($marcador,strpos('-',$marcador)+4);
	$hoy=$fecha;
	$ayer=date('Y-m-d', strtotime($hoy .' -1 day'));

	if (($estado=="FT" or $estado=="AET")and $id_eq1!=0 and $id_eq2!=0){if ($debug)	   print "<br><br>$i cargando partido $eq1 Vs $eq2<br>";
	   //validar si el partido ya existe
	   $query="SELECT id_partido FROM partidos2 WHERE id_equipo1=$id_eq1 AND id_equipo2=$id_eq2 AND (fecha='$hoy' OR fecha='$ayer')";
	   $stmt = $db->query($query);
	   if ($stmt->rowCount()==0){
	      print "se debe registrar el partido $competencia --> $eq1:$goles1 vs $eq2:$goles2<br>";
	      $query="INSERT INTO partidos2 VALUES('','$id_eq1','$id_eq2','$id_competencia','$goles1','$goles2','0','0','$hoy','$competencia')";
	      $stmt = $db->query($query);
          $partidos_registrados++;
          audit_carga("registrando partido $competencia --> $eq1:$goles1 vs $eq2:$goles2");
	   }else{
if ($debug)	   	  print "partido $competencia --> $eq1 vs $eq2 ya está registrado<br>";
	   }
	}

   $i++;
}

print "<br><br>Total partidos registrados: $partidos_registrados<br><br>";
audit_carga("Total partidos registrados: $partidos_registrados");

print "<br><br>*************fin de carga de partidos históricos*********************<br>";

/*************************************************************************************
               Inicio de Registro de partidos finalizados
**************************************************************************************/

//partidos de bolsa y polla
//cargar partidos del dia marcados como iniciados
$hoy=date("Y-m-d");
$querypi="SELECT id_partido,id_evento,id_equipo1,id_equipo2,tipo_e FROM partidos WHERE id_partido IN(SELECT id_partido FROM partidos_iniciados)";

if ($debug) print "q=$querypi<br>";
$stmtpi = $db->query($querypi);

$num_partidos=$stmtpi->rowCount();

print "Procesando $num_partidos partidos<br><br>";


while($rowpi=$stmtpi->fetch(PDO::FETCH_ASSOC)){

	   $cambio_marcador=false;
	   $partido_finalizado=false;

       //obtener los nombres de los equipos
       $id_equipo1=$rowpi['id_equipo1'];
       $id_equipo2=$rowpi['id_equipo2'];
       $nom_eq1=$equipo->get_nombreLS($id_equipo1);
       $nom_eq2=$equipo->get_nombreLS($id_equipo2);
	   $nom_eqsp1=$equipo->get_nombre($id_equipo1);
       $nom_eqsp2=$equipo->get_nombre($id_equipo2);
       $id_evento=$rowpi['id_evento'];
       $id_partido=$rowpi['id_partido'];
       $tipo_e=$rowpi['tipo_e'];


print "id_partido=$id_partido....tipoe:$tipo_e<br>$nom_eqsp1 vs $nom_eqsp2<br>";
       //recorrer partidos cargados para obtener estado y marcador
       $i=0;
       $marcador="";
if ($debug) print "num_partidos_cargados=$num_partidos_cargados<br>";
       while ($i<=$num_partidos_cargados){
       	   $nom1=$partidos[$i]['eq1'];
       	   $nom2=$partidos[$i]['eq2'];
if ($debug) print "<br><br>$i revisando:<br>";
if ($debug) print_r($partidos[$i]);
       	   if ($partidos[$i]['eq1']==$nom_eq1 and $partidos[$i]['eq1']==$nom_eq1){
				if ($debug) print "encontre!!!!";
				if ($debug) print_r($partidos[$i]);
                preg_match_all('!\d+!', $partidos[$i]['marcador'], $matches);
                $m1=$matches[0][0];
                $m2=$matches[0][1];
                $estado=$partidos[$i]['estado'];
                $estado=str_replace(' ', '', $estado);
         	    if ($debug)   print_r($partidos[$i]);
       	   }
           $i++;
       }

       print "partido va: $nom_eqsp1 *$m1* - $nom_eqsp2 *$m2* ....".$partidos[$i]['marcador']."<br>estado: *$estado*<br><br>";

       //validar si hay q actualizar marcador
       $query_marcador="SELECT goles1,goles2 FROM partidos WHERE id_partido='$id_partido'";
       $stmt_marcador = $db->query($query_marcador);
   	   $row_marcador=$stmt_marcador->fetch(PDO::FETCH_ASSOC);
       $marc1=$row_marcador['goles1'];
       $marc2=$row_marcador['goles2'];

       if (($marc1!= $m1 or $marc2!=$m2) and ($m1!='?' and $m2!='?') and ($m1>=0 and $m1!='')){
		   print "hubo cambio de marcador<br>";
		   
		   //registrar el evento
		   $evento="$nom_eqsp1 ";
		   if ($marc1 != $m1 and $m1>=0){
                $evento.="($m1)";
		   }else{
			    $evento.=" $m1";
		   }
		   $evento.=" - $nom_eqsp2 ";
		   if ($marc2 != $m2){
               $evento.="($m2)";
		   }else{
			    $evento.=" $m2";
		   }           print "Registrando evento: $evento<br>";
		   
		   $query_evento="INSERT INTO polla_eventos VALUES ('','$id_partido','$evento')";
		   $stmt_evento = $db->query($query_evento);
		   $id_evento_polla=$db->lastInsertId();;
		   
		   //fin de registro de evento
		   
           $query_update="UPDATE partidos SET goles1='$m1', goles2='$m2' WHERE id_partido='$id_partido'";
           $stmt_update = $db->query($query_update);
           //print "query update=$query_update<br>id_evento=$id_evento<br>";
           $cambio_marcador=true;
           audit_carga("partido entre $nom_eq1 vs $nom_eq2 cambio marcador: $m1 - $m2");
		   
		   //si x alguna razón el partido sigue estando editable.....desmarcar
		   $query_editable="SELECT editable FROM partidos WHERE id_partido='$id_partido'";
		   $stmt_editable = $db->query($query_editable);
		   $row_editable=$stmt_editable->fetch(PDO::FETCH_ASSOC);
		   $editable=$row_editable['editable'];
		   if ($editable==1){
			   audit_carga("el partido está editable!!!!....procediendo a bloquear");
			   $query_editable="UPDATE partidos SET editable=0 WHERE id_partido='$id_partido'";
			   $stmt_editable = $db->query($query_editable);
			   audit_carga("Partido bloqueado!!!");
		   }
		   
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

           	//adicionar a la lista de partidos finalizados para revisar duelos
           	$partidos_duelos[]='p-'.$id_partido;
       }

       //si el partido es de evento y cambio marcador actualizar posiciones
       if ($tipo_e=='e' and $cambio_marcador){
            audit_carga("es partido de polla y cambio marcador....se debe actualizar tabla de posiciones");
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
                print "<br><b>Actualizando posiciones en evento $eventos[$num_eventos] por partido $id_partido</b><br>";
                audit_carga("Actualizando posiciones en evento $eventos[$num_eventos] por partido $id_partido");
                $id_evento=$eventos[$num_eventos];
     			include 'genera_posiciones.php';
				
				$id_evento=$partido_obj->get_id_evento($id_partido);
				
				//registrar las posiciones en la tabla de historia de la polla
                $query="SELECT 	u.id_usuario,uxe.puntos,uxe.marcadores_exactos,uxe.ganadorempate,uxe.marcador1
                        FROM usuarios as u, usuariosxevento as uxe
                        WHERE u.id_usuario=uxe.id_usuario AND uxe.id_evento='$id_evento'";
						
				//validar si el evento requiere validar los usuarios
				$queryv="SELECT conf_usuarios FROM eventos WHERE id_evento='$id_evento'";
				$stmtv=$db->query($queryv);
				$rowv=$stmtv->fetch(PDO::FETCH_ASSOC);
				if ($rowv['conf_usuarios'])
					$query.=" AND uxe.validado='1'";	

				$query.=" ORDER BY uxe.puntos DESC,uxe.marcadores_exactos DESC,uxe.ganadorempate DESC,uxe.marcador1 DESC";
				$stmt2=$db->query($query);
				
				//print "<br><br>q=$query<br><br>";
				
				$pos=1;
				
				while ($row2=$stmt2->fetch(PDO::FETCH_ASSOC)){
				    $id_usuario=$row2['id_usuario'];
					$puntos=$row2['puntos'];
					$ganadorempate=$row2['ganadorempate'];
					$marcadores_exactos=$row2['marcadores_exactos'];
					$marcador1=$row2['marcador1'];
					
					$query_posiciones="INSERT INTO polla_posiciones_historia VALUES ('$id_evento_polla','$id_usuario','$pos','$puntos','$marcadores_exactos','$ganadorempate','$marcador1')";
					$stmt_posiciones=$db->query($query_posiciones);
					
					//print "<br><br>q=$query_posiciones<br><br>";
					
					$pos++;
				}
				
                //actualzar estadisticas del equipo en el evento
                actualizar_estadisticas_equipoxevento($id_equipo1,$id_evento);
                actualizar_estadisticas_equipoxevento($id_equipo2,$id_evento);
            }
	   }


	   if ($partido_finalizado and $tipo_e=="b"){  //es un partido de bolsa y terminó....depreciar y marcar
           audit(0,"  ","  ");
           audit(0,"Si es partido de bolsa","partido: $id_partido...poniendo marca valorización y depreciando");
           audit_carga("Partido de bolsa...poniendo marca valorización y depreciando");
          //obtener el id de la bolsa
	       if ($m1>$m2){
               //si la acción vale 0 no se pone marca
               if ($bolsa->get_valor_accion($id_evento,$id_equipo1)>0){
                   	audit_carga("Marca automática para valorización: bolsa=$id_evento,equipo=$id_equipo1: ".$equipo->get_nombre($id_equipo1));
		    	    $bolsa->marca_valorizacion($id_evento,$id_equipo1);
	           }else{
                    audit_carga("Equipo eliminado....no se marca para valorización: bolsa=$id_evento,equipo=$id_equipo2: ".$equipo->get_nombre($id_equipo2));
               }
               audit_carga("Depreciación automática: bolsa=$id_evento,equipo=$id_equipo2: ".$equipo->get_nombre($id_equipo2));
               $bolsa->depreciar($id_evento,$id_equipo2,"p");
		   }else if ($m2>$m1){
		       if ($bolsa->get_valor_accion($id_evento,$id_equipo2)>0){
                  audit_carga ("Marca automática para valorización: bolsa=$id_evento,equipo=$id_equipo2: ".$equipo->get_nombre($id_equipo2));
				  $bolsa->marca_valorizacion($id_evento,$id_equipo2);
			   }else{
                  audit_carga("Equipo eliminado....no se marca para valorización: bolsa=$id_evento,equipo=$id_equipo2: ".$equipo->get_nombre($id_equipo2));
               }
               audit_carga("Depreciación automática: bolsa=$id_bolsa,equipo=$id_equipo1: ".$equipo->get_nombre($id_equipo1));
               $bolsa->depreciar($id_evento,$id_equipo1,"p");
		   }else if ($m2==$m1){
               audit_carga("Depreciación automática: bolsa=$id_evento,equipo=$id_equipo21 ".$equipo->get_nombre($id_equipo1));
               $bolsa->depreciar($id_evento,$id_equipo1,"e");
               audit_carga("Depreciación automática: bolsa=$id_bolsa,equipo=$id_equipo2: ".$equipo->get_nombre($id_equipo2));
               $bolsa->depreciar($id_evento,$id_equipo2,"e");
		   }else{
		       audit_carga("Error en valorizaciòn automática: bolsa=$id_evento,equipo=$id_equipo1: ".$equipo->get_nombre($id_equipo1)."equipo=$id_equipo2: ".$equipo->get_nombre($id_equipo2));
		   }

		   //validar si el partido tiene marca para ejecutar valorización
		   $query_val="SELECT marcaval FROM partidos WHERE id_partido='$id_partido'";
		   $stmt_val = $db->query($query_val);
		   $row_val=$stmt_val->fetch(PDO::FETCH_ASSOC);
		   $marcaval=$row_val['marcaval'];

		   if ($marcaval){
		       print "<br>Se debe ejecutar valorización automática<br>";
		       audit_carga("Ejecutando valorización automática");
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
$query="SELECT id_apuesta,id_equipo1,id_equipo2 FROM apuesta_directa WHERE editable='0' AND pagado='0'";
if ($debug)print "q=$query<br>";
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
       if ($estado=="FT" or $estado=="AET"){
           pagar_apuesta($id_apuesta);
           $partidos_duelos[]='d-'.$id_apuesta;
       }
}


print "**************************************************************************************<br>";
print "                                 Revisando duelos<br>";
print "**************************************************************************************<br>";

//revisar duelos
$num_partidos=sizeof($partidos_duelos);



print "posibles partidos de duelo: $num_partidos<br><br>";
print_r($partidos_duelos);

foreach($partidos_duelos as $id_partido){
   print "<br>****************************************************<br>
           Revisando partido $id_partido<br>";

   $query="SELECT * FROM duelos WHERE id_partido='$id_partido'";
   $stmt = $db->query($query);

   $num_duelos=$stmt->rowCount();

   if ($num_duelos==0){
   	   print "no hay duelos para este partido<br><br>";
   }else{
       print "Existen $num_duelos duelos para este partido";

       //validar quien ganó
       if ($clase_partido=='p')
	      $query_marcador="SELECT id_equipo1,id_equipo2,fecha,hora FROM partidos WHERE id_partido='$id_partido'";
	   else if ($clase_partido=='d')
	      $query_marcador="SELECT id_equipo1,id_equipo2,fecha,hora FROM apuesta_directa WHERE id_apuesta='$id_partido'";
	   //print "q=$query<br>";
	   $stmt_marcador=$db->query($query_marcador);
	   $row_marcador=$stmt_marcador->fetch(PDO::FETCH_ASSOC);

	   $id_equipo1=$row_marcador['id_equipo1'];
	   $id_equipo2=$row_marcador['id_equipo2'];
	   $goles1=$row_marcador['goles1'];
	   $goles2=$row_marcador['goles2'];

	   if ($goles1>$goles2){
	   	  $gana=1;
	   }else if ($goles2>$goles1){
	   	  $gana=2;
	   }else if ($goles1==$goles2){
	   	  $gana='e';
	   }


	   //verificar cada duelo quien ganó
	   while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
	      $id_duelo=$row['id_duelo'];
	      $id_equipo1=$row['id_equipo1'];
	      $id_equipo2=$row['id_equipo2'];
	      $id_usuario1=$row['id_usuario1'];
	      $id_usuario2=$row['id_usuario2'];
	      $ap1=$row['ap1'];
	      $ap2=$row['ap2'];
	      $monto=$row['monto'];

	      if (($ap1==1 and $gana==1) or ($ap1==2 and $gana==2) or ($ap1=='e' and $gana=='e')){   //ganó el 1
	          $ganado=$monto+(0.95*$monto);
	      	  $query_devolver="UPDATE usuarios SET saldo=saldo+$ganado WHERE id_usuario='$id_usuario1'";
	          $stmt_devolver = $db->query($query_devolver);
              movimiento_plata($id_usuario1,$ganado,"+","Ganó duelo $id_duelo");
	      }else if (($ap2==1 and $gana==1) or ($ap2==2 and $gana==2) or ($ap2=='e' and $gana=='e')){  //ganó el 2
	      	  $ganado=$monto+(0.95*$monto);
	      	  $query_devolver="UPDATE usuarios SET saldo=saldo+$ganado WHERE id_usuario='$id_usuario2'";
	          $stmt_devolver = $db->query($query_devolver);
              movimiento_plata($id_usuario2,$ganado,"+","Ganó duelo $id_duelo");
	      }else{   //nadie ganó
	          //devolver la plata a los dos
	          $query_devolver="UPDATE usuarios SET saldo=saldo+$monto WHERE id_usuario='$id_usuario1'";
	          $stmt_devolver = $db->query($query_devolver);
              movimiento_plata($id_usuario1,$monto,"+","Duelo empatado ... nadie ganó");
              $query_devolver="UPDATE usuarios SET saldo=saldo+$monto WHERE id_usuario='$id_usuario2'";
	          $stmt_devolver = $db->query($query_devolver);
              movimiento_plata($id_usuario2,$monto,"+","Duelo empatado ... nadie ganó");

	      }
	   }
   }

}



if (!isset($_SESSION['usuario_polla']))
   include 'includes/Close-Connection.php';
?>