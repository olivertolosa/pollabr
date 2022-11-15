<?
session_start();

?>
<center>
<link rel="stylesheet" type="text/css" href="css/polla.css" />
<link rel="stylesheet" href="css/jquery.modal.css" type="text/css" media="screen" />


<?

require_once 'includes/Open-Connection.php';
include 'audit.php';
include 'includes/class_equipo.php';

$equipo_obj=new equipo($db);

$id_liga=$_GET['id_liga'];

//obtener url de la liga
$query="SELECT link_LS FROM grupos_equipos WHERE id_grupo_equipos='$id_liga'";
//print "q=$query<br>";

$stmt = $db->query($query);
$row=$stmt->fetch(PDO::FETCH_ASSOC);
$url=$row['link_LS'];


// print "url:$url<br>";


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
    	 print "No fue posible cargar los datos";
    	 exit();
    }else{
     //  print "<br>abri $url<br>";
    }


print "<div style=\"width:500px;\"><table class=\"tabla_con_encabezado\" style=\"font-size:11px\">
           <thead><th style=\"text-align:center\">#<th colspan=\"2\" style=\"text-align:center\">Equipo<th style=\"text-align:center\">J<th style=\"text-align:center\">G<th style=\"text-align:center\">E<th style=\"text-align:center\">P<th style=\"text-align:center\">GF<th style=\"text-align:center\">GC<th style=\"text-align:center\">GD<th style=\"text-align:center\">Ptos</thead><tbody>";
   unset($matches);

//   $linea=utf8_encode($linea);
//   $encontro=preg_match_all('/<div class="team">\s*[\d\.O&#x27;\w\s-\*]*\s*<\/div> <div class="pts">\d*<\/div> <div class="pts">\d*<\/div> <div class="pts">\d*<\/div> <div class="pts">\d*<\/div> <div class="pts">\d*<\/div> <div class="pts">\d*<\/div> <div class="pts">[-\d]*<\/div> <div class="pts tot">\d*<\/div>',$linea,$matches);
     $encontro=preg_match_all('/<div class="team">\s*([\d\.O&#x27;\w\s-\*]*)\s*<\/div> <div class="pts">(\d*)<\/div> <div class="pts">(\d*)<\/div> <div class="pts">(\d*)<\/div> <div class="pts">(\d*)<\/div> <div class="pts">(\d*)<\/div> <div class="pts">(\d*)<\/div> <div class="pts">([-\d]*)<\/div> <div class="pts tot">(\d*)<\/div>/',$linea,$matches);
//   print_r($matches);
   if ($encontro){

   	   $encontradas=count($matches[0]);
   	   //$num_equipos_cargados=0;
   	  // foreach ($matches[0] as $cadena){
           //preg_match('/<div class="team">\s*([\d\.O&#x27;\w\s-\*]*)\s*<\/div> <div class="pts">(\d*)<\/div> <div class="pts">(\d*)<\/div> <div class="pts">(\d*)<\/div> <div class="pts">(\d*)<\/div> <div class="pts">(\d*)<\/div> <div class="pts">(\d*)<\/div> <div class="pts">([-\d]*)<\/div> <div class="pts tot">(\d*)<\/div>/',$cadena,$matches2);
           //print_r($cadena);
   	   for ($num_equipos_cargados=0, $i=1; $num_equipos_cargados< $encontradas;  $num_equipos_cargados++, $i++){
   	  	   $equipo=$matches[1][$num_equipos_cargados];
   	   	   $jugados=$matches[2][$num_equipos_cargados];
   	   	   $ganados=$matches[3][$num_equipos_cargados];
   	   	   $empatados=$matches[4][$num_equipos_cargados];
   	   	   $perdidos=$matches[5][$num_equipos_cargados];
   	   	   $gf=$matches[6][$num_equipos_cargados];
   	   	   $gc=$matches[7][$num_equipos_cargados];
   	   	   $gd=$matches[8][$num_equipos_cargados];
   	   	   $ptos=$matches[9][$num_equipos_cargados];


   	   	   $query="SELECT id_equipo,equipo FROM equipos WHERE equipoLS='$equipo' AND id_grupo_equipos='$id_liga'";
//   	   	   print "<br>q=$query<br>";
   	   	   $stmt = $db->query($query);
   	   	   $row=$stmt->fetch(PDO::FETCH_ASSOC);
   	   	   $id_equipo=$row['id_equipo'];
   	   	   $eq=$row['equipo'];

$img=$equipo_obj->get_imagen($id_equipo);


   	   	   print "<tr><td>$i<td><img src=\"".$equipo_obj->get_imagen($id_equipo)."\" class=\"img_small\">
   	   	              <td>$equipo<td style=\"text-align:center\">$jugados
   	   	              <td style=\"text-align:center\">$ganados
   	   	              <td style=\"text-align:center\">$empatados
   	   	              <td style=\"text-align:center\">$perdidos
   	   	              <td style=\"text-align:center\">$gf
   	   	              <td style=\"text-align:center\">$gc
   	   	              <td style=\"text-align:center\">$gd
   	   	              <td style=\"text-align:center\">$ptos\n";
   	   	   //$num_equipos_cargados++;

   	   }

   }else{
   	  print "No se encontraron datos";
   }
print"</tbody></table></div>";

include 'includes/Close-Connection.php';
?>
</center>
