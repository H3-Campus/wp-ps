<?php
    $serveurBD = "192.168.150.213";
    $nomUtilisateur = "root";
    $motDePasse = null;
    $baseDeDonnees = "Infos";
   
    mysqli_connect($serveurBD,
                  $nomUtilisateur,
                  $motDePasse,
                  $baseDeDonnees);

   // $connexionReussie = mysqli_select_db($baseDeDonnees);
   echo "YES !";
    // Et pour mettre fin à la connexion
    mysqli_close();
?>