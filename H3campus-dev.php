<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Serveurs BTS NDRC</title>
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
	  $host="192.168.150.233"; 
	  

//print_r($_POST)	;

//<!-- ********************  Fonctions  Bouttons ************************** --->
	if(array_key_exists('Reset', $_POST)) { FnReset($host); }
	if(array_key_exists('NameWP', $_POST)) { FnCreateWP($host); }
	if(array_key_exists('WP_X', $_POST)) { FnDeleteWP($host); }
	if(array_key_exists('NamePresta', $_POST)) { FnCreatePresta($host); }
	if(array_key_exists('Presta_X', $_POST)) { FnDeletePresta($host); }
	
	/**************************************** WORDPRESS function PHP **************************************** */		
	function FnCreateWP($hostaddr) {
			if ($_POST['NameWP']==="cancel") {exit;}
				/* Variables */	
				$port=8000;	//port de départ...			
				$dockerlxc="/root/WP/".$_POST["NameWP"].".yml"; //Nom fichier compose
				$sqlfile ="/root/WP/sql.sql";

				//recherche du premier port disponible
				$connection = @fsockopen($hostaddr, $port);
				while (is_resource($connection)) { $port++; fclose($connection);$connection = @fsockopen('192.168.150.233', $port,$errno,$errstr,10); }


				/* Création des fichiers compose et db */
				$Result=ssh_command($hostaddr,"cp /root/WP/wp.yml.save ".$dockerlxc);
				$Result=ssh_command($hostaddr,"sed -i 's/wordpress1/".$_POST["NameWP"]."/g' ".$dockerlxc);
				$Result=ssh_command($hostaddr,"sed -i 's/8001/$port/g' ".$dockerlxc);
				$Result=ssh_command($hostaddr,"echo 'CREATE DATABASE IF NOT EXISTS ".$_POST["NameWP"].";'> $sqlfile" );
				$Result=ssh_command($hostaddr,"echo 'GRANT ALL PRIVILEGES ON ".$_POST["NameWP"].".* TO 'wp_user'@'localhost';'>> $sqlfile");

				/* Création de la table correspondante dans la base de donnée */
				$Result=ssh_command($hostaddr,"docker-compose -f /root/WP/db.yml exec -T db mysql -uroot -plinux wordpress < $sqlfile");
				sleep(15);
				//création du conteneur docker...				
				$Result=ssh_command($hostaddr, "export COMPOSE_HTTP_TIMEOUT=120 && docker-compose -f $dockerlxc up -d &");
				sleep(20);

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
	/**************************************** PRESTASHOP function PHP **************************************** */		
	function FnCreatePresta($hostaddr) {
		$dockerName=$_POST['NamePresta']; 
		if ($dockerName==="cancel") {exit;}
			/* Variables */	
			$port=7000;	//port de départ...			
			$dockerlxc="/root/PS/$dockerName.yml"; //Nom fichier compose
			$sqlfile ="/root/PS/sql.sql";

			//recherche du premier port disponible
			$connection = @fsockopen($hostaddr, $port);
			while (is_resource($connection)) { $port++; fclose($connection);$connection = @fsockopen($hostaddr, $port,$errno,$errstr,10); }


			/* Création des fichiers compose et db */
			$Result=ssh_command($hostaddr,"cp /root/PS/ps.yml.save ".$dockerlxc);
			$Result=ssh_command($hostaddr,"sed -i 's/prestashop1/$dockerName/g' ".$dockerlxc);
			$Result=ssh_command($hostaddr,"sed -i 's/8001/$port/g' ".$dockerlxc);

			//création du conteneur docker...		
			$Result=ssh_command($hostaddr, "export DOCKER_CLIENT_TIMEOUT=120 && export DOCKER_HTTP_TIMEOUT=120");		
			$Result=ssh_command($hostaddr, "export COMPOSE_HTTP_TIMEOUT=120 && docker-compose -f $dockerlxc up -d &");
			sleep(20);			

			// Vide l'instruction de créer une machine et puis refresh
			unset($_POST["NamePresta"]); 
			header("Refresh:0");
		}	

	function FnDeletePresta($host) {		
		$dockerName=$_POST['Presta_X'];
		$dockerlxc="/root/PS/$dockerName.yml"; //Nom fichier compose
		if ($dockerName==="cancel") {exit;}		
		$Result=ssh_command($host, "docker stop $dockerName");		
		$Result=ssh_command($host, "docker rm $dockerName");	
		header("Refresh:0");	
		}
		?>
<!--- ********************************************************************---->

<br><br>
<!-- **************   Bouton d'actualisation ************ -->
<table>
	<tr>
		<td>
			<FORM>
				<?php 
					/* Création de la base de donnée (& phppmyadmin) si elle n'existe pas !! */
					$Result=ssh_command($host, "docker ps -a | grep mysqldb");
					if ($Result== null) {
						//$Result=ssh_command($host,"docker network create wp_wpsite");
						$Result=ssh_command($host,"cp /root/WP/db.yml.save /root/WP/db.yml");
						$Result=ssh_command($host, "export DOCKER_CLIENT_TIMEOUT=120 && export DOCKER_HTTP_TIMEOUT=120 && docker-compose -f /root/WP/db.yml up -d");		
						}
					$Result=ssh_command($host, "docker ps -a | grep mysqlps");
					if ($Result== null) {
						$Result=ssh_command($host,"cp /root/PS/db.yml.save /root/PS/db.yml");
						$Result=ssh_command($host, "export DOCKER_CLIENT_TIMEOUT=120 && export DOCKER_HTTP_TIMEOUT=120 && docker-compose -f /root/PS/db.yml up -d");		
						}
				?>
				<INPUT Type="button" VALUE="Actualiser la liste" onClick="history.go(0)">
				<form name="form" method="post" action="H3campus.php">
			</FORM>	
		</td>
	<tr><br></tr>
</table>

<h2>Wordpress - Prestashop</h2>


<!-- *********************** Tableau avec les serveurs de base de données ! *******************  -->
<?php 
$Result=ssh_command($host, "docker ps -a | grep -i mysqldb | awk '{print \$NF}' ");
$Result2=ssh_command($host, "docker ps -a | grep -i mysqlps | awk '{print \$NF}' ");
if (($Result<>NULL) and ($Result2<>NULL))
{
	echo <<<TABLEAU
	<table  style='width:220px'>
		<tr><th>Serveurs de bases de données</th></tr>
			<tr><td align='center'><font color='green'>Bases de données Wordpress & Prestashop fonctionnelles </font></td>
		</tr>
	</table>
	TABLEAU;
}
else echo "<b><font color='red'>Attention les bases de données ne sont pas lancés correctement !</font></b><br/><br/>";
?>


<!-- Tableau avec les serveurs WORDPRESS -->
<table  style="width:600px">
	<tr><th>Serveurs Wordpress</th>
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
		$Result=ssh_command($host, "docker ps -a | grep -i wordpress | awk '{print \$NF}' ");
		foreach( $Result as $value ){
			#Acquire port : docker ps -a | grep -i $value | awk '{print $11}' | cut -d - -f1| cut -d : -f4
			$portlist =ssh_command($host, "docker ps -a | awk '{print $11,\$NF}'|grep -i $value | cut -d - -f1| cut -d : -f4");
			$i=0;
			foreach($portlist as $portfinal) {
				$i++;
  				if (!$portfinal==null and !$portfinal=="") {
					echo "<tr><td align='center'>".$value ."</td><td><a href='http://192.168.150.233:".$portfinal."'> Accéder à $value</a> </td></tr><br/>";
				}
			}
		}
	?>
</table>

<!-- Tableau avec les serveurs PRESTASHOP -->
<table  style="width:600px">
	<tr><th>Serveurs Prestashop</th>
		<td  style="width:400px"> 
			<form name="formPresta" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" id="formPresta">
      		<input type="submit" name="submit" value="Créer un Prestashop" onclick="CreatePresta()" />
      		<input type="hidden" name="NamePresta" id="NamePresta" />
			<input type="submit" name="submit" value="Supprimer un Prestashop" onclick="DeletePresta()" />
     		<input type="hidden" name="Presta_X" id="Presta_X" />
			</form>
   		</td>		
	</tr>
	<?php 
		/* Recherche de tous les Prestashop */
		$Result=ssh_command($host, "docker ps -a | grep -i prestashop | awk '{print \$NF}' ");
		foreach( $Result as $value ){
			#Acquire port : docker ps -a | grep -i $value | awk '{print $11}' | cut -d - -f1| cut -d : -f4
			$portlist =ssh_command($host, "docker ps -a | awk '{print $11,\$NF}'|grep -i $value | cut -d - -f1| cut -d : -f4");
			$i=0;
			foreach($portlist as $portfinal) {
				$i++;
  				if (!$portfinal==null and !$portfinal=="") {
					echo "<tr><td align='center'>".$value ."</td><td><a href='http://192.168.150.233:".$portfinal."'> Accéder à $value</a> </td></tr><br/>";
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
<p style='text-align: right'>
		<form method="post">
				<input type="submit" name="Reset" class="button" value="Reset" />
		</form>
</p>

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
	function CreatePresta() {
   	var Presta_name = prompt("Entrer un nom pour le Prestashop :", "Prestashop");
	  	if (Presta_name == null || Presta_name == "") {Presta_name = "cancel";} 
	  	document.getElementById("NamePresta").value = Presta_name;			  
	  }
	function DeletePresta() {
   	var Presta_name = prompt("Entrer un nom pour le Prestashop a supprimé :", "");
	  	if (Presta_name == null || Presta_name == "") {Presta_name = "cancel";} 
	  	document.getElementById("Presta_X").value = Presta_name;		
	  }
   </script>
 </center>
HTML;
?>
</body>
</html>

