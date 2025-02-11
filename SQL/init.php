<?php
    /*
    |----------------------------------------------------------------------------------------|
    | init mysql connexion
    |----------------------------------------------------------------------------------------|
    */

    /* --- Initialisation des variables de travail --- */
    $strNomBD  = "if0_38253009_projet2";
    $strTabUser = "utilisateurs";
    $strTabCon = "connexions";
    $strTabAnn = "annonces";
    $strTabCat = "categories";
    $strInfosSensibles = "secrets/mysql_secrets.php";

    /* --- Création de l'instance, connexion avec mySQL et sélection de la base de données (RÉUSSITE) --- */
    $BDProjet2 = new mysql($strNomBD, $strInfosSensibles);

    /* --- Sélectionner la base de données ---*/
    $BDProjet2->selectionneBD();
    if($BDProjet2->OK){
        afficheMessageConsole("Connexion à la base de données avec success !");
    }else{
        afficheMessageConsole("Erreur de connexion dans la base de données !", true);
    }
    /* --- Création de la structure de la table utilisateurs --- */
    $BDProjet2->creeTableGenerique(
        $strTabUser,
        'A,NoUtilisateur;V50,Courriel;V60,MotDePasse;D,Creation;E,NbConnexions;E,Statut;
        E,NoEmpl;V25,Nom;V20,Prenom;V15,NoTelMaison;V21,NoTelTravail;V15,NoTelCellulaire;D,Modification;V50,AutresInfos',
        'NoUtilisateur'
    );
    /* --- Création de la structure de la table connexions --- */
    $BDProjet2->creeTableGenerique(
        $strTabCon,
        'A,NoConnexion;E,NoUtilisateur;D,Connexion;D,Deconnexion',
        'NoConnexion'
    );
    /* --- Création de la structure de la table annonces --- */
    $BDProjet2->creeTableGenerique(
        $strTabAnn,
        'A,NoAnnonce;E,NoUtilisateur;D,Parution;E,Categorie;V50,DescriptionAbregee;
        V250,DescriptionComplete;M,Prix;V50,Photo;D,MiseAJour;E,Etat',
        'NoAnnonce'
    );
    /* --- Création de la structure de la table categories --- */
    $BDProjet2->creeTableGenerique(
        $strTabCat,
        'A,NoCategorie;V20,Description',
        'NoCategorie'
    );
    /*
    |----------------------------------------------------------------------------------|
    | Ajouter les données de l'administrateur si celui-ci n'existe pas déja
    |----------------------------------------------------------------------------------|
    */
    require_once ("secrets/admin_user.php");
    $adminUserDAO = new UtilisateurDAO($BDProjet2->cBD);
    $adminUser = new Utilisateur(ADMIN_NAME, ADMIN_SURNAME, ADMIN_EMAIL, ADMIN_MOTDEPASSE, ADMIN_PHONE,ADMIN_PHONE, ADMIN_PHONE, 1);
    if(!$adminUserDAO->emailExiste(ADMIN_EMAIL)){
        $adminUserDAO->ajouterUtilisateur($adminUser );
        afficheMessageConsole("Ajout de l'administrateur avec success !");
    }else{
        afficheMessageConsole("Administrateur à déjà été ajouté !");
    }

    /*
    |----------------------------------------------------------------------------------|
    | Ajouter les categories
    |----------------------------------------------------------------------------------|
    */
    $categorieDAO = new CategorieDAO($BDProjet2->cBD);

    $categories = $categorieDAO->getAllCategorie();
    if(!empty($categories)){
        afficheMessageConsole("les catégories ont déjà été ajouté !");
    }else{
        foreach (["Location", "Recherche", "A vendre", "Service offert", "Autre"] as $Description){
            $categorieDAO->addCategorie($Description);
        }
        afficheMessageConsole("Categories ajouté !");
    }






