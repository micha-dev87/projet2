<?php


// Vérifier si l'utilisateur est connecté et administrateur
    if (!isset($_SESSION['utilisateur']) || $_SESSION['utilisateur']['Statut'] != 1) {
        die("<p class=\"text-danger\">Accès refusé. Vous devez être administrateur pour effectuer cette action.</p>");
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $noUtilisateur = intval($_POST['no_utilisateur']);
        $nouveauStatut = intval($_POST['statut']);

        // Mettre à jour le statut de l'utilisateur
        $resultat = $GLOBALS["utilisateurDAO"]->confimerUtilisateur($noUtilisateur, $nouveauStatut);
        if ($resultat) {

            header("Location: dashboard");
            exit();
        } else {
            die("Erreur lors de la mise à jour du statut.");
        }
    }