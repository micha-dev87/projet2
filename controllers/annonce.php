
<?php

// dossier controllers
// controllers/annonce.php

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['utilisateur'])) {
    header("Location: " . lien("login"));
    exit();
}


$annonceDAO = new AnnonceDAO($GLOBALS["BDProjet2"]->cBD);
$categorieDAO = new CategorieDAO($GLOBALS["BDProjet2"]->cBD);
$id_annonce = intval($GLOBALS["paramId"]);

// Gérer les actions
if ($GLOBALS["action"]) {
    require_once(ACTIONS_PATH . $GLOBALS["action"] . ".php");
} else {
    header("Location: " . lien("dashboard"));
    exit();
}