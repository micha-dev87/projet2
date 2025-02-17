<?php
require_once "secrets/admin_user.php";
require_once "secrets/mysql_secrets.php";
require_once "classes/classe-mysql-2025-01-29.php";
require_once  "models/AnnonceDAO.php";
require_once "models/Annonce.php";


$annonceDAO = new AnnonceDAO();
// afficher les annonces

header('Content-Type: application/json');

// Récupération des paramètres depuis l'URL propre
// Rechercher dans le lien p-{numero} pour la pagination
$pattern = '/p-([1-9]+)/';
$page = preg_match($pattern, $_SERVER['REQUEST_URI'], $matches) ? intval($matches[1]) : 1;

$recherche = $_POST['recherche'] ?? null;
$categorie = $_POST['categorie'] ?? null;
$dateDebut = $_POST['dateDebut'] ?? null;
$dateFin = $_POST['dateFin'] ?? null;
$tri = $_POST['tri'] ?? null;
$limit = 10;

try {
    $annonces = $annonceDAO->listerAnnonces(
        ($page - 1) * $limit,
        $limit,
        $recherche,
        $categorie,
        $dateDebut,
        $dateFin,
        $tri
    );
    
    $total = $annonceDAO->getAnnoncesTotal();
    
    echo json_encode([
        'success' => true,
        'data' => $annonces,
        'total' => $total,
        'pages' => ceil($total / $limit),
        'page' => $page
        
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'page' => $page
       
    ]);
}