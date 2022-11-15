<?php
include 'includes/Open-Connection.php';

include("includes/pchart/class/pData.class.php");
include("includes/pchart/class/pDraw.class.php");
include("includes/pchart/class/pImage.class.php");

$id_usuario=$_GET['id_usuario'];


$query="SELECT saldo,fecha FROM saldos_historia
          WHERE id_usuario='$id_usuario'
          ORDER BY fecha ASC
          LIMIT 1,18446744073709551615";
//print "q=$query<br>";
$stmt = $db->query($query);
$i=1;
$cuantos_registros=$stmt->rowCount();
$salto_cada=($cuantos_registros/1)+1;
$saldo_anterior=0;
while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
//   if ($saldo_anterior!=$row['saldo']){   	   $saldo_anterior=$row['saldo'];
      $array_y[]=$row['saldo'];
      if ($i%$salto_cada==1)
        $array_x[]=$row['fecha'];
      else
        $array_x[]="";
      $i++;
  // }
}

/*print_r($array_y);
print "<br>";
print_r($array_x);*/
include 'includes/Close-Connection.php';
//exit();


/* Create and populate the pData object */
 $MyData = new pData();
 $MyData->addPoints($array_y,"Saldo");
/// $MyData->addPoints(array(3,12,15,8,5,-5),"Probe 2");
// $MyData->addPoints(array(2,7,5,18,19,22),"Probe 3");
 $MyData->setSerieTicks("Saldo",1);
 $MyData->setSerieWeight("Saldo",1);
 $MyData->setAxisName(0,"Saldo \$");
 $MyData->addPoints($array_x,"Labels");
 $MyData->setSerieDescription("Labels","Fechas");
 $MyData->setAbscissa("Labels");
 $MyData->setAbscissaName("Fechas");

  //cambiar el color de la linea
  $serieSettings = array("R"=>249,"G"=>168,"B"=>5);
  $MyData->setPalette("Saldo",$serieSettings);


 /* Create the pChart object */
 $myPicture = new pImage(400,230,$MyData);

 /* Turn of Antialiasing */
 $myPicture->Antialias = FALSE;


 $Settings = array("R"=>195, "G"=>192, "B"=>192, "Dash"=>1, "DashR"=>190, "DashG"=>203, "DashB"=>107);
 $myPicture->drawFilledRectangle(0,0,390,230,$Settings);
 $Settings = array("StartR"=>195, "StartG"=>192, "StartB"=>192, "EndR"=>210, "EndG"=>205, "EndB"=>205, "Alpha"=>40);
 $myPicture->drawGradientArea(0,0,390,230,DIRECTION_VERTICAL,$Settings);
//barra de título
 $myPicture->drawGradientArea(0,0,390,20,DIRECTION_VERTICAL,array("StartR"=>0,"StartG"=>0,"StartB"=>0,"EndR"=>50,"EndG"=>50,"EndB"=>50,"Alpha"=>80));


 /* Add a border to the picture */
 $myPicture->drawRectangle(0,0,390,229,array("R"=>0,"G"=>0,"B"=>0));

 /* Write the chart title */
 $myPicture->setFontProperties(array("FontName"=>"includes/pchart/fonts/Forgotte.ttf","FontSize"=>11));
// $myPicture->drawText(150,35,"$nombre_equipo",array("FontSize"=>28,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));

 /* Set the default font */
 $myPicture->setFontProperties(array("FontName"=>"includes/pchart/fonts/pf_arma_five.ttf","FontSize"=>7));

 /* Define the chart area */
 $myPicture->setGraphArea(60,40,360,200);

 /* Draw the scale */
 $scaleSettings = array("XMargin"=>10,"YMargin"=>10,"Floating"=>TRUE,"GridR"=>200,"GridG"=>200,"GridB"=>200,"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE);
 $myPicture->drawScale($scaleSettings);

 /* Turn on Antialiasing */
 $myPicture->Antialias = TRUE;

 /* adicionar logo del equipo */
 //$myPicture->drawFromPNG(50,90,"img_sized.php?id_equipo=$id_equipo");

 /* Draw the line chart */
 $myPicture->drawLineChart(array("DisplayColor"=>DISPLAY_MANUAL,"DisplayR"=>190,"DisplayG"=>190,"DisplayB"=>190,));
 $myPicture->setFontProperties(array("FontName"=>"includes/pchart/fonts/pf_arma_five.ttf","FontSize"=>8));
// $myPicture->drawPlotChart(array("DisplayValues"=>FALSE,"PlotBorder"=>TRUE,"BorderSize"=>1,"Surrounding"=>-160,"BorderAlpha"=>80));


 /* Write the chart legend */
// $myPicture->drawLegend(340,20,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));

 /* Render the picture (choose the best way) */
 $myPicture->autoOutput("pictures/example.drawLineChart.simple.png");
?>


?>
