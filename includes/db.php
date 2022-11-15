<?php
/*
Wrapper para manejo de bd..por ahora mysql y mssql

*/

class db{   var $server;
   var $user;
   var $password;
   var $motorbd;
   var $base;
   var $link;


   function db (){   //constructor...lee las variables del archivo de configuración
/*  include 'config.php';
     $this->server=$bdserver;
      $this->user=$bduser;
      $this->password=$bdpassword;
   	  $this->motorbd=$dbmotor;
   	  $this->base=$dbname;
*/
      $this->server="wsqlpru1a";
      $this->user="conti";
      $this->password="@ctasc0nt1";
   	  $this->motorbd="mssql";
   	  $this->base="Actas_Conti";
   }

   function db_connect($base = "bitacora"){//   	require 'config.php';

   	  if ($base=="SM"){     	  $this->server="Wsql20082";
    	  $this->user="consultasm";
    	  $this->password="Mesa09";
   	      $this->motorbd="mssql";
   	      $this->base="SMPROD";
      }
   	  if ($this->motorbd=="mysql"){
         $this->link = mysql_connect($this->server, $this->user, $this->password) or die(mysql_error());
         mysql_select_db($this->base, $this->link) or die(mysql_error());
      }else if ($this->motorbd=="mssql"){         try{         	 $this->link = sqlsrv_connect($this->server, array("Database" => $this->base,"UID" => $this->user, "PWD" => $this->password,'ReturnDatesAsStrings'=>true));
         	 if ( $this->link === false ) {         	 	  throw new Exception();
         	 }
         }
            catch (Exception $e)
        {
          if( ($errors = sqlsrv_errors() ) != null){
             foreach( $errors as $error ) {
               echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
               echo "code: ".$error[ 'code']."<br />";
               echo "message: ".$error[ 'message']."<br />";
               if (!isset($_SESSION['error_msg']))
                  $_SESSION['error_msg']=$error[ 'code'];
             }
          }
        }
      }
   }


   function db_disconnect(){
   	  if ($this->motorbd=="mysql"){
         mysql_close($this->link) or die(mysql_error());
      }else if ( $this->motorbd=="mssql"){
         try{         	if (!sqlsrv_close($this->link));
         	   throw new Exception();
         }
         catch (Exception $e)
        {
          if( ($errors = sqlsrv_errors() ) != null){
             foreach( $errors as $error ) {
               echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
               echo "code: ".$error[ 'code']."<br />";
               echo "message: ".$error[ 'message']."<br />";
               if (!isset($_SESSION['error_msg']))
                  $_SESSION['error_msg']=$error[ 'code'];
             }
          }
        }
     }
  }



   function db_query($query){   	  if ( $this->motorbd=="mysql"){   	      $result=mysql_query($query) or die(mysql_error());
   	      return $result;
   	  }else if ( $this->motorbd=="mssql"){   	  	try{   	      if (!$result=sqlsrv_query($this->link,$query,array(),array("Scrollable" => 'KEYSET'))){   	         throw new Exception();
   	      }

   	    }
   	    catch (Exception $e)
        {
       if( ($errors = sqlsrv_errors() ) != null){
          foreach( $errors as $error ) {
            echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
            echo "code: ".$error[ 'code']."<br />";
            echo "message: ".$error[ 'message']."<br />";
            echo "query: ". $query."<br />";
            if (!isset($_SESSION['error_msg']))
               $_SESSION['error_msg']=$error[ 'code'];
        }
       // exit();
}

//          $mesg=$mesg[2];
//          print "mesg=$mesg";
//          exit();        }
         return $result;
   	  }
   }

   function db_fetch_assoc($result){
   	  if ( $this->motorbd=="mysql"){
   	      $row=mysql_fetch_assoc($result);
   	      return $row;
   	  }else if ( $this->motorbd=="mssql"){
   	      $row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
   	      return $row;
   	  }
   }

   function db_num_rows($result){
   	  if ( $this->motorbd=="mysql"){
   	      $num=mysql_num_rows($result);
   	      return $num;
   	  }else if ( $this->motorbd=="mssql"){
   	      $num=sqlsrv_num_rows($result);
  	      return $num;
   	  }
   }

   function db_free_result($result){
   	  if ( $this->motorbd=="mysql"){
   	      mysql_free_result($result);
   	  }else if ( $this->motorbd=="mssql"){
   	      sqlsrv_free_stmt($result);
   	  }
   }

   function db_insert_id($tabla,$campo){   	global $database;
   	  if ($this->motorbd=="mysql"){   	      return mysql_insert_id($database->link);
   	  }else if ( $this->motorbd=="mssql"){
   	      $query="SELECT MAX($campo) AS LastID FROM $tabla";
   	      $result=$this->db_query($query);
   	      $row=$this->db_fetch_assoc($result);
   	      $lastId=$row['LastID'];
          return $lastId;
   	  }
   }


}


?>
