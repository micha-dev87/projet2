<?php
/*
|----------------------------------------------------------------------------------|
| Controller dashboard
|----------------------------------------------------------------------------------|
*/

    // Base de donnée - USER
    $utilisateurDAO = new UtilisateurDAO();
    // Annonce
    $annonceDAO = new AnnonceDAO();


// Vérifier si l'utilisateur est connecté
    if (is_null(NO_UTILISATEUR)) {
        echo '<script type="text/javascript">
        window.location.href = "' . lien("login") . '";
      </script>';

        exit();
    }






// Passer les données à la vue selon l'utilisateur
    if (STATUT_UTILISATEUR == 1) :

        require_once 'vues/dashboard.admin.vue.php';
    else:
        require_once 'vues/dashboard.vue.php';
    endif;