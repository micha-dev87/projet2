<?php


// Vérifier si l'utilisateur est connecté
if (!estConnecte()) {
    redirectTo("login");
}

// Récupérer les informations de l'utilisateur
$utilisateur = $GLOBALS["utilisateurDAO"]->utilisateurDetail(NO_UTILISATEUR);
if (strpos($utilisateur->no_tel_travail, "|") !== false) {
    list($post_tel_travail, $numero_tel_travail) = explode("|", $utilisateur->no_tel_travail);
} else {
    $post_tel_travail = "";
    $numero_tel_travail = "";
}

afficheMessageConsole("Utilisateur: " . json_encode($utilisateur));

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Verifier que les champs nom, prenom ont été entre et sont correctes
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $NoEmpl = $_POST['NoEmpl'];
    $autresInfos = (isset($_POST['hidePhone']) ? 'hidePhone|' : '') . (isset($_POST['hideCourriel']) ? 'hideCourriel|' : '') . (isset($_POST['hidePrenom']) ? 'hidePrenom' : '');
    $numero_tel_travail = $_POST['numTelTravail'];
    $post_tel_travail = $_POST['postTelTravail'];

    if (!preg_match('/^[a-zA-ZÀ-ÿ][a-zA-ZÀ-ÿ \-' . '’]*[a-zA-ZÀ-ÿ]$/', $nom)) {
        $_SESSION['erreur'] = "Le nom est invalide.";
    } elseif (!preg_match('/^[a-zA-ZÀ-ÿ][a-zA-ZÀ-ÿ \-' . '’]*[a-zA-ZÀ-ÿ]$/', $prenom)) {
        $_SESSION['erreur'] = "Le prénom est invalide.";
    } else {


        $tel_travail = $post_tel_travail . "|" . $numero_tel_travail;
        // Création d'un nouvel objet Utilisateur avec les données du formulaire
        $utilisateurModifie = new Utilisateur();
        $utilisateurModifie->NoUtilisateur = NO_UTILISATEUR;
        $utilisateurModifie->nom = $nom;
        $utilisateurModifie->prenom = $prenom;
        $utilisateurModifie->no_tel_maison = $_POST['telMaison'];
        $utilisateurModifie->no_tel_cellulaire = $_POST['telCellulaire'];
        $utilisateurModifie->no_tel_travail = $tel_travail;
        $utilisateurModifie->statut = $_POST['statut']; // Modified to lower case
        $utilisateurModifie->courriel = $utilisateur->courriel; // Modified to lower case and keep existing email
        $utilisateurModifie->NoEmpl = $NoEmpl;
        $utilisateurModifie->autreinfos = $autresInfos; // Modified to lower case
        
     
        // Mise à jour du profil
        if ($GLOBALS["utilisateurDAO"]->mettreAJourUtilisateur($utilisateurModifie)) {
            $_SESSION['message'] = "Profil mis à jour avec succès";
       
        } else {
            $_SESSION['erreur'] = "Erreur lors de la mise à jour du profil";
        }
    }
}
// Afficher la vue
require_once("vues/mon-profil.vue.php");