<?php
// controllers/utilisateur.php

// Vérifier si l'utilisateur est connecté et administrateur
    if (!isset($_SESSION['utilisateur']) || $_SESSION['utilisateur']['Statut'] != 1) {
        die("<p class=\"text-danger\">Accès refusé. Vous devez être administrateur pour effectuer cette action.</p>");
    }

// Instancier le DAO
    $utilisateurDAO = new UtilisateurDAO($GLOBALS["BDProjet2"]->cBD);


// Gérer les actions
    if($GLOBALS["action"]){

        require ACTIONS_PATH.$GLOBALS["action"].".php";
    }else{
        die("<p class=\"text-danger\">Cette action  n'existe pas ici</p>");
    }


