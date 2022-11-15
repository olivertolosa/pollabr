<?php

include 'includes/Open-Connection.php';

$handle = fopen("paises.txt", "r");
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        $query="INSERT INTO paises VALUES ('','$line','1')";
		print "$query<br>";
		$stmt=$db->query($query);
    }

    fclose($handle);
} else {
    print "errror abriendo archivo";
}

include 'includes/Close-Connection.php';

?>