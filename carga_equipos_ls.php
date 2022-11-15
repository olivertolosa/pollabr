<?php


//debe haberse cargado la url en la variable $url
// devuelve un arreglo ($equipos_ls) con los equipos cargados

function get_data($url) {
	
	
	$ch = curl_init();
	
	//$curl_log = fopen("curl.txt", 'a+'); // open file for READ and write
	
	//fwrite($curl_log, $url);
	
	
	$timeout = 15;
	$userAgent = 'Mozilla/5.0 (Windows NT 5.1; rv:31.0) Gecko/20100101 Firefox/31.0';
	curl_setopt( $ch, CURLOPT_USERAGENT, $userAgent );
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_ENCODING ,"");
	$data = curl_exec($ch);
	curl_close($ch);
	
	//fwrite($curl_log, $data);

	
/*rewind($curl_log);
$output= fread($curl_log);
echo "<pre>". print_r($output, 1). "</pre>";
fclose($curl_log);
unlink($curl_log);*/
	

	return $data;
}

function carga_equipos_ls($url,$ocultar_errores=TRUE){
   $linea = get_data($url);

//   print "<br>$url<br>";

   if ($linea==false and !$ocultar_errores){
    	 print "<br>REPAILA en carga_fuente<br>";
    	 //exit();
   }else{
      // print "<br>abri $url<br>";
//       print "**$lines[2]**";
   }


   unset($matches);

   $encontro=preg_match_all('/<div class="team">[^<]*<\/div>/u',$linea,$matches);
   if ($encontro){
      $encontradas=count($matches[0]);
      $num_equipos_cargados=1;

      //cargar los equipos de LS en un arreglo
      foreach ($matches[0] as $cadena){
         preg_match('/<div class="team">\s*([^<]*)<\/div>/',$cadena,$matches2);
   	     $equipos_ls[$num_equipos_cargados]=rtrim(str_replace('*','',$matches2[1]));
         $num_equipos_cargados++;
      }
   }else if (!$ocultar_errores){      print "<br>no se encontraron coincidencias<br>";
   }

   return $equipos_ls;
}



?>