<?php
    /*
    |----------------------------------------------------------------------------------|
    | Controller logout - deconnection
    |----------------------------------------------------------------------------------|
    */

// Déconnecter l'utilisateur
    global $BDProjet2, $uriSegments;
    $utilisateurDAO = new UtilisateurDAO($BDProjet2->cBD);
    $utilisateurDAO->deconnecterUtilisateur();

// Rediriger vers la page de connexion
    header("Location: ".chemin("login", $uriSegments[0]));
    exit();