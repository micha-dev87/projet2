<?php

    if($GLOBALS["controller"] =="annonce"){

        $annonce = $GLOBALS["annonceDAO"]->getAnnonceById($GLOBALS["id_annonce"]);
        if ($annonce) {
            require_once 'vues/annonce.details.vue.php';
        } else {
            die("<p class=\"text-danger\">Aucune annonce crée!</p>");
        }
    }