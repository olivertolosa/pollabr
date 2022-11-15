<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title></title>
    <style>
        .SimpleChart {
            position: relative;
        }

            .SimpleChart #tip {
                background-color: #f0f0f0;
                border: 1px solid #d0d0d0;
                position: absolute;
                left: -200px;
                top: 30px;
            }

        .down-triangle {
            width: 0;
            height: 0;
            border-top: 10px solid #d0d0d0;
            border-left: 6px solid transparent;
            border-right: 6px solid transparent;
            position: absolute;
            left: -200px;
        }


        .SimpleChart #highlighter {
            position: absolute;
            left: -200px;
        }

        .-simple-chart-holder {
            float: left;
            position: relative;
            width: 100%;
            background-color: #fff;
            border: 1px solid #cecece;
            /*padding: 6px;*/
        }


        .SimpleChart .legendsli {
            list-style: none;
        }

            .SimpleChart .legendsli span {
                float: left;
                vertical-align: middle;
            }

                .SimpleChart .legendsli span.legendindicator {
                    position: relative;
                    top: 5px;
                }

                    .SimpleChart .legendsli span.legendindicator .line {
                        width: 30px;
                        height: 3px;
                    }

                    .SimpleChart .legendsli span.legendindicator .circle {
                        width: 12px;
                        height: 12px;
                        border-radius: 20px;
                        position: relative;
                        top: -5px;
                        right: 20px;
                    }


        /******Starts::Horizontal Alignment of Legends******/

        .simple-chart-legends {
            background: #E7E7E7;
            border: 1px solid #d6d7dd;
            padding: 5px;
            margin: 2px 0px;
        }

            .simple-chart-legends ul {
            }

                .simple-chart-legends ul li {
                    display: inline;
                    border-right: 1px solid #d6d7dd;
                    float: left;
                    padding: 10px;
                }

                    .simple-chart-legends ul li:last-child {
                        border-right: 0px;
                    }


            .simple-chart-legends.vertical {
                margin: 0px 10px;
            }

                .simple-chart-legends.vertical ul li {
                    display: block;
                    border: 0px;
                    border-bottom: 1px solid #d6d7dd;
                }

                    .simple-chart-legends.vertical ul li:last-child {
                        border-bottom: 0px;
                    }

            .simple-chart-legends .legendvalue {
                padding-left: 2px;
                background: #fff;
            }

        /******Starts::Horizontal Alignment of Legends******/
        .simple-chart-Header {
            position: absolute;
            font-size: 16px;
        }
    </style>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js" type="text/javascript"></script>
    <script src="includes/SimpleChart.js"></script>

<?php
include 'includes/Open-Connection.php';

$id_usuario=$_GET['id_usuario'];
$id_evento=$_GET['id_evento'];

$query="SELECT posicion FROM polla_posiciones_historia WHERE id_usuario='$id_usuario' 
        AND id_evento IN (SELECT id_evento FROM polla_eventos WHERE id_partido IN(SELECT id_partido FROM partidos WHERE id_evento='$id_evento'))
        ORDER BY id_evento ASC";
		
//print "q=$query<br>";		
$stmt = $db->query($query);
$i=1;
?>
<script>
var graphdata1 = {
    linecolor: "#6E6E6E",
    title: "Posicion",
	values: [
<?
while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
	$posicion=$row['posicion'];
    $values.="{X: \"\",Y:$posicion},";
    $i++;	
}
$values=substr($values,0,strlen($values)-1);
print $values;
?>
            ]
        };
$(function () {
            $("#Linegraph").SimpleChart({
                ChartType: "Line",
                toolwidth: "50",
                toolheight: "25",
				headerfontsize: "24px",
				toolwidth: 30,  //tamaño de la caja al hacer hover
                toolheight: 20,
                axiscolor: "#000000",
                textcolor: "#6E6E6E",
                showlegends: false,  //mostrar caja con la tabla de convenciones
                data: [graphdata1],
                legendsize: "140",
                legendposition: 'bottom',
                xaxislabel: null,
                title: 'Posición Histórica',
                yaxislabel: 'Posición',
            });

        });

    </script>
</head>
<body>
    <h2>Line</h2>
    <div id="Linegraph" style="width: 98%; height: 500px">
    </div>
</body>
</html>






