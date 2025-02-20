<?php
/*
|----------------------------------------------------------------------------------------|
| init mysql connexion
|----------------------------------------------------------------------------------------|
*/

/* --- Initialisation des variables de travail --- */

define('TABLE_USERS', 'utilisateurs');
define('TABLE_CONNECTIONS', 'connexions');
define('TABLE_ANNOUNCEMENTS', 'annonces');
define('TABLE_CATEGORIES', 'categories');

/* --- Création de l'instance, connexion avec mySQL et sélection de la base de données (RÉUSSITE) --- */
$BDProjet2 = new mysql();

/* --- Se connecter à la base de données---*/
$BDProjet2->connexion();
if($BDProjet2->OK){
    afficheMessageConsole("Connexion à la base de données avec success !");
}else{
    afficheMessageConsole("Erreur de connexion dans la base de données !", true);
}
/* --- Création de la structure de la table utilisateurs --- */
$BDProjet2->creeTableGenerique(
    TABLE_USERS,
    'A,NoUtilisateur;V50,Courriel;V60,MotDePasse;D,Creation;E,NbConnexions;E,Statut;
    E,NoEmpl;V25,Nom;V20,Prenom;V15,NoTelMaison;V21,NoTelTravail;V15,NoTelCellulaire;D,Modification;V50,AutresInfos',
    'NoUtilisateur'
);
/* --- Création de la structure de la table connexions --- */
$BDProjet2->creeTableGenerique(
    TABLE_CONNECTIONS,
    'A,NoConnexion;E,NoUtilisateur;D,Connexion;D,Deconnexion',
    'NoConnexion'
);
/* --- Création de la structure de la table annonces --- */
$BDProjet2->creeTableGenerique(
    TABLE_ANNOUNCEMENTS,
    'A,NoAnnonce;E,NoUtilisateur;D,Parution;E,Categorie;V50,DescriptionAbregee;
    V250,DescriptionComplete;M,Prix;V100,Photo;D,MiseAJour;E,Etat',
    'NoAnnonce'
);
/* --- Création de la structure de la table categories --- */
$BDProjet2->creeTableGenerique(
    TABLE_CATEGORIES,
    'A,NoCategorie;V20,Description',
    'NoCategorie'
);

/*
 * Fermer la connexion
 */
$BDProjet2->deconnexion();
/*
|----------------------------------------------------------------------------------|
| Ajouter les données de l'administrateur si celui-ci n'existe pas déja
|----------------------------------------------------------------------------------|
*/

$adminUserDAO = new UtilisateurDAO();
$adminUser = new Utilisateur();
$adminUser ->nom = ADMIN_NAME;
$adminUser->prenom = ADMIN_SURNAME;
$adminUser->courriel = ADMIN_EMAIL;
$adminUser->mot_de_passe = ADMIN_MOTDEPASSE;
$adminUser->no_tel_maison = ADMIN_PHONE;
$adminUser->no_tel_travail = ADMIN_PHONE;
$adminUser->no_tel_cellulaire = ADMIN_PHONE;
$adminUser->statut = 1;
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
$categorieDAO = new CategorieDAO();
$categories = [];
$categories = $categorieDAO->getAllCategorie();
if(sizeof($categories) >0){
    afficheMessageConsole("les catégories ont déjà été ajouté !");
}else{
    foreach (["Location", "Recherche", "A vendre", "Service offert", "Autre"] as $Description){
        $categorieDAO->addCategorie($Description);
    }
    afficheMessageConsole("Categories ajouté !");
}