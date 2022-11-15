<?php

session_start();
//print_r($_SESSION);

require_once 'includes/Open-Connection.php';
//require_once 'audit.php';
//audit_max();

//validar si hay que setear la variable de sesion debu
if (isset($_GET['debug'])){
    $debug=$_REQUEST['debug'];
    if ($debug=="on"){
       $_SESSION['debug']=true;
    }else if ($debug=="off"){
       unset($_SESSION['debug']);
    }
}

if (isset($_SESSION['cambia_clave'])) {	    $_SESSION['msg']="Se requiere cambio de clave";
	    //print "cambiar clave!!";
        if (!headers_sent()) {
                header('Location: index.php?accion=micuenta');
        }

}else if (!isset($_SESSION['usuario_polla']) and $accion!="registro") {
        echo '<script type="text/javascript">
        alert ("Acceso no Autorizado");
           window.location = "index.php?accion=logon"
      </script>';
      die();
}

?>