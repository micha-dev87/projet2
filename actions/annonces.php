<?
// Connect to database


global $annonceDAO; 
global $utilisateurDAO;
global $categorieDAO;

$annonces = $annonceDAO->listerAnnonces(
    0,
    10,
    null,
    null,
    null,
    null,
    ''
);

// Return as JSON
header('Content-Type: application/json');
echo json_encode($annonces);