<?
function validar_inscripcion ($id_usuario,$id_evento){//	    retorna:
//	    1 si el usuario está inscrito y validado o inscrito y no necesita validación
//      0 si el usuario no está inscrito
//      2 si el usuario está inscrito pero no ha sido validado

    global $db;

         //validar si el usuario está inscrito en el evento
         $query="SELECT validado FROM usuariosxevento WHERE id_usuario='$id_usuario' AND id_evento='$id_evento'";
         $stmt = $db->query($query);
         $num=$stmt->rowCount();

         if ($num==0)
            return 0;
         else{ //ya está participando...validar si es necesario que se valide o no
            $row_p=$stmt->fetch(PDO::FETCH_ASSOC);
            $usuario_validado=$row_p['validado'];
            if ($usuario_validado)
               return 1;
            else{
               ///validar si el evento requiere validación de usuarios
               $query_v="SELECT conf_usuarios FROM eventos WHERE id_evento='$id_evento'";
               $stmt_v = $db->query($query_v);
               $row_v=$stmt_v->fetch(PDO::FETCH_ASSOC);
               $conf_usuarios=$row_v['conf_usuarios'];
               if (!$conf_usuarios)
                  return 1;
               else if ($validado)
                  return 1;
               else
                  return 2;
            }
         }
}
?>