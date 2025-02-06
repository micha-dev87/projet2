<?php
    /*
    |----------------------------------------------------------------------------------------|
    | init mysql connexion
    |----------------------------------------------------------------------------------------|
    */

    /* --- Initialisation des variables de travail --- */
    $strNomBD  = "if0_38253009_projet2";
    $strNomTab1 = "utilisateurs";
    $strNomTab2 = "connexions";
    $strNomTab3 = "annonces";
    $strNomTab4 = "categories";
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
        'utilisateurs',
        'E,NoUtilisateur;V50,Courriel;V15,MotDePasse;D,Creation;E,NbConnexions;E,Statut;
        E,NoEmpl;V25,Nom;V20,Prenom;V15,NoTelMaison;V21,NoTelTravail;V15,NoTelCellulaire;D,Modification;V50,AutresInfos',
        'NoUtilisateur'
    );
    /* --- Création de la structure de la table connexions --- */
    $BDProjet2->creeTableGenerique(
        'connexions',
        'E,NoConnexion;E,NoUtilisateur;D,Connexion;D,Deconnexion',
        'NoConnexion'
    );
    /* --- Création de la structure de la table annonces --- */
    $BDProjet2->creeTableGenerique(
        'annonces',
        'E,NoAnnonce;E,NoUtilisateur;D,Parution;E,Categorie;V50,DescriptionAbregee;
        V250,DescriptionComplete;M,Prix;V50,Photo;D,MiseAJour;E,Etat',
        'NoAnnonce'
    );
    /* --- Création de la structure de la table categories --- */
    $BDProjet2->creeTableGenerique(
        'categories',
        'E,NoCategorie;V20,Description',
        'NoCategorie'
    );