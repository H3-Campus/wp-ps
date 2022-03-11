<?php include 'menu.php'; 
	  include 'ssh.php';
	  $host="192.168.170.233"; 	 /* <<<<<<<<<<<<<<<<<<< A MODIFIER */
	  error_reporting(E_ERROR | E_PARSE | E_STRICT);

//print_r($_POST)	;

//<!-- ********************  Fonctions  Bouttons ************************** --->
	if(array_key_exists('Reset', $_POST)) { FnReset($host); }
	//Paris
	if(array_key_exists('NameWP', $_POST)) { FnCreateWP($host); }
	if(array_key_exists('WP_X', $_POST)) { FnDeleteWP($host); }

/************************************************************************************************************** */
	
/**************************************** WORDPRESS function PHP PARIS **************************************** */		
function FnCreateWP($hostaddr) {
        $dockerName=$_POST['NameWP'];
        if ($dockerName==="cancel") {exit;}
            /* Variables */	
            $port=8000;	//port de départ...			
            $dockerlxc="/root/WP/$dockerName.yml"; //Nom fichier compose
            $sqlfile ="/root/WP/sql/$dockerName-sql";

            //recherche du premier port disponible
            $connection = @fsockopen($hostaddr, $port);
            while (is_resource($connection)) { $port++; fclose($connection);$connection = @fsockopen($hostaddr, $port,$errno,$errstr,10); }


            /* Création des fichiers compose et db */
            $Result=ssh_command($hostaddr,"cp /root/WP/wp.yml.save ".$dockerlxc);
            $Result=ssh_command($hostaddr,"sed -i 's/wordpress1/$dockerName/g' ".$dockerlxc);
            $Result=ssh_command($hostaddr,"sed -i 's/8001/$port/g' ".$dockerlxc);
            $Result=ssh_command($hostaddr,"echo 'CREATE DATABASE IF NOT EXISTS $dockerName;'> $sqlfile" );
            $Result=ssh_command($hostaddr,"echo 'GRANT ALL PRIVILEGES ON $dockerName.* TO 'wp_user'@'localhost';'>> $sqlfile");

            /* Création de la table correspondante dans la base de donnée */
            $Result=ssh_command($hostaddr,"docker-compose -f /root/WP/db.yml exec -T db mysql -uroot -plinux wordpress < $sqlfile >$sqlfile.log");
            sleep(10);
            //création du conteneur docker...				
            $Result=ssh_command($hostaddr, "export COMPOSE_HTTP_TIMEOUT=240 && docker-compose -f $dockerlxc up -d >$dockerlxc.log");
            
            // Vide l'instruction de créer une machine et puis refresh
            unset($_POST["NameWP"]); 
            header("Refresh:0");
}	

function FnDeleteWP($host) {		
    $dockerName=$_POST['WP_X'];
    if ($dockerName==="cancel") {exit;}
    $Result=ssh_command($host, "docker stop $dockerName");		
    $Result=ssh_command($host, "docker rm $dockerName");		
    header("Refresh:0");
}	

/*************************************************************************************************************** */	
?>