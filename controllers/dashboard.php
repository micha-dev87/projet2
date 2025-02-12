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
    //liste annonces
    $annonces = $annonceDAO->listerAnnonces();



// Vérifier si l'utilisateur est connecté
    if (!isset($_SESSION['utilisateur'])) {
        echo '<script type="text/javascript">
        window.location.href = "' . lien("login") . '";
      </script>';

        exit();
    }

// Récupérer les informations de l'utilisateur connecté
    $noConnexion = $_SESSION['no_connexion'];
    $NoUtilisateur = $_SESSION['utilisateur']['NoUtilisateur'];
    $Nom = $_SESSION['utilisateur']['Nom'];
    $Prenom = $_SESSION['utilisateur']['Prenom'];
    $Courriel = $_SESSION['utilisateur']['Courriel'];
    $Statut = $_SESSION['utilisateur']['Statut'];




// Passer les données à la vue selon l'utilisateur
    if ($Statut == 1) :
        //liste des utilisateur :::: administrateur
        $listeUtilisateurs = $utilisateurDAO->listerUtilisateurs();
        require_once 'vues/dashboard.admin.vue.php';
    else:
        require_once 'vues/dashboard.vue.php';
    endif;