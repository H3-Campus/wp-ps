
<html>

<body>
<?php

include 'ssh.php';
$host="192.168.150.233"; //docker

$dockerlxc="/root/WP/wordpress9.yml"; //Nom fichier compose
$sqlfile="/root/WP/sql.sql";

/* Création de la table correspondante dans la base de donnée */
echo "docker-compose -f /root/WP/db.yml exec -T db mysql -uroot -plinux wordpress < $sqlfile";
$Result=ssh_command($host,"docker-compose -f /root/WP/db.yml exec -T db mysql -uroot -plinux wordpress < $sqlfile");
print_r($Result);

?>
</body>
</html>
