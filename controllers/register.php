<?php
// controllers/register.controller.php

    require_once 'models/utilisateurDAO.php'; // Inclure le modèle utilisateur

    $erreur = ""; // Stocke les messages d'erreur
    $succes = ""; // Stocke les messages de succès

// Vérifier si le formulaire a été soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupérer les données du formulaire
        $nom = trim($_POST['nom']);
        $prenom = trim($_POST['prenom']);
        $courriel = trim($_POST['courriel']);
        $courriel_confirmation = trim($_POST['courriel_confirmation']);
        $mot_de_passe = trim($_POST['mot_de_passe']);
        $mot_de_passe_confirmation = trim($_POST['mot_de_passe_confirmation']);
        $no_tel_maison = trim($_POST['no_tel_maison']);
        $no_tel_travail = trim($_POST['no_tel_travail']);
        $no_tel_cellulaire = trim($_POST['no_tel_cellulaire']);

        // Validation côté serveur
        if ($courriel !== $courriel_confirmation) {
            $erreur = "Les deux adresses de courriel ne correspondent pas.";
        } elseif ($mot_de_passe !== $mot_de_passe_confirmation) {
            $erreur = "Les deux mots de passe ne correspondent pas.";
        } elseif (!filter_var($courriel, FILTER_VALIDATE_EMAIL)) {
            $erreur = "L'adresse de courriel saisie n'est pas valide.";
        } elseif (strlen($mot_de_passe) < 5 || strlen($mot_de_passe) > 15 || !preg_match('/[a-zA-Z]/', $mot_de_passe) || !preg_match('/[0-9]/', $mot_de_passe)) {
            $erreur = "Le mot de passe doit contenir entre 5 et 15 caractères, avec des lettres et chiffres combinés.";
        } elseif (!preg_match('/^[a-zA-ZÀ-ÿ][a-zA-ZÀ-ÿ \-'.'’]*[a-zA-ZÀ-ÿ]$/', $nom)) {
        $erreur = "Le nom est invalide.";
    } elseif (!preg_match('/^[a-zA-ZÀ-ÿ][a-zA-ZÀ-ÿ \-'.'’]*[a-zA-ZÀ-ÿ]$/', $prenom)) {
            $erreur = "Le prénom est invalide.";
        } else {
            // Vérifier si l'adresse de courriel existe déjà dans la base de données
            $utilisateurModel = new UtilisateurDAO($BDProjet2->cBD);
            if ($utilisateurModel->emailExiste($courriel)) {
                $erreur = "Cette adresse de courriel est déjà utilisée. Veuillez en choisir une autre.";
            } else {
                // Insérer l'utilisateur dans la base de données
                $mot_de_passe_hache = password_hash($mot_de_passe, PASSWORD_BCRYPT); // Hacher le mot de passe

                $resultat_insertion = $utilisateurModel->insererUtilisateur(
                    $nom,
                    $prenom,
                    $courriel,
                    $mot_de_passe_hache,
                    $no_tel_maison,
                    $no_tel_travail,
                    $no_tel_cellulaire
                );

                if ($resultat_insertion) {

                    $succes = "Votre inscription a été enregistrée avec succès. Un courriel de confirmation vous a été envoyé.";
                    // TODO : Envoyer un courriel de confirmation avec un jeton unique
                    afficheMessageConsole($succes);
                } else {
                    $erreur = "Erreur lors de l'enregistrement.";
                    afficheMessageConsole($erreur, true);
                }
            }
        }
}

// Inclure la vue
    require_once ("vues/register.vue.php");