<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Serveurs BTS NDRC</title>

        <script src="https://code.jquery.com/jquery-latest.min.js"></script>
        <script>
        $(window).load(function() {
        $(".section").fadeOut("3000");
        $(".Code").load();
        </script>

	</head>
<body>

<!------------------------ Définitions des styles ------------------------->
<style>
table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}
</style>
<!-- ------------------------------------------------------------------- -->
<body>



<center>

<h1>Service Informatique H3 Campus </h1><br><br>

<?php include 'menu.php'; 
	  include 'ssh.php';
	  $host="192.168.170.233"; 	 /* <<<<<<<<<<<<<<<<<<< A MODIFIER */
	  error_reporting(E_ERROR | E_PARSE | E_STRICT);

//print_r($_POST)	;
?>

<?php
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



<div class="section">
    <div class="zone_loader">
        <span class="loader loader-quart"></span>
        <span class="loading">Chargement en cours...</span>
    </div>
  
</div>

<div class="Code">
<h2>Wordpress Poissy</h2>


<!-- *********************** Tableau avec les serveurs de base de données ! *******************  -->
<?php 
$Result=ssh_command($host, "docker ps | grep -i mysqldb | awk '{print \$NF}' ");
if ($Result<>NULL) 
{
	echo <<<TABLEAU
	<table  style='width:220px'>
		<tr><th>Serveurs de bases de données</th></tr>
			<tr><td align='center'><font color='green'>Bases de données Wordpress fonctionnelles </font></td>
		</tr>
	</table>
	TABLEAU;
}
else echo "<b><font color='red'>Attention les bases de données ne sont pas lancés correctement !</font></b><br/><br/>";
?>

<br/><br/>

<!-- Tableau avec les serveurs WORDPRESS -->

<table style="width:600px">
    <tr>
        <td colspan="2" align='center'><B>POISSY</B></td> 				
    </tr>
    <tr><th>Nom du Wordpress</th>
        <td  style="width:400px"> 
            <form name="formWP" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" id="formWP">
            <input type="submit" name="submit" value="Créer un Wordpress" onclick="createWP()" />
            <input type="hidden" name="NameWP" id="NameWP" />
            <input type="submit" name="submit" value="Supprimer un Wordpress" onclick="DelWP()" />
            <input type="hidden" name="WP_X" id="WP_X" />
            </form>
        </td>					
    </tr>
    <?php 
        /* Recherche de tous les Wordpress */
        $Result=ssh_command($host, "docker ps | grep -i wordpress:latest | awk '{print \$NF}' ");
        foreach( $Result as $value ){
            #Acquire port : docker ps -a | grep -i $value | awk '{print $11}' | cut -d - -f1| cut -d : -f4
            $portlist =ssh_command($host, "docker ps | grep -i wordpress:latest | awk '{print $11,\$NF}'|grep -i $value | cut -d - -f1| cut -d : -f4");
            $i=0;
            foreach($portlist as $portfinal) {
                $i++;
                if (!$portfinal==null and !$portfinal=="") {
                    echo "<tr >";
                    echo      "<td align='center'> $value </td><td><a href='http://$host:$portfinal' target='_blank'> Accéder à $value</a> </td>";							
                    echo "</tr>";					
                }
            }
        }
    ?>
</table>


</center>

<!-- Boutton reset --->
<?php
        function FnReset($host) {
			$Result=ssh_command($host, "docker stop $(docker ps -a -q)");
            $Result=ssh_command($host, "docker rm $(docker ps -a -q)");
			$Result=ssh_command($host, "docker network prune -f");
			$Result=ssh_command($host, "rm -f /root/WP/*.yml");
			$Result=ssh_command($host, "rm -f /root/WP/*.sql");
        }
	?>
<br><br><br><br><br>


<?php

/* BOUTONS */
echo <<< HTML
<center>	
   <script type="text/javascript">
   function createWP() {
   	var wp_name = prompt("Entrer un nom pour le Wordpress :", "wordpress");
	  	if (wp_name == null || wp_name == "") {wp_name = "cancel";} 
	  	document.getElementById("NameWP").value = wp_name;			  
	  }
	function DelWP() {
   	var wp_name = prompt("Entrer un nom pour le Wordpress a supprimé :", "");
	  	if (wp_name == null || wp_name == "") {wp_name = "cancel";} 
	  	document.getElementById("WP_X").value = wp_name;		
	  }
   </script>
 </center>
HTML;
?>
</div>
</body>
</html>

