<?php



    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $noUtilisateur = intval($_POST['no_utilisateur']);
        $nouveauStatut = intval($_POST['statut']);

        // Mettre à jour le statut de l'utilisateur
        $resultat = $GLOBALS["utilisateurDAO"]->confimerUtilisateur($noUtilisateur, $nouveauStatut);
        if ($resultat) {

        redirectTo("dashboard");

        } else {
            die("Erreur lors de la mise à jour du statut.");
        }
    }

        

    $paramId = intval($GLOBALS["paramId"])??null;

    if(!is_null($paramId)) {
        $resultat = $GLOBALS["utilisateurDAO"]->confimerUtilisateur($paramId, 9);
        if ($resultat) {
            redirectTo("login/validated");
        } else {
            die("Erreur lors de la mise à jour du statut.");
        }
    } else {
        die("Erreur lors de la récupération de l'utilisateur.");
    }