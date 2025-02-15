<?php


// Vérifier si l'utilisateur est connecté et administrateur
    if (!isset($_SESSION['utilisateur']) || $_SESSION['utilisateur']['Statut'] != 1) {
        die("<p class=\"text-danger\">Accès refusé. Vous devez être administrateur pour effectuer cette action.</p>");
    }
    if ($GLOBALS["paramId"]) {
        $resultat = $GLOBALS["utilisateurDAO"]->supprimerUtilisateur($GLOBALS["paramId"]);
        if ($resultat) {

            echo '<script type="text/javascript">
        window.location.href = "' . lien("dashboard") . '";
      </script>';
            exit();
        } else {
            die("Erreur lors de la mise à jour du statut.");
        }
    } else {
        die("ID utilisateur manquant.");
    }