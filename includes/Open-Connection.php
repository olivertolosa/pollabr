<?

/* ******************Activar este pedazo de código para deshabilitar el sitio junto con el mismo pedazo en _Policy


echo '<script type="text/javascript">
           window.location = "index.php?accion=logon"
      </script>';
*/


//definir el ambiente: 1:Producción   2:Pruebas    3:Desarrollo

$ambiente=2;


if ($ambiente==1){      //producción
   /*$usrpolla[0]="usrpolla";
   $usrpolla[1]="usrpolla1";
   $usrpolla[2]="usrpolla2";
   $usrpolla[3]="usrpolla3";
   $usrpolla[4]="usrpolla4";
   $usrpolla[5]="usrpolla5";

   $MYSQL_HOST='otolosa.powwebmysql.com';
   $MYSQL_PASSWORD='pwpolla';
   $MYSQL_DATABASE='polla';*/
   $usrpolla[0]="root";
   $usrpolla[1]="root";
   $usrpolla[2]="root";
   $usrpolla[3]="root";
   $usrpolla[4]="root";
   $usrpolla[5]="root";

   $MYSQL_HOST='localhost';
   $MYSQL_PASSWORD='cucutoche';
   $MYSQL_DATABASE='polla';
}else if ($ambiente==2){  //pruebas
   $usrpolla[0]="brpollauser";
   $usrpolla[1]="brpollauser";
   $usrpolla[2]="brpollauser";
   $usrpolla[3]="brpollauser";
   $usrpolla[4]="brpollauser";
   $usrpolla[5]="brpollauser";

   $MYSQL_HOST='localhost';
   $MYSQL_PASSWORD='bpol1234';
   $MYSQL_DATABASE='polla';
}else if ($ambiente==3){   //desarrollo
   $usrpolla[0]="root";
   $usrpolla[1]="root";
   $usrpolla[2]="root";
   $usrpolla[3]="root";
   $usrpolla[4]="root";
   $usrpolla[5]="root";

   $MYSQL_HOST='localhost';
   $MYSQL_PASSWORD='cucutoche';
   $MYSQL_DATABASE='polla_pruebas';

}




$rnd=rand(0,5);


try{
    $db = new pdo( 'sqlsrv:server='.$MYSQL_HOST.';database='.$MYSQL_DATABASE,
                    $usrpolla[$rnd],
                    $MYSQL_PASSWORD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $ex){
    die(json_encode(array('outcome' => false, 'message' => 'Error al conectarse a la BD....'.$ex->getMessage())));
}

if (!defined('MYSQL_PAGING')){
        define('MYSQL_PAGING', 20);
}


?>