<html>

<head>
  <title></title>
</head>

<body>

<?php
require 'includes/Open-Connection.php';

$query="SELECT *
FROM  `usuarios`
WHERE  `email` LIKE CONVERT( _utf8 '%banrep%'
USING latin1 )
COLLATE latin1_swedish_ci
AND  `last_login` >  '0000-00-00 00:00:00'
AND recibir_correos='1'
ORDER BY email ASC";
   foreach($db->query($query) as $row) {
   	  $email=$row['email'];
   	  print "$email;<br>";
   }

require 'includes/Close-Connection.php';
?>

</body>

</html>