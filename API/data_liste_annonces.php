<?php
require "secrets/admin_user.php";
require "secrets/mysql_secrets.php";
require "classes/classe-mysql-2025-01-29.php";
require "models/AnnonceDAO.php";
require "models/Annonce.php";

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
$id_user = $_POST['id_user'] ?? null;
$limit = 10;

try {
    $annonces = $annonceDAO->listerAnnonces(
        ($page - 1) * $limit,
        $limit,
        $recherche,
        $categorie,
        $dateDebut,
        $dateFin,
        $tri,
        $id_user
    );
    
    $total = $annonceDAO->getAnnoncesTotal( 
        $recherche,
    $categorie,
    $dateDebut,
    $dateFin,
    $id_user);
    
    echo json_encode([
        'success' => true,
        'data' => $annonces,
        'total' => $total,
        'pages' => ceil($total / $limit),
        'page' => $page,
        'requete' => $annonceDAO->sql
        
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'page' => $page,
        'requete' => $annonceDAO->sql
        
       
    ]);
}