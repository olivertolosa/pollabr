<?php
include 'includes/Open-Connection.php';

include("includes/pchart/class/pData.class.php");
include("includes/pchart/class/pDraw.class.php");
include("includes/pchart/class/pImage.class.php");

$id_usuario=$_GET['id_usuario'];
$id_evento=$_GET['id_evento'];



$query="SELECT id_evento,posicion FROM polla_posiciones_historia WHERE id_usuario='$id_usuario' 
        AND id_evento IN (SELECT id_evento FROM polla_eventos WHERE id_partido IN(SELECT id_partido FROM partidos WHERE id_evento='$id_evento'))
        ORDER BY id_evento ASC";
		
//print "q=$query<br>";		
$stmt = $db->query($query);
$i=1;
$cuantos_registros=$stmt->rowCount();

$salto_cada=($cuantos_registros/5)+1;
while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
    $array_y[]=$row['posicion'];
    if ($i%$salto_cada==1)
       $array_x[]=$i;
    else
       $array_x[]=$i;
    $i++;
}


//print_r($array_y);
//print "<br><br><br>";
//print_r($array_x);
include 'includes/Close-Connection.php';



/* Create and populate the pData object */
 $MyData = new pData();
 $MyData->addPoints($array_y,"Posicion");
 $MyData->setSerieTicks("Posicion",0);  //que tan puntuada es la linea
 $MyData->setSerieWeight("Posicion",1);  //que tan gruesa es la linea
 $MyData->setAxisName(0,"Posición");
 $MyData->addPoints($array_x,"Labels");
 $MyData->setSerieDescription("Labels","Fechas");
 $MyData->setAbscissa("Labels");
 $MyData->setAbscissaName("");
 
 function NegateValuesDisplay($Value) { if ( $Value == VOID ) { return(VOID); } else { return(-$Value); } }
 $MyData->negateValues("Posicion");
 $MyData->setAbsicssaPosition(AXIS_POSITION_TOP); 
 $MyData->setAxisDisplay(0,AXIS_FORMAT_CUSTOM,"NegateValuesDisplay"); 
 

   //cambiar el color de la linea
  $serieSettings = array("R"=>15,"G"=>15,"B"=>15);
  $MyData->setPalette("Posicion",$serieSettings);


 /* Create the pChart object */
 $myPicture = new pImage(600,350,$MyData);

 /* Turn of Antialiasing */
 $myPicture->Antialias = FALSE;

 $Settings = array("R"=>195, "G"=>192, "B"=>192, "Dash"=>1, "DashR"=>190, "DashG"=>203, "DashB"=>207);
 $myPicture->drawFilledRectangle(0,0,700,350,$Settings);
 $Settings = array("StartR"=>195, "StartG"=>192, "StartB"=>192, "EndR"=>210, "EndG"=>205, "EndB"=>205, "Alpha"=>40);
 $myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,$Settings);
//barra de título
 $myPicture->drawGradientArea(0,0,700,20,DIRECTION_VERTICAL,array("StartR"=>0,"StartG"=>0,"StartB"=>0,"EndR"=>50,"EndG"=>50,"EndB"=>50,"Alpha"=>80));

 /* Add a border to the picture */
 $myPicture->drawRectangle(0,0,599,349,array("R"=>0,"G"=>0,"B"=>0));

 /* Write the chart title */
 $myPicture->setFontProperties(array("FontName"=>"includes/pchart/fonts/Forgotte.ttf","FontSize"=>11));
// $myPicture->drawText(150,35,"$nombre_equipo",array("FontSize"=>28,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));

 /* Set the default font */
 $myPicture->setFontProperties(array("FontName"=>"includes/pchart/fonts/pf_arma_five.ttf","FontSize"=>7));

 /* Define the chart area */
 $myPicture->setGraphArea(60,40,570,300);

 /* Draw the scale */
 $AxisBoundaries = "";
 //$AxisBoundaries[0] = array("Min"=>0,"Max"=>75);
 $scaleSettings = array("XMargin"=>0,"YMargin"=>5,"Floating"=>TRUE,"GridR"=>200,"GridG"=>200,"GridB"=>200,"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE,"RemoveXAxis"=>TRUE);
 $myPicture->drawScale($scaleSettings);

 /* Turn on Antialiasing */
 $myPicture->Antialias = TRUE;

 /* adicionar logo del equipo */
// $myPicture->drawFromPNG(50,90,"img_sized.php?id_equipo=$id_equipo");

 /* Draw the line chart */
 $myPicture->drawLineChart();
 $myPicture->setFontProperties(array("FontName"=>"includes/pchart/fonts/pf_arma_five.ttf","FontSize"=>8));
 $myPicture->drawPlotChart(array("DisplayValues"=>FALSE,"PlotBorder"=>TRUE,"PlotSize"=>5,"BorderSize"=>190,"Surrounding"=>30,"BorderAlpha"=>00));

 /* Write the chart legend */
// $myPicture->drawLegend(340,20,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));

/* Write a label over the chart */
//$LabelSettings = array("TitleR"=>255,"TitleG"=>255,"TitleB"=>255, "DrawSerieColor"=>FALSE,"TitleMode"=>LABEL_TITLE_BACKGROUND, "OverrideTitle"=>"Information","ForceLabels"=>$array_y, "GradientEndR"=>220,"GradientEndG"=>255,"GradientEndB"=>220, "TitleBackgroundG"=>155);
//$myPicture->writeLabel(array("Posicion"),array(1,3),$LabelSettings);

 /* Render the picture (choose the best way) */
 $myPicture->autoOutput("pictures/example.drawLineChart.simple.png");
?>


