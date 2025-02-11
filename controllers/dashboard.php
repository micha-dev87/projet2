<?php
/*
|----------------------------------------------------------------------------------|
| Controller dashboard
|----------------------------------------------------------------------------------|
*/

    // Base de donnée - USER
    $utilisateurDAO = new UtilisateurDAO($GLOBALS["BDProjet2"]->cBD);
    // Annonce
    $annonceDAO = new AnnonceDAO($GLOBALS["BDProjet2"]->cBD);
    //liste annonces
    $annonces = $annonceDAO->listerAnnonces();



// Vérifier si l'utilisateur est connecté
    if (!isset($_SESSION['utilisateur'])) {
        header("Location: ".lien("login"));
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