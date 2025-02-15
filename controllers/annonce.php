
<?php

// dossier controllers
// controllers/annonce.php

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['utilisateur'])) {
    echo '<script type="text/javascript">
            window.location.href = "' . lien("login") . '";
          </script>';
    exit();
}


$annonceDAO = new AnnonceDAO();
$utilisateurDAO = new UtilisateurDAO();
$categorieDAO = new CategorieDAO();


// Gérer les actions
if ($GLOBALS["action"]) {
    require_once(ACTIONS_PATH . $GLOBALS["action"] . ".php");
} else {
    echo '<script type="text/javascript">
        window.location.href = "' . lien("dashboard") . '";
      </script>';
    exit();
}