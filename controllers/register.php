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
        $nom = trim($_POST['nom']);
        $prenom = trim($_POST['prenom']);
        $courriel = trim($_POST['courriel']);
        $courriel_confirmation = trim($_POST['courriel_confirmation']);
        $mot_de_passe = trim($_POST['mot_de_passe']);
        $mot_de_passe_confirmation = trim($_POST['mot_de_passe_confirmation']);
        $no_tel_maison = trim($_POST['no_tel_maison']);
        $no_tel_travail = trim($_POST['no_tel_travail']);
        $no_tel_cellulaire = trim($_POST['no_tel_cellulaire']);

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
        } elseif (!preg_match('/^[a-zA-ZÀ-ÿ][a-zA-ZÀ-ÿ \-' . '’]*[a-zA-ZÀ-ÿ]$/', $nom)) {
            $erreur = "Le nom est invalide.";
        } elseif (!preg_match('/^[a-zA-ZÀ-ÿ][a-zA-ZÀ-ÿ \-' . '’]*[a-zA-ZÀ-ÿ]$/', $prenom)) {
            $erreur = "Le prénom est invalide.";
        } else {
            /*
            |----------------------------------------------------------------------------------|
            | Action sur la base de données "Utilisateur"
            |----------------------------------------------------------------------------------|
            */

            // nouveau utilisateur
            $utilisateur = new Utilisateur($nom, $prenom, $courriel, $mot_de_passe, $no_tel_cellulaire, $no_tel_travail, $no_tel_maison);


            $utilisateurModel = new UtilisateurDAO();
            if ($utilisateurModel->emailExiste($courriel)) {
                /*
                |----------------------------------------------------------------------------------|
                | Definir le message d'erreur -
                |----------------------------------------------------------------------------------|
                */
                $erreur = "Cette adresse de courriel est déjà utilisée. Veuillez en choisir une autre.";
            } else {
                /*
                |----------------------------------------------------------------------------------|
                | Hashé le mot de passe
                |----------------------------------------------------------------------------------|
                */
                $mot_de_passe_hache = password_hash($mot_de_passe, PASSWORD_BCRYPT); // Hacher le mot de passe

                $resultat_insertion = $utilisateurModel->ajouterUtilisateur($utilisateur);

                if ($resultat_insertion) {
                    /*
                    |----------------------------------------------------------------------------------|
                    | Message de confirmation d'ajout
                    |----------------------------------------------------------------------------------|
                    */
                    $succes = "Votre inscription a été enregistrée avec succès. Un courriel de confirmation vous a été envoyé.";
                    // TODO : Envoyer un courriel de confirmation avec un jeton unique
                    afficheMessageConsole($succes);
                    /*
                    |----------------------------------------------------------------------------------|
                    | Attendre rediriger vers la page login
                    |----------------------------------------------------------------------------------|
                    */
                    echo '<script type="text/javascript">
                    window.location.href = "' . lien("login") . '";
                             </script>';
                    exit();
                } else {
                    $erreur = "Erreur lors de l'enregistrement.";
                    afficheMessageConsole($erreur, true);
                }
            }
        }
    }

// Inclure la vue
    require_once("vues/register.vue.php");