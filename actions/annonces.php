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

echo "<pre>";
print_r($annonces);
echo "</pre>";