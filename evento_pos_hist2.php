<html>
<head>
   <style>
     div.pChartPicture { border: 0px; }
   </style>
</head>
<body>
   <script src="includes/imagemap.js" type="text/javascript"></script>
   <img src="draw.php" id="testPicture" alt="" class="pChartPicture"/>
</body>
<script>
   addImage("testPicture","pictureMap","draw.php?ImageMap=get");
</script>