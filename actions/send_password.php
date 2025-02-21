<?php
/*
    |----------------------------------------------------------------------------------|
    | Envoyer un lien pour changer son mot de passe
    |----------------------------------------------------------------------------------|
    */

$erreur = ""; // Stocke les messages d'erreur
$succes = ""; // Stocke les messages de succès

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /*
        |----------------------------------------------------------------------------------|
        | Recupération des valeurs du formulaire
        |----------------------------------------------------------------------------------|
        */
    $courriel = trim($_POST['courriel']);
    $courriel_confirmation = trim($_POST['courriel_confirmation']);

    /*
        |----------------------------------------------------------------------------------|
        | Verification des information du formulaire
        |----------------------------------------------------------------------------------|
        */
    if ($courriel !== $courriel_confirmation) {
        $erreur = "Les deux adresses de courriel ne correspondent pas.";
    } elseif (!filter_var($courriel, FILTER_VALIDATE_EMAIL)) {
        $erreur = "L'adresse de courriel saisie n'est pas valide.";
    } else {
        /*
            |----------------------------------------------------------------------------------|
            | Verifier si l'email existe
            |----------------------------------------------------------------------------------|
            */

        $noUtilisateur = $GLOBALS["utilisateurDAO"]->emailExiste($courriel);


        
        if ($noUtilisateur) {
             

                // Envoyer un courriel de confirmation
                $sujet = "Changer votre mot de passe";
                $message = "<html><body>";
                $message .= "<h2>Changer votre mot de passe!</h2>";
   
                $message .= "<p>Pour finaliser votre demande, veuillez cliquer sur le bouton ci-dessous :</p>";
                $message .= "<p style='text-align:center;margin:30px 0;'>";
                $message .= "<a href='localhost" . lien("utilisateur/update-password/" . $noUtilisateur) . "' 
                                style='background-color:#4CAF50;color:white;padding:14px 25px;
                                text-decoration:none;border-radius:4px;'>
                                Changer mon mot de passe</a></p>";
                $message .= "<p>Si le bouton ne fonctionne pas, vous pouvez copier et coller ce lien dans votre navigateur :</p>";
                $message .= "<p>localhost" . lien("utilisateur/update-password/" . $noUtilisateur) . "</p>";
                $message .= "<hr>";
                $message .= "<p>Cordialement,<br>L'équipe du site</p>";
                $message .= "</body></html>";
                $to = $courriel;
          
                    require_once("shared/sendMail.php");

                    sendMail($to, $sujet, $message);
                



                // TODO : Envoyer un courriel de confirmation avec un jeton unique
                afficheMessageConsole($succes);
                $succes = "Un lien pour changer votre mot de passe vous a été envoyé par courriel.";
         
            } else {
                           /*
                |----------------------------------------------------------------------------------|
                | Definir le message d'erreur -
                |----------------------------------------------------------------------------------|
                */
            $erreur = "Cette Adresse de courriel n'existe pas.";
            afficheMessageConsole($erreur, true);
            }
        }
    
}

// Inclure la vue
require_once("vues/send_password.vue.php");