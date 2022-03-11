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
<h1>Service Informatique H3 Campus </h1>

<?php include 'menu.php'; 
	  include 'ssh.php';
	  $host="192.168.150.233"; 
	  error_reporting(E_ERROR | E_PARSE | E_STRICT);

//print_r($_POST)	;

//<!-- ********************  Fonctions  Bouttons ************************** --->
	if(array_key_exists('Reset', $_POST)) { FnReset($host); }
	if(array_key_exists('NamePresta', $_POST)) { FnCreatePresta($host); }
	if(array_key_exists('Presta_X', $_POST)) { FnDeletePresta($host); }

/**************************************** PRESTASHOP function PHP **************************************** */		
function FnCreatePresta($hostaddr) {
    $dockerName=$_POST['NamePresta']; 
    if ($dockerName==="cancel") {exit;}
        /* Variables */	
        $port=7000;	//port de départ...			
        $dockerlxc="/root/PS/$dockerName.yml"; //Nom fichier compose
        

        //recherche du premier port disponible
        $connection = @fsockopen($hostaddr, $port);
        while (is_resource($connection)) { $port++; fclose($connection);$connection = @fsockopen($hostaddr, $port,$errno,$errstr,10); }


        /* Création des fichiers compose et db */
        $Result=ssh_command($hostaddr,"cp /root/PS/ps.yml.save ".$dockerlxc);
        $Result=ssh_command($hostaddr,"sed -i 's/prestashop1/$dockerName/g' ".$dockerlxc);
        $Result=ssh_command($hostaddr,"sed -i 's/8001/$port/g' ".$dockerlxc);

        //création du conteneur docker...			
        $Result=ssh_command($hostaddr, "export DOCKER_CLIENT_TIMEOUT=240 && export DOCKER_HTTP_TIMEOUT=240 && export COMPOSE_HTTP_TIMEOUT=240 && docker-compose -f $dockerlxc up -d >PS_$dockerName.log");
        sleep(20);			

        // Vide l'instruction de créer une machine et puis refresh
        unset($_POST["NamePresta"]); 
        header("Refresh:0");
    }	

function FnDeletePresta($host) {		
    $dockerName=$_POST['Presta_X'];
    $dockerlxc="/root/PS/$dockerName.yml"; //Nom fichier compose
    if ($dockerName==="cancel") {exit;}		
    $Result=ssh_command($host, "docker stop $dockerName >>PS_$dockerName.log");		
    $Result=ssh_command($host, "docker rm $dockerName >>PS_$dockerName.log");	
    header("Refresh:0");	
    }
    ?>
<!--- ********************************************************************---->


<h2>Prestashop PARIS</h2>


<!-- *********************** Tableau avec les serveurs de base de données ! *******************  -->
<?php 
$Result=ssh_command($host, "docker ps -a | grep -i mysqlps | awk '{print \$NF}' ");
if ($Result<>NULL)
{
	echo <<<TABLEAU
	<table  style='width:220px'>
		<tr><th>Serveurs de bases de données</th></tr>
			<tr><td align='center'><font color='green'>Bases de données Prestashop fonctionnelles </font></td>
		</tr>
	</table>
	TABLEAU;
}
else echo "<b><font color='red'>Attention les bases de données ne sont pas lancées correctement !</font></b><br/><br/>";
?>
<br/>
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
		$Result=ssh_command($host, "docker ps | grep -i prestashop\/prestashop  | awk '{print \$NF}' ");
		foreach( $Result as $value ){
			#Acquire port : docker ps -a | grep -i $value | awk '{print $11}' | cut -d - -f1| cut -d : -f4
			$portlist =ssh_command($host, "docker ps | grep -i prestashop\/prestashop | awk '{print $11,\$NF}'| grep -i $value | cut -d - -f1| cut -d : -f4");
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

<!---
<p style='text-align: right'>
		<form method="post">
				<input type="submit" name="Reset" class="button" value="Reset" />
		</form>
</p>
--->s

<?php

/* BOUTONS */
echo <<< HTML
<center>	
   <script type="text/javascript">
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

