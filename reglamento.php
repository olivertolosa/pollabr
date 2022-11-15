<?php /*ElGolGanador ofrece 3 formas de juego.
<br><br>
La primera de ellos es la <strong>Polla</strong> tradicional en donde el jugador va ganando puntos a medida que acierta los marcadores.
<br><br>
La segunda forma es la <strong>Bolsa de Acciones</strong>, en donde el jugador compra y vende acciones de los equipos.
<br><br>
La tercera es <strong>Apuestas Directas</strong> donde el jugador apuesta contra la casa en un único partido.
<br><br>
A continuación encontrarás el reglamento para cada uno de los modos de juego.
<br><br>
<a href="#polla">Reglamento Polla tradicional</a>
<br><br>
<a href="#bolsa">Reglamento Bolsa de acciones</a>
<br><br>
<a href="#album">Reglamento Álbum Virtual</a>
<br><br>
<a href="#directas">Reglamento Apuestas Directas</a> */ ?>


<a name="polla"></a>
<h1>Reglamento Polla tradicional</h1>

<ul>
<li>Cada usuario deberá usar la opción de autoregistro. El nombre de usuario será validado contra los ya existentes para que no se presente duplicidad.
<li>Para los eventos que exigen validación de usuario (como es el caso de eventos con pago incluido), El usuario no aparecerá en la tabla de posiciones hasta que <b>el administrador
   del evento</b> no registre/haga la validación del usuario.</li>
<li>Los marcadores pueden registrarse o modificarse hasta 15 minutos antes del comienzo del mismo.
    Una vez cerradas las apuestas, las apuestas registradas por todos los jugadores serán visibles para todos.</li>
<li>El marcador a tener en cuenta es el de los dos (2) tiempos reglamentarios (mas la adición correspondiente), es decir,
    no se tendrán en cuenta goles marcados en tiempos extras (cuando hay alargue a 120 minutos), ni definición por penales.</li>
<li>En caso de no registar marcador al cumplirse el límite se registrará automáticamente una marcador aleatorio; el máximo marcador aleatorio es definido por el administrador de cada evento</li>
<li>La distribución de los premios (si aplica) es definida por el administrador de cada evento según la tabla de posiciones. Los criterios de desempate son:
     1. Puntos obtenidos.
     2. Cantidad de marcadores exactos acertados.
     3. Cantidad de marcadres del equipo 1 acertados.
     En caso de presentarse empate en alguna de las posiciones se sorteará quien se queda con la ubicación mas alta a no ser que los jugadores empatados decidan acordar de otra manera.
    </li>
</ul>

<h2>Puntaje</h2>
<ul>
<li>Se podrá obtener hasta 15 puntos por cada partido.</li>
<li>Se obtienen 5 puntos si se acierta el ganador/empate (Gana Eq1, Gana Eq2 o empate)</li>
<li>Se obtienen 5 puntos por cada marcador por equipo obtenido, es decir, si la apuesta fue 2-0, y el
marcador final fue 2-1. Se obtienen 5 puntos por haber acertado el marcador del Eq1.</li>
<li>Al finalizar el torneo, se totalizarán los puntajes individuales para organizar los ganadores</li>
</ul>

<h2>Valor</h2>
<ul>
<li>La inscripción al sitio no tiene ningún costo.</li>
<li>El valor de la inscripción a los eventos depende del administrador de cada evento.</li>
</ul>


<?php if ($habilitar_bolsas){ ?>
<a name="bolsa"></a>
<h1>Reglamento Bolsa de acciones</h1>

Este modo de juego simula una bolsa de acciones en donde los usuarios pueden comprar y vender acciones de los equipos. El valor de las acciones está
determinado por los resultados de los partidos en los que participa el equipo, ganando valor si el equipo gana y perdiendo si empata o pierde.
<br><br>
<h2>Definiciones</h2>
<ul>
<li><strong>Mercado primario:</strong> Las acciones son vendidas únicamente por la casa a su precio de lista. Es necesario adquirir paquetes de acciones para
poder adquirir acciones individuales. Este tipo de mercado está habilitado sólo al inicio del juego</li>
<li><strong>Mercado secundario completo:</strong> Los usuarios pueden negociar (comprar/vender) acciones entre ellos y/o con la casa. La casa compra acciones
al 50% de su precio de lista y las vende al 150% del precio de lista. Estos dos valores son los topes en los que un usuario podrá vender acciones,
es decir, no se aceptan ofertas por debajo del 50% o por encima del 150% del precio de mercado de la acción.</li>
<li><strong>Mercado secundario parcial:</strong> La casa no emite acciones, solamente compra (al 50% del valor de lista). Los usuarios pueden seguir negociando
acciones entre ellos. Este mercado aplica para las etapas finales del juego.</li>
<li><strong>Precio de mercado:</strong> Es el precio base de la acción. Este precio se ve modificado por los resultados obtenidos en los partidos en los que participa
el equipo</li>
<li><strong>Portafolio:</strong> Corresponde al total de valores pertenecientes al usuario, los cuales están discriminados en "saldo en efectivo" y "Valor de acciones"</li>
<li><strong>Oferta:</strong> Expresa la intención de un usuario de comprar o vender un número determinado de acciones de un equipo. Estas ofertas pueden ser tomadas por
cualquiera de los demás participantes del juego siempre y cuando cuente con capital suficiente para realizar la transacción</li>
<li><strong>Valorización pendiente:</strong> Corresponde al valor perdido por las acciones de otros equipos el cual será repartido entre los equipos que hayan ganado
 sus partidos.</li>
</ul>

<h2>Crédito</h2>
<li>La asignación de crédito debe ser solicitada a uno de los administradores. Por el momento no contamos con pagos en linea</li>
<li>El monto mínimo para ingresar en el mercado primario corresponde al meno valor de uno de los paquetes de acciones</li>
<li>El usuario podrá solicitar nuevo crédito en cualquier momento y por cualquier monto.</li>

<h2>Realización de ofertas</h2>
<ul>
<li>Durante el mercado primario el usuario podrá comprar paquetes de acciones al precio de mercado, por cada paquete de acciones que adquiera tendrá
derecho a comprar 5 acciones individuales</li>
<li>El usuario solo puede poner en venta acciones de su propiedad</li>
<li>Las ofertas de venta no podrán ser superiores al 150% del precio de mercado.</li>
<li>Las ofertas de compra no podrán ser inferiores al 50% del precio de mercado.</li>
<li>No se permite tener ofertas de compra y venta del mismo equipo de manera simultánea</li>
<li>Las ofertas pueden realizarse en cualquier momento, excepto minetras el equipo esté dispuntando un partido. Las ofertas vigentes para un equipo serán
eliminadas automáticamente 15 minutos antes de iniciar un partido en el que ese equipo participe, es decir, solo se pueden tranzar acciones hasta 15 minutos antes
de iniciar el partido.</li>
<li>La casa no venderá acciones de equipos ganadores hasta tanto no se corra el ciclo de valorización. Los usuarios podrán seguir poniendo ofertas
de estos equipos. La casa seguirá comprando acciones de los equipos ganadores al 50% del valor actual de mercado.</li>
<li>El sistema solamente mostrará la oferta mas baja de compra y las mas alta de venta con el fin de garantizar que las mejores ofertas sean atendidas primero.</li>
<li>Las ofertas pueden ser removidas por el usuario que hizo la oferta en cualquier momento siempre y cuando la misma no haya sido aceptada por otro usuario</li>
</ul>

<h2>Valorización</h2>
<ul>
<li>Los equipos que pierdan su partido perderán un 30% de su precio de mercado</li>
<li>Los equipos que empaten su partido perderán un 15% de su precio de mercado</li>
<li>El valor perdido por los equipos que empaten o pierdan se sumará a la <strong>valorización pendiente</strong></li>
<li>Si hay acciones que se vendan a la casa, el valor correspondiente a la diferencia de precio entre el precio de mercado y el precio al que compra la casa (50%),
se sumará a la <strong>valorización pendiente</strong></li>
<li>Los equipos que ganen su partido recibirán valorización de la siguiente manera
   <ul>
      <li>El monto recaudado en la <strong>valorización pendiente</strong> se repartirá en partes iguales por cada equipo</li>
      <li>El monto asignado a cada equipo se repartirá en partes iguales entre las acciones del equipo.</li>
   </ul>
   </li>
<li>Si un equipo queda eliminado, su precio de mercado será 0 (cero)</li>
<li>La desvalorización de las acciones de un equipo que pierda se llevará a cabo apenas finalice el partido</li>
<li>La valorización de los equipos que ganen se llevará a cabo al finalizar el último partido de la fecha.</li>
<li>En caso de partidos aplazados, el/los partido(s) aplazados contarán como una fecha; es decir, al finalizar el/los partido(s) aplazado(s) se correrá valorización.</li>

</ul>

<h2>Retiros</h2>
<ul>
<li>El usuario podrá retirar el valor correspondiente a su saldo en efectivo.</li>
<li>El retiro podrá llevarse a cabo en cualquier momento</li>
<li>La casa podrá cobrar una cuota de manejo dependiendo de las condiciones de juego. Esto será comunicado por el organizador al momento de inicio del torneo.</li>
</ul>

<?php } ?>

<?php if ($habilitar_albums){ ?>
<a name="album"></a>
<h1>Reglamento Álbum Virtual</h1>

<h2>Entrega de sobres virtuales</h2>
<ul>
<li> 1 sobre diario por ingresar a www.elgolganador.com</li>
<li> 1 sobre por cada paquete de acciones comprado en el mercado primario de la bolsa</li>
<li> 1 sobre por cada transaccion realizada en la bolsa cuya contra parte no sea la casa.</li>
<li> 1 sobre por cada 5 puntos obtenidos en la polla</li>
<li> 1 sobre por cada marcador registrado en la polla</li>
<li> 3 sobres por cada amigo que refieras y que ingrese a jugar polla/bolsa.</li>
<li> x sobres para todos cuando el administrador esté contento</li>
</ul>

<h2>Intercambio de láminas</h2>
El usuario puede intercambiar láminas repetidas de 2 formas.
<ul>
<li>Poniendo sus repetidas en el "muro de las repetidas" y esperando que otro usuario realice el intercambio</li>
<li>Visitando el "muro de las repetidas" y seleccionando una de las láminas disponibles. El sistema listará cuales láminas puede intercambiar por la lámina seleccionada<li>
</ul>
Nota: El muro de las repetidas desplegará sólo una vez cada lámina, y tendrá prioridad para cambio el usuario que publique primero en el muro,
los demás quedarán en cola y tendrán su turno cuando las láminas publicadas con anterioridad sean intercambiadas.

<?php } ?>

<?php if ($habilitar_directas){ ?>
<a name="directas"></a>
<h1>Reglamento Apuestas directas</h1>
<ul>
  <li>Las apuestas disponibles están ubicadas en el panel izquierdo o bajo el menú Apostar->Apuestas Disponibles.</li>
  <li>Cualquier usuario podrá apostar por uno o mas partidos, el único requisito es tener saldo suficiente para realizar la apuesta</li>
  <li>El saldo será asignado por un administrador previa confirmación del pago y/o tranferencia</li>
  <li>El usuario podrá escoger si apuesta por el equipo 1 (local) por el equipo 2 (visitante) o por un empate</li>
  <li>El monto de la apuesta debe estar entre $100 y $1.000</li>
  <li>Un usuario solo podrá apostar por un resultado en un partido específico, es decir, no podrá apostar que gana el equipo1 y tener otra apuesta a que gana el equipo2</li>
  <li>Los número que aparecen debajo de cada equipo (y en la zona central para el emapte) corresponde al multiplicador del premio, es decir, por cuanto se multiplica el monto en caso de acertar el ganador/empate del partido</li>
  <li>Apenas se realiza una apuesta, el monto es debitado de la cuenta del usuario. Apenas se registre un acierto, el monto multiplicado por el multipolicador de premio será acreditado a la cuenta del usuario</li>

</ul>

<?php } ?>