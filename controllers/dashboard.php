<?php
/*
|----------------------------------------------------------------------------------|
| Controller dashboard
|----------------------------------------------------------------------------------|
*/


// Vérifier si l'utilisateur est connecté
    if (!isset($_SESSION['utilisateur'])) {
        header("Location: login");
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
        // Récupérer la liste des utilisateurs
        $utilisateurDAO = new UtilisateurDAO($BDProjet2->cBD);
        $listeUtilisateurs = $utilisateurDAO->listerUtilisateurs();
        require_once 'vues/dashboard.admin.vue.php';
    else:
        require_once 'vues/dashboard.vue.php';
    endif;