<?php

$port=8000;	//port de départ...			

//recherche du premier port disponible
$connection = @fsockopen('192.168.150.233', $port,$errno,$errstr,10);
echo "Erreur n°$errno : $errstr";
while (is_resource($connection)) { $port++; fclose($connection); $connection = @fsockopen('192.168.150.233', $port,$errno,$errstr,10); }
echo "<h2>Port disponible : $port </h2>";

?>