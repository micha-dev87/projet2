<?php
/*
    |----------------------------------------------------------------------------------|
    | Controller register - pour enregistrer l'utilisateur
    |----------------------------------------------------------------------------------|
    */

$erreur = ""; // Stocke les messages d'erreur
$succes = ""; // Stocke les messages de succès

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /*
        |----------------------------------------------------------------------------------|
        | Recupération des valeurs du formulaire
        |----------------------------------------------------------------------------------|
        */
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $courriel = trim($_POST['courriel']);
    $courriel_confirmation = trim($_POST['courriel_confirmation']);
    $mot_de_passe = trim($_POST['mot_de_passe']);
    $mot_de_passe_confirmation = trim($_POST['mot_de_passe_confirmation']);


    /*
        |----------------------------------------------------------------------------------|
        | Verification des information du formulaire
        |----------------------------------------------------------------------------------|
        */
    if ($courriel !== $courriel_confirmation) {
        $erreur = "Les deux adresses de courriel ne correspondent pas.";
    } elseif ($mot_de_passe !== $mot_de_passe_confirmation) {
        $erreur = "Les deux mots de passe ne correspondent pas.";
    } elseif (!filter_var($courriel, FILTER_VALIDATE_EMAIL)) {
        $erreur = "L'adresse de courriel saisie n'est pas valide.";
    } elseif (strlen($mot_de_passe) < 5 || strlen($mot_de_passe) > 15 || !preg_match('/[a-zA-Z]/', $mot_de_passe) || !preg_match('/[0-9]/', $mot_de_passe)) {
        $erreur = "Le mot de passe doit contenir entre 5 et 15 caractères, avec des lettres et chiffres combinés.";
    
    } else {
        /*
            |----------------------------------------------------------------------------------|
            | Action sur la base de données "Utilisateur"
            |----------------------------------------------------------------------------------|
            */

            $utilisateur = new Utilisateur();
            
            // nouveau utilisateur
            $utilisateur->nom = $nom;
            $utilisateur->prenom = $prenom;
            $utilisateur->courriel = $courriel;
            $utilisateur->mot_de_passe = $mot_de_passe;
            
            afficheMessageConsole("nom et prenom : " . $nom . " " . $prenom);

        $utilisateurModel = new UtilisateurDAO();
        if ($utilisateurModel->emailExiste($courriel)) {
            /*
                |----------------------------------------------------------------------------------|
                | Definir le message d'erreur -
                |----------------------------------------------------------------------------------|
                */
            $erreur = "Cette adresse de courriel est déjà utilisée. Veuillez en choisir une autre.";
        } else {


            $resultat_insertion = $utilisateurModel->ajouterUtilisateur($utilisateur);

            if ($resultat_insertion) {

                // Envoyer un courriel de confirmation
                $sujet = "Confirmation d'inscription";
                $message = "<html><body>";
                $message .= "<h2>Bienvenue " . htmlspecialchars($prenom) . " " . htmlspecialchars($nom) . "!</h2>";
                $message .= "<p>Merci de vous être inscrit sur notre site.</p>";
                $message .= "<p>Votre compte a été créé avec succès.</p>";
                $message .= "<p>Pour finaliser votre inscription, veuillez cliquer sur le bouton ci-dessous :</p>";
                $message .= "<p style='text-align:center;margin:30px 0;'>";
                $message .= "<a href='http://" . $_SERVER['SERVER_NAME'] . lien("utilisateur/validateUser/" . $resultat_insertion) . "' 
                                style='background-color:#4CAF50;color:white;padding:14px 25px;
                                text-decoration:none;border-radius:4px;'>
                                Confirmer mon inscription</a></p>";
                $message .= "<p>Si le bouton ne fonctionne pas, vous pouvez copier et coller ce lien dans votre navigateur :</p>";
                $message .= "<p>https://" . $_SERVER['SERVER_NAME'] . lien("utilisateur/validateUser/" . $resultat_insertion) . "</p>";
                $message .= "<hr>";
                $message .= "<p>Cordialement,<br>L'équipe du site</p>";
                $message .= "</body></html>";
                $to = $courriel;
            
                    require_once("shared/sendMail.php");

                    sendMail($to, $sujet, $message);
                



                // TODO : Envoyer un courriel de confirmation avec un jeton unique
                afficheMessageConsole($succes);
                /*
                    |----------------------------------------------------------------------------------|
                    | Attendre rediriger vers la page login
                    |----------------------------------------------------------------------------------|
                    */
                redirectTo("login/new");
            } else {
                $erreur = "Erreur lors de l'enregistrement.";
                afficheMessageConsole($erreur, true);
            }
        }
    }
}

// Inclure la vue
require_once("vues/register.vue.php");
