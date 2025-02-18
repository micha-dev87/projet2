<?php
// controllers/login.php
    $erreur = ""; // Stocke les messages d'erreur
    $success = ""; // Stocke les messages de succès
    
// Vérifier si le formulaire a été soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupérer les données du formulaire
        $courriel = trim($_POST['courriel']);
        $mot_de_passe = trim($_POST['mot_de_passe']);

        // Validation côté serveur
        if (!filter_var($courriel, FILTER_VALIDATE_EMAIL)) {
            $erreur = "L'adresse de courriel saisie n'est pas valide.";
        } elseif (strlen($mot_de_passe) < 5 || strlen($mot_de_passe) > 15) {
            $erreur = "Le mot de passe doit contenir entre 5 et 15 caractères.";
        } else {
            // Authentifier l'utilisateur
            $utilisateurDAO = new UtilisateurDAO();
            $utilisateur = $utilisateurDAO->authentifierUtilisateur($courriel, $mot_de_passe);

            if (is_array($utilisateur)) {
                // Stocker les informations de l'utilisateur dans la session
                $_SESSION['utilisateur'] = [
                    'NoUtilisateur' => $utilisateur['NoUtilisateur'],
                    'Nom' => $utilisateur['Nom'],
                    'Prenom' => $utilisateur['Prenom'],
                    'Courriel' => $utilisateur['Courriel'],
                    'Statut' => $utilisateur['Statut']
                ];

                // Rediriger vers la page d'accueil ou le tableau de bord
              redirectTo("dashboard");

            } else {
                $erreur = $utilisateur; // Message d'erreur retourné par authentifierUtilisateur
            }
        }
    }
$action = $GLOBALS["action"]??null;

if($action == "validated") {
    $success = "Votre compte a été validé avec succès. Vous pouvez maintenant vous connecter.";
} else if($action == "new") {
    $success = "Votre compte a été créé avec succès. Veuillez le confirmer en cliquant sur le lien envoyé à votre adresse courriel.";
}
// Inclure la vue
    require_once 'vues/login.vue.php';