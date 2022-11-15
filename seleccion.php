<style>
a.tp {outline:none;}
a.tp strong {line-height:30px;}
a.tp:hover {text-decoration:none;}
a.tp span {
    z-index:10;
    display:none;
    padding:14px 20px;
    margin-top:-30px;
    margin-left:8px;
    width:210px;
    line-height:16px;
}
a.tp:hover span{
    display:inline;
    position:absolute;
    color:#111;
    border:1px solid #DCA;
    background:#fffAF0;
}
.callout {z-index:20;position:absolute;top:30px;border:0;left:-12px;}

/*CSS3 extras*/
a.tooltip span
{
    border-radius:4px;
    -moz-border-radius: 4px;
    -webkit-border-radius: 4px;

    -moz-box-shadow: 5px 5px 8px #CCC;
    -webkit-box-shadow: 5px 5px 8px #CCC;
    box-shadow: 5px 5px 8px #CCC;
}
</style>
<div style="text-align:center; width:100%;">

<?

$jugadores[0]="David Ospina";
$jugadores[1]="Faryd Mondragon";
$jugadores[2]="Camilo Vargas";
$jugadores[3]="Camilo Zúñiga";
$jugadores[4]="Santiago Arias";
$jugadores[5]="Mario Alberto Yepes";
$jugadores[6]="Cristian Zapata";
$jugadores[7]="Pablo Armero";
$jugadores[8]="Eder Álvarez Balanta";
$jugadores[9]="Carlos Valdés";
$jugadores[10]="Alex Mejía";
$jugadores[11]="Freddy Guarín";
$jugadores[12]="Abel Aguilar";
$jugadores[13]="Carlos Carbonero";
$jugadores[14]="Carlos Sanchez";
$jugadores[15]="Juan Fernando Quintero";
$jugadores[16]="Juan Guillermo Cuadrado";
$jugadores[17]="James Rodríguez";
$jugadores[18]="Carlos Bacca";
$jugadores[19]="Teófilo Gutiérrez";
$jugadores[20]="Jackson Martínez";
$jugadores[21]="Víctor Ibarbo";
$jugadores[22]="Adrián Ramos";


$posicion[0]="Portero";
$posicion[1]="Portero";
$posicion[2]="Portero";
$posicion[3]="Defensa Lateral";
$posicion[4]="Defensa Lateral";
$posicion[5]="Defensa Central";
$posicion[6]="Defensa Central";
$posicion[7]="Defensa Lateral";
$posicion[8]="Defensa Central";
$posicion[9]="Defensa Central";
$posicion[10]="Mediocampista";
$posicion[11]="Mediocampista";
$posicion[12]="Mediocampista";
$posicion[13]="Mediocampista";
$posicion[14]="Mediocampista";
$posicion[15]="Mediocampista";
$posicion[16]="Mediocampista";
$posicion[17]="Mediocampista";
$posicion[18]="Delantero";
$posicion[19]="Delantero";
$posicion[20]="Delantero";
$posicion[21]="Delantero";
$posicion[22]="Delantero";


$ciudad[0]="Medellín";
$ciudad[1]="Cali";
$ciudad[2]="Bogotá";
$ciudad[3]="Chigorodó";
$ciudad[4]="Medellín";
$ciudad[5]="Cali";
$ciudad[6]="Padilla";
$ciudad[7]="Tumaco";
$ciudad[8]="Bogotá";
$ciudad[9]="Cali";
$ciudad[10]="Barranquilla";
$ciudad[11]="Puerto Boyacá";
$ciudad[12]="Bogotá";
$ciudad[13]="Bogotá";
$ciudad[14]="Quibdó";
$ciudad[15]="Medellín";
$ciudad[16]="Necoclí";
$ciudad[17]="Cúcuta";
$ciudad[18]="Puerto Colombia";
$ciudad[19]="Barranquilla";
$ciudad[20]="Quibdó";
$ciudad[21]="Tumaco";
$ciudad[22]="Santander de Quilichao";




$estatura[0]="1.83";
$estatura[1]="1.91";
$estatura[2]="1.88";
$estatura[3]="1.72";
$estatura[4]="1.76";
$estatura[5]="1.86";
$estatura[6]="1.87";
$estatura[7]="1.79";
$estatura[8]="1.81";
$estatura[9]="1.84";
$estatura[10]="1.83";
$estatura[11]="1.83";
$estatura[12]="1.85";
$estatura[13]="1.74";
$estatura[14]="1.82";
$estatura[15]="1.68";
$estatura[16]="1.76";
$estatura[17]="1.80";
$estatura[18]="1.80";
$estatura[19]="1.85";
$estatura[20]="1.88";
$estatura[21]="1.85";
$estatura[22]="1.85";

$peso[0]=79;
$peso[1]=97;
$peso[2]=80;
$peso[3]=72;
$peso[4]=68;
$peso[5]=83;
$peso[6]=82;
$peso[7]=75;
$peso[8]=78;
$peso[9]=80;
$peso[10]=79;
$peso[11]=77;
$peso[12]=80;
$peso[13]=82;
$peso[14]=82;
$peso[15]=62;
$peso[16]=77;
$peso[17]=77;
$peso[18]=83;
$peso[19]=76;
$peso[20]=74;
$peso[21]=74;
$peso[22]=74;




$edad[0]=25;
$edad[1]=42;
$edad[2]=25;
$edad[3]=28;
$edad[4]=22;
$edad[5]=38;
$edad[6]=27;
$edad[7]=27;
$edad[8]=21;
$edad[9]=29;
$edad[10]=25;
$edad[11]=27;
$edad[12]=29;
$edad[13]=23;
$edad[14]=28;
$edad[15]=21;
$edad[16]=26;
$edad[17]=22;
$edad[18]=27;
$edad[19]=29;
$edad[20]=27;
$edad[21]=24;
$edad[22]=28;


$equipo[0]=445;
$equipo[1]=39;
$equipo[2]=38;
$equipo[3]=408;
$equipo[4]=574;
$equipo[5]=396;
$equipo[6]=407;
$equipo[7]=395;
$equipo[8]=224;
$equipo[9]=226;
$equipo[10]=25;
$equipo[11]=403;
$equipo[12]=451;
$equipo[13]=224;
$equipo[14]=540;
$equipo[15]=454;
$equipo[16]=401;
$equipo[17]=560;
$equipo[18]=207;
$equipo[19]=224;
$equipo[20]=454;
$equipo[21]=398;
$equipo[22]=419;

($mobile)? $cuantos_por_linea=3 : $cuantos_por_linea=6;

require 'includes/Open-Connection.php';
require_once 'includes/class_equipo.php';
$eqobj=new equipo($db);

$query="SELECT * FROM dual";
$stmt = $db->query($query);

?>

<?

for ($i=0 ;  $i<=22 ; $i++){
   if ($i%$cuantos_por_linea==0) print "<br><br>";

?>

<a href="#" class="tp">
    <img src="imagenes/seleccion/<?= $i ?>.jpg" style="max-width:80px;max-height:60px" title="<?= $jugadores[$i] ?>">
    <span>
      <table>
      <tr>
        <td colspan="2" style="text-align:center;"><img class="callout" src="imagenes/callout.gif" />
        <strong><?= $jugadores[$i] ?></strong><br />
        <img src="imagenes/seleccion/<?= $i ?>.jpg" style="max-width:180px;max-height:160px" />
      <tr>
      <tr> <td>Edad:<td><?= $edad[$i] ?> Años
      <tr>  <td>Peso:<td> <?= $peso[$i] ?> Kg <br>
      <tr>  <td>Estatura:<td> <?= $estatura[$i] ?> mts<br>
      <tr>  <td>Ciudad de<br>Nacimiento:<td> <?= $ciudad[$i] ?><br>
      <tr>  <td>Posición:<td> <?= $posicion[$i] ?><br>
      <tr>  <td>Equipo:<td> <img src="imagenes/logos_equipos/<?= $equipo[$i] ?>.png" style="max-width:95px;max-height:95px;vertical-align: middle"><br><? print  $eqobj->get_nombre($equipo[$i]) ?>


    </span>
    </table>
</a>
<?
}
require 'includes/Close-Connection.php';

?>
</div>