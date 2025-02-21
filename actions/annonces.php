<?php
// Connect to database


global $annonceDAO; 
global $utilisateurDAO;
global $categorieDAO;
echo DB_NAME . "" . print_r($annonceDAO->db);
$annonces = $annonceDAO->listerAnnonces(
    0,
    10,
    null,
    null,
    null,
    null,
    ''
);


echo "<pre>";
print_r($annonces);
echo "</pre>";