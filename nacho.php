<?php

$file_array=glob("imagenes/nacho/*.*");
$cant=sizeof($file_array);
$img=rand(0,$cant-1);

?>
<div style="text-align:center"><img src="<?= $file_array[$img] ?>" style="max-width:180px;max-height:180px"></div>

