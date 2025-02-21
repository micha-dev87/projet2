<?php

// actions/vider-data.php
//verifier que l'utilisateur est administrateur
if (!estAdmin()) {
    redirectTo("dashboard");
}

$utilisateurDAO = $GLOBALS["utilisateurDAO"];
$annonceDAO = $GLOBALS["annonceDAO"];



?>
