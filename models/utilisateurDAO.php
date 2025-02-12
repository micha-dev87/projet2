<?php

// models/utilisateurDAO.php


    class UtilisateurDAO
    {
        
        private $db;

        private $OK;

        public function __construct()
        {


            /*
            |----------------------------------------------------------------------------------|
            | connection à la base de données
            |----------------------------------------------------------------------------------|
            */
            $DB = new mysql();
            $this->db = $DB->connexion();


        }


        /*
        |----------------------------------------------------------------------------------|
        | Ajouter un utilisateur
        |----------------------------------------------------------------------------------|
        */
        public function ajouterUtilisateur($utilisateur)
        {
            $date_creation = date('Y-m-d H:i:s');
            $mot_de_passe_hash = password_hash($utilisateur->mot_de_passe, PASSWORD_BCRYPT);
            // Construire la requête INSERT IGNORE
            $requete = "INSERT IGNORE INTO
                   ".TABLE_USERS." ( Nom, Prenom, Courriel, MotDePasse,NoTelMaison, NoTelTravail, NoTelCellulaire, Statut, Creation )
                    VALUES ('$utilisateur->nom', '$utilisateur->prenom', '$utilisateur->courriel', '$mot_de_passe_hash', '$utilisateur->no_tel_maison', '$utilisateur->no_tel_travail',
                    '$utilisateur->no_tel_cellulaire', '$utilisateur->statut', '$date_creation');";


            // Exécuter la requête
            $this->OK = mysqli_query($this->db, $requete);

            if ($this->OK) {
                // Récupérer l'ID généré automatiquement (clé primaire)
                $noEmpl = mysqli_insert_id($this->db);

                // Mettre à jour le champ NoEmpl avec la valeur de la clé primaire
                $requeteUpdate = "UPDATE ".TABLE_USERS." SET NoEmpl = $noEmpl WHERE Noutilisateur = $noEmpl;";
                afficheMessageConsole("Requete Update : $requeteUpdate");

                // Exécuter la mise à jour
                $this->OK = mysqli_query($this->db, $requeteUpdate);

                if (!$this->OK) {
                    afficheMessageConsole("Erreur lors de la mise à jour du NoEmpl", true);
                }
            } else {
                afficheMessageConsole("Erreur lors de l'insertion de l'utilisateur", true);
            }
            return $this->OK;


        }


        /*
        |----------------------------------------------------------------------------------|
        | Verifier que l'adresse mail existe
        |----------------------------------------------------------------------------------|
        */

        function emailExiste($strEmail)
        {
            // Vérifier si l'adresse courriel existe déjà dans la table
            $requeteVerification = "SELECT COUNT(*) AS count FROM ".TABLE_USERS." WHERE Courriel = '$strEmail'";
            $resultatVerification = mysqli_query($this->db, $requeteVerification);

            if ($resultatVerification) {
                $row = mysqli_fetch_assoc($resultatVerification);

                if ($row['count'] > 0) {
                    return true;
                }
            } else {
                die("Erreur lors de la vérification de l'adresse courriel : " . mysqli_error($this->db));
            }
            return false;
        }

        /*
        |----------------------------------------------------------------------------------|
        | Confirmer un utilistaeur
        |----------------------------------------------------------------------------------|
        */

        function confimerUtilisateur($id, $statut)
        {
            // Vérifier si l'adresse courriel existe déjà dans la table
            $requete = "UPDATE ".TABLE_USERS." SET Statut = $statut WHERE NoUtilisateur = '$id'";
            $resultat = mysqli_query($this->db, $requete);

            return $resultat;

        }



        /**
         * Liste des utilisateurs
         * @return array<int, Utilisateur>
         */
        function listerUtilisateurs(): array
        {
            //Select all users
            $requete = "SELECT * FROM ". TABLE_USERS;

            $resultat = mysqli_query($this->db, $requete);
            $listeUtilisateurs = array();
            while ($row = mysqli_fetch_assoc($resultat)) {
                $utilisateur = new Utilisateur($row["Nom"], $row["Prenom"], $row["Courriel"],
                    $row["MotDePasse"], $row["NoTelCellulaire"], $row["NoTelTravail"],
                    $row["NoTelMaison"], $row["Statut"], $row["NoUtilisateur"]);

                array_push($listeUtilisateurs, $utilisateur);

            }
            return $listeUtilisateurs;
        }

        /*
        |----------------------------------------------------------------------------------|
        | Supprimer un Utilisateur
        |----------------------------------------------------------------------------------|
        */

        /**
         * @param $id
         * @return mysqli_result|bool
         */
        public function supprimerUtilisateur($id): mysqli_result|bool
        {
            $requet = "DELETE FROM ".TABLE_USERS." WHERE NoUtilisateur = '$id'";
            $resultat = mysqli_query($this->db, $requet);
            return $resultat;
        }

        /*
        |----------------------------------------------------------------------------------|
        | Mettre à jour l'utilisateur
        |----------------------------------------------------------------------------------|
        */

        public function mettreAJourUtilisateur($utilisateur)
        {


            $dateModification = date('Y-m-d H:i:s');


            // Construire la requête UPDATE
            $requete = "UPDATE ".TABLE_USERS." SET 
                    Nom = '$utilisateur->nom', 
                    Prenom = '$utilisateur->prenom', 
                    Courriel = '$utilisateur->courriel', 
                    NoTelMaison = '$utilisateur->no_tel_maison', 
                    NoTelTravail =  '$utilisateur->no_tel_travail', , 
                    NoTelCellulaire = '$utilisateur->no_tel_cellulaire',
                    Modification = '$dateModification'
                     
                    WHERE NoUtilisateur = $utilisateur->NoUtilisateur;";

            // Exécuter la requête
            return mysqli_query($this->db, $requete);


        }


        /*
        |----------------------------------------------------------------------------------|
        | @function : Authentification de l'utilisateur
        | @param : l'adresse courriel et mot de passe
        | @return : []$utilisateur ou String - message d'erreur
        |----------------------------------------------------------------------------------|
        */
        public function authentifierUtilisateur($Courriel, $MotDePasse)
        {
            $requete = "SELECT * FROM ".TABLE_USERS." WHERE Courriel = '$Courriel'";
            $resultat = mysqli_query($this->db, $requete);

            if ($resultat && mysqli_num_rows($resultat) > 0) {
                $utilisateur = mysqli_fetch_assoc($resultat);

                // Vérifier le mot de passe
                if (password_verify($MotDePasse, $utilisateur['MotDePasse'])) {
                    // Vérifier si l'utilisateur est confirmé
                    if ($utilisateur['Statut'] == 0) {
                        return "Votre compte n'a pas encore été confirmé. Veuillez vérifier votre courriel.";
                    }

                    // Incrémenter le nombre de connexions
                    $this->incrementerNbConnexions($utilisateur['NoUtilisateur']);

                    // Ajouter une nouvelle connexion dans la table connexions
                    $connexionDAO = new ConnexionDAO();
                    $noConnexion = $connexionDAO->ajouterConnexion($utilisateur['NoUtilisateur']);
                    // Stocker l'ID de connexion dans la session
                    $_SESSION['no_connexion'] = $noConnexion;
                    return $utilisateur; // Retourner les informations de l'utilisateur
                }else{
                    afficheMessageConsole("Mot de passe incorrect, dans la base: ".$utilisateur['MotDePasse']."  # Mot de passe : ".$MotDePasse);
                    return "Le mot de passe est incorrect.";
                }
            }

            return "Les informations de connexion sont incorrectes.";
        }

        /*
        |----------------------------------------------------------------------------------|
        | @function : Incrementer le nombre de connexion dans la table user
        | @param : l'id de l'utilisateur
        | @return : void
        |----------------------------------------------------------------------------------|
        */
        private function incrementerNbConnexions($noUtilisateur)
        {
            $requete = "UPDATE ".TABLE_USERS." SET NbConnexions = NbConnexions + 1 WHERE NoUtilisateur = $noUtilisateur";
            mysqli_query($this->db, $requete);
        }

        /*
        |----------------------------------------------------------------------------------|
        | Déconnecter l'utilisateur
        |----------------------------------------------------------------------------------|
        */
        public function deconnecterUtilisateur()
        {
            // Vérifier si l'utilisateur est connecté
            if (isset($_SESSION['no_connexion'])) {
                $noConnexion = $_SESSION['no_connexion'];


                // Fermer la connexion
                $connexionDAO = new ConnexionDAO();

                 if($connexionDAO->fermerConnexion($noConnexion))
                // Détruire la session
                session_destroy();
                 else
                        afficheMessageConsole("Erreur lors de la fermeture de la connexion", true);
            }

        }
        /*
        |----------------------------------------------------------------------------------|
        | @function : details d'un utilisateur
        | @param : l'id de l'utilisateur
        | @return l'object utilisateur ou message erreur
        |----------------------------------------------------------------------------------|
        */

        public function utilisateurDetail($noUtilisateur){

            $requete = "SELECT * FROM ".TABLE_USERS." WHERE NoUtilisateur = $noUtilisateur";
            $this->OK = mysqli_query($this->db, $requete);
            if ($this->OK && mysqli_num_rows($this->OK) > 0) {
                $row = mysqli_fetch_assoc($this->OK);
                return new Utilisateur($row['Nom'], $row['Prenom'], $row['Courriel'], $row['MotDePasse'], $row['NoTelCellulaire'], $row['NoTelTravail'], $row['NoTelMaison']);
            }

            return "Aucun Utilisateur Trouvé!";
        }

    }