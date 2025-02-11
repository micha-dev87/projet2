<?php
// controllers/utilisateur.php


// Instancier le DAO
    $utilisateurDAO = new UtilisateurDAO($GLOBALS["BDProjet2"]->cBD);


// Gérer les actions
    if($GLOBALS["action"]){
        require_once (ACTIONS_PATH.$GLOBALS["action"].".php");

    }else{
        header("Location: ".lien("dashboard "));
        exit();
    }


