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


        echo "<h2> Bonjour ".$Prenom." tu as le role  :  ".strStatut($Statut)."</h2>";

    }
