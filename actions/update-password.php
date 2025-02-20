<?php
/*
|----------------------------------------------------------------------------------|
| Actions pour changer le mot de passe  -
|----------------------------------------------------------------------------------|
*/

$noUtilisateur = intval($GLOBALS["paramId"]);


$erreur = ""; // Stocke les messages d'erreur
$succes = ""; // Stocke les messages de succès

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /*
        |----------------------------------------------------------------------------------|
        | Recupération des valeurs du formulaire
        |----------------------------------------------------------------------------------|
        */
    $mot_de_passe = trim($_POST['mot_de_passe']);
    $mot_de_passe_confirmation = trim($_POST['mot_de_passe_confirmation']);


    /*
        |----------------------------------------------------------------------------------|
        | Verification des information du formulaire
        |----------------------------------------------------------------------------------|
        */

    if (strlen($mot_de_passe) < 5 || strlen($mot_de_passe) > 15 || !preg_match('/[a-zA-Z]/', $mot_de_passe) || !preg_match('/[0-9]/', $mot_de_passe)) {
        $erreur = "Le mot de passe doit contenir entre 5 et 15 caractères, avec des lettres et chiffres combinés.";
    } else {
        /*
            |----------------------------------------------------------------------------------|
            | Action sur la base de données "Utilisateur"
            |----------------------------------------------------------------------------------|
            */
        $utilisateur = $GLOBALS["utilisateurDAO"]->utilisateurDetail($noUtilisateur);


        if ($GLOBALS["utilisateurDAO"]->changerMotDePasse($noUtilisateur, $mot_de_passe) && $utilisateur) {
            /*
            |----------------------------------------------------------------------------------|
            | confirmation la modification du mot de passe
            |----------------------------------------------------------------------------------|
            */


            redirectTo("login/");
        } else {
            $erreur = "Erreur lors de la modification du mot de passe.";
            afficheMessageConsole("error", mysqli_error($GLOBALS["utilisateurDAO"]->db));
        }
    }
}

// Inclure la vue
require_once("vues/update-password.vue.php");
