<?php
    /*
    |----------------------------------------------------------------------------------|
    | Controller dashboard
    |----------------------------------------------------------------------------------|
    */
    $noConnexion = $_SESSION['no_connexion'];
    $NoUtilisateur = $_SESSION['utilisateur']['NoUtilisateur'];
    $Nom = $_SESSION['utilisateur']['Nom'];
    $Prenom = $_SESSION['utilisateur']['Prenom'];
    $Courriel = $_SESSION['utilisateur']['Courriel'];
    $Statut = $_SESSION['utilisateur']['Statut'];

    if(isset($noConnexion)){

        echo "<H4>Bienvenu <i class='fa-spin fa-regular fa-face-smile'> </i></h4>";
        echo "<h2> Bonjour ".$Prenom." tu as le role  :  ".strStatut($Statut)."</h2>";


    }
