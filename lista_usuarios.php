<html>

<head>
  <title></title>
</head>

<body>

<?php

include 'includes/Open-Connection.php';

$query="SELECT usuario FROM usuarios";
$result = mysql_query($query) or die(mysql_error());
$cadena="";
$num=mysql_num_rows($result);
print "num=$num<br>";
while($row=mysql_fetch_assoc($result)){
   $usuario=$row['usuario'];
   $cadena.=$usuario.";";
}

print "$cadena";
include 'includes/Close-Connection.php';

?>

</body>

</html>