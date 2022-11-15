<?
require_once 'includes/Open-Connection.php';
require_once 'audit.php';


//buscar forma de setear esto globalmente


if (isset($_POST['usuario'])) {

	if (strlen ($_POST['usuario'])>12 or strlen($_POST['password'])>12){
		print "Estas haciendo algo indebido??????";
		exit();
	}
        $usuario=htmlspecialchars($_POST['usuario']);
//        $usuario=strtolower($usuario);
        $clave=htmlspecialchars($_POST['password']);
//print "clave=*$clave*<br>clave MD5:".md5($clave)."<br>";

/*        if (!get_magic_quotes_gpc() and $clave!="") {
                $usuario = addslashes($_POST['usuario']);
                $clave = addslashes($_POST['clave']);

        }*/

        $query = "SELECT id_usuario,password FROM usuarios WHERE usuario=:usuario";
        $stmt= $db->prepare($query);
		$stmt->bindParam(':usuario',$usuario);
		$stmt->execute();
//print "q=$query<br>";

        session_start();
//print "validando";
        if ( $stmt->rowCount() == 0) {
                unset($_SESSION['usuario_polla']);
                $_SESSION['msg'] = '<span class="msg_error">Usuario o Contraseña inválida.</span>';
                $redirect="index.php?accion=logon";
        } else {
                $row=$stmt->fetch(PDO::FETCH_ASSOC);
                $clave2=$row['password'];
//print "clave=*$clave*<br>clave2=*$clave2*<br>";
                //validar si la la clave está en blanco en la bd (usuario recien creado o clave reseteada) y obligarlo a
                //setear clave
                if ($clave==""){//                	print "clave1 vacia<br>";
                    if ($clave2==""){//                    	print "clave2 vacia<br>";
                       $id_usuario=$row['id_usuario'];
                       $_SESSION['usuario_polla'] = "$id_usuario";
                       $_SESSION['cambia_clave']= true;
                       $redirect="index.php?accion=micuenta";
                       $_SESSION['msg']='<span class="msg_warn">Para completar el proceso de ingreso se requiere cambio de clave</span>';

                       //entregar sobres si aplica
                        $_SESSION['id_album'] = "1";
                        $id_album=1;
                        if ($id_album!=0){
                           $query="SELECT last_login FROM usuarios WHERE id_usuario=:id_usuario";
                        	$stmt= $db->prepare($query);
							$stmt->bindParam(':id_usuario',$id_usuario);
							$stmt->execute();

                           $row=$stmt->fetch(PDO::FETCH_ASSOC);
                           $last_login=$row['last_login'];

                           $last_login=substr($last_login,0,10);
                           $fecha_dia=date('Y-m-d');

                           //print "last_login=$last_login<br>fecha:$fecha_dia";

                           if ($fecha_dia>$last_login and $habilitar_albums){
                              include 'function_entrega_sobres.php';
                              entregar_sobres($id_usuario,$id_album,1,"logon");
                           }
                        }
                    }
                }else{
                    $clave=md5($clave);
                }

                if ($clave2 != $clave) {
                        unset($_SESSION['usuario_polla']);
                        $_SESSION['msg'] = '<span class="msg_error">Usuario o Contraseña inválida.2</span>';
                        $redirect="index.php?accion=logon";
                } else if ($clave!=""){
                        $id_usuario=$row['id_usuario'];
                        //validar si es admin
                        $query_admin="SELECT * FROM administradores WHERE id_usuario=:id_usuario";
                        $stmt_admin= $db->prepare($query_admin,array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
						$stmt_admin->bindParam(':id_usuario',$id_usuario);
						$stmt_admin->execute();

                        if ( $stmt_admin->rowCount() > 0) {
                           $_SESSION['admin'] = "1";
                        }

                        //Validar si tiene pollas administradas
                        $query="SELECT * FROM eventos WHERE admin=:id_usuario";

                        $stmt2= $db->prepare($query,array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
						$stmt2->bindParam(':id_usuario',$id_usuario);
						$stmt2->execute();

                        if ($stmt2->rowCount()>0)
                            $_SESSION['administra_polla']=true;

//print "usuario=$id_usuario";
                        $_SESSION['usuario_polla'] = "$id_usuario";
                        $_SESSION['msg'] = '';
                        $redirect="index.php";
                        if ($_SESSION['url']!="")
                           $redirect.="?".$_SESSION['url'];
                        require_once 'audit.php';
                        $ip=$_SERVER['REMOTE_ADDR'];
                        audit($_SESSION['usuario_polla'],"login","ip: $ip");


                        date_default_timezone_set ('America/Bogota');
                        $fecha=date('Y-m-d H:i');


                        //entregar sobres si aplica
                        $_SESSION['id_album'] = "1";
                        $id_album=1;
                        if ($id_album!=0 and $_SESSION['cuantas_monas_tengo']<$cuantas_monas_totales){
                           $query="SELECT last_login FROM usuarios WHERE id_usuario=:id_usuario";
                           $stmt= $db->prepare($query,array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
						   $stmt->bindParam(':id_usuario',$id_usuario);
						   $stmt->execute();
                           $row=$stmt->fetch(PDO::FETCH_ASSOC);
                           $last_login=$row['last_login'];

                           $last_login=substr($last_login,0,10);
                           $fecha_dia=date('Y-m-d');

                           //print "last_login=$last_login<br>fecha:$fecha_dia";

                           if ($fecha_dia>$last_login){
                              include 'function_entrega_sobres.php';

                              entregar_sobres($id_usuario,$id_album,1,"logon");
                           }
                        }

                        //actualizar último login

                        $query="UPDATE usuarios SET last_login=:fecha WHERE id_usuario=:id_usuario";
                        $stmt= $db->prepare($query,array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
						$stmt->bindParam(':fecha',$fecha);
						$stmt->bindParam(':id_usuario',$id_usuario);
						$stmt->execute();


                }
        }
//print "msg=$msg<br>";


//        if ($redirect=="") $redirect="index.php";

/*        print "destino=$redirect";
        print_r($_SESSION);
        exit();*/


//        require_once 'includes/Close-Connection.php';
        if (!headers_sent() && $msg == '') {
             header('Location: '.$redirect);
        }
        else{           $_SESSION['msg']="<span class=\"msg_error\">No pude redirigir</span>";
//          header('Location: '.$redirect);
        }

}

audit_max();
?>


