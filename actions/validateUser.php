<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $noUtilisateur = intval($_POST['no_utilisateur']);
        $nouveauStatut = intval($_POST['statut']);

        // Mettre à jour le statut de l'utilisateur
        $resultat = $GLOBALS["utilisateurDAO"]->confimerUtilisateur($noUtilisateur, $nouveauStatut);
        if ($resultat) {
            echo afficherModal("L'utilisateur : $noUtilisateur ", "est désormais actif");
            sleep(5);
            header("Location: dashboard");
            exit();
        } else {
            die("Erreur lors de la mise à jour du statut.");
        }
    }