<?php
// Vérifier si l'utilisateur est connecté
if (!estConnecte()) {
    redirectTo("login");
}

// Récupérer les informations de l'utilisateur
$utilisateur = $GLOBALS["utilisateurDAO"]->utilisateurDetail(NO_UTILISATEUR);

afficheMessageConsole("Utilisateur: " . json_encode($utilisateur));

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Création d'un nouvel objet Utilisateur avec les données du formulaire
    $utilisateurModifie = new Utilisateur();
    $utilisateurModifie->NoUtilisateur = NO_UTILISATEUR;
    $utilisateurModifie->nom = $_POST['nom'];
    $utilisateurModifie->prenom = $_POST['prenom'];
    $utilisateurModifie->no_tel_maison = $_POST['telMaison'];
    $utilisateurModifie->no_tel_cellulaire = $_POST['telCellulaire'];
    $utilisateurModifie->no_tel_travail = $_POST['telTravail'];
    $utilisateurModifie->statut = $_POST['statut']; // Modified to lower case
    $utilisateurModifie->courriel = $utilisateur->courriel; // Modified to lower case and keep existing email
    $utilisateurModifie->autreinfos = isset($_POST['hideinfospersonnel']) ? 'hideinfospersonnel' : ''; // Modified to lower case

    // Mise à jour du profil
    if ($GLOBALS["utilisateurDAO"]->mettreAJourUtilisateur($utilisateurModifie)) {
        $_SESSION['message'] = "Profil mis à jour avec succès";
        redirectTo("utilisateur/mon-profil");
    } else {
        $_SESSION['erreur'] = "Erreur lors de la mise à jour du profil";
    }
}
// Afficher la vue
require_once("vues/mon-profil.vue.php");