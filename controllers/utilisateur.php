<?php
// controllers/utilisateur.php


// Instancier le DAO
    $utilisateurDAO = new UtilisateurDAO();


// Gérer les actions
    if($GLOBALS["action"]){
        require_once (ACTIONS_PATH.$GLOBALS["action"].".php");

    }else{
        echo '<script type="text/javascript">
        window.location.href = "' . lien("dashboard") . '";
      </script>';
        exit();
    }


