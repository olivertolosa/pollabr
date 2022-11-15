  <div id="w">
    <nav>
      <ul id="nav">
<? if ($administra_polla){?>
        <li><a href="#">Evento</a>
          <ul>
            <li><a href="index.php?accion=evento_admin&id_evento=<?= $id_evento ?>&accion2=parametros">Parámetros Generales</a></li>
            <li><a href="index.php?accion=evento_admin&id_evento=<?= $id_evento ?>&accion2=equipos">Equipos Participantes</a></li>
          </ul>
        </li>
        <li><a href="#">Partidos</a>
          <ul>
            <li><a href="index.php?accion=evento_admin&id_evento=<?= $id_evento ?>&accion2=listar_partidos">Listar Partidos</a></li>
            <li><a href="index.php?accion=evento_admin&id_evento=<?= $id_evento ?>&accion2=partido_nuevo">Crear Partido</a></li>
          </ul>
        </li>
        <li><a href="#">Usuarios</a>
          <ul>
            <li><a href="index.php?accion=evento_admin&id_evento=<?= $id_evento ?>&accion2=listar_usuarios">Listar Usuarios</a></li>
            <li><a href="index.php?accion=evento_admin&id_evento=<?= $id_evento ?>&accion2=usuario_nuevo">Crear Usuario</a></li>
          </ul>
        </li>
        <li><a href="#">Resultados</a>
          <ul>
            <li><a href="index.php?accion=evento_admin&id_evento=<?= $id_evento ?>&accion2=actualizar_resultados">Actualizar tabla de resultados</a></li>
          </ul>
        </li>
<?
}

if ($usuario_evento){
?>
      <li><a href="#">Apuestas</a>
         <ul>
             <li><a href="index.php?accion=evento_admin&id_evento=<?= $id_evento ?>&accion2=mi_apuesta">Mi apuesta</a></li>
             <li><a href="index.php?accion=evento_admin&id_evento=<?= $id_evento ?>&accion2=todas_apuestas">Ver todas las apuestas</a></li>
         </ul>
      </li>
      <li><a href="#">Resultados</a>
         <ul>
             <li><a href="index.php?accion=evento_admin&id_evento=<?= $id_evento ?>&accion2=mis_resultados">Resultados individuales</a></li>
             <li><a href="index.php?accion=evento_admin&id_evento=<?= $id_evento ?>&accion2=posiciones">Tabla de posiciones</a></li>
         </ul>
      </li>
      </ul>
<?
}
?>

    </nav>
  </div>