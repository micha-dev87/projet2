<?php
    /* --- Ajouter l'administrateur ---- */
    // Coordonnées de l'administrateur;
    require_once ("secrets/admin_user.php");
    $adminUser = new UtilisateurDAO($BDProjet2->cBD);
    if(!$adminUser->emailExiste(ADMIN_EMAIL)){
        $adminUser->ajouterUtilisateur(
            ADMIN_NAME, ADMIN_SURNAME, ADMIN_EMAIL, ADMIN_MOTDEPASSE, ADMIN_PHONE, ADMIN_PHONE, ADMIN_PHONE, true
        );
        afficheMessageConsole("Ajout de l'administrateur avec success !");
    }else{
        afficheMessageConsole("Administrateur à déjà été ajouté !");
    }