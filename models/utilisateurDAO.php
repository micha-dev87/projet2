<?php

// models/utilisateurDAO.php


    class UtilisateurDAO
    {

        public $OK;
        private $strTabUser = "utilisateurs";
        private $db;

        public function __construct($db)
        {


            /*
            |----------------------------------------------------------------------------------|
            | connection à la base de données
            |----------------------------------------------------------------------------------|
            */
            $this->db = $db;


        }


        /*
        |----------------------------------------------------------------------------------|
        | Ajouter un utilisateur
        |----------------------------------------------------------------------------------|
        */
        public function ajouterUtilisateur()
        {
            $data = [];
            if (func_num_args() >= 4) {
                for ($i = 0; $i < func_num_args(); $i++) {
                    $valeur = func_get_arg($i);
                    if (is_string($valeur)) {
                        $valeur = mysqli_real_escape_string($this->db, $valeur);
                    } else if (is_null($valeur)) {
                        $valeur = "";
                    }

                    $data[$i] = $valeur;
                }

                $nom = $data[0];
                $prenom = $data[1];
                $email = $data[2];
                $date_creation = aujourdhui();

                // Hacher le mot de passe pour plus de sécurité
                $motDePasseHache = password_hash($data[3], PASSWORD_DEFAULT);
                //Optionnel
                $telMaison = $data[4] ?? "";
                $telTravail = $data[5] ?? "";
                $telCellulaire = $data[6] ?? "";
                $isAdmin = $data[7] ?? false;
                // Construire la requête INSERT IGNORE
                $requete = "INSERT IGNORE INTO
                    $this->strTabUser (
                    Nom, Prenom, Courriel, MotDePasse,NoTelMaison, NoTelTravail, NoTelCellulaire, Statut, Creation
                    )
                    VALUES ('$nom', '$prenom', '$email', '$motDePasseHache', '$telMaison', '$telTravail', '$telCellulaire'," . ($isAdmin ? 1 : 0) . ",'$date_creation' );";
                afficheMessageConsole("Requete : $requete");


                // Exécuter la requête
                $this->OK = mysqli_query($this->db, $requete);

                if ($this->OK) {
                    // Récupérer l'ID généré automatiquement (clé primaire)
                    $noEmpl = mysqli_insert_id($this->db);

                    // Mettre à jour le champ NoEmpl avec la valeur de la clé primaire
                    $requeteUpdate = "UPDATE $this->strTabUser SET NoEmpl = $noEmpl WHERE Noutilisateur = $noEmpl;";
                    afficheMessageConsole("Requete Update : $requeteUpdate");

                    // Exécuter la mise à jour
                    $this->OK = mysqli_query($this->db, $requeteUpdate);

                    if (!$this->OK) {
                        afficheMessageConsole("Erreur lors de la mise à jour du NoEmpl", true);
                    }
                } else {
                    afficheMessageConsole("Erreur lors de l'insertion de l'utilisateur", true);
                }


            } else {
                $this->OK = false;
                afficheMessageConsole("Le nombre de parametre est incorrect ", true);
                die();

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
            $requeteVerification = "SELECT COUNT(*) AS count FROM $this->strTabUser WHERE Courriel = '$strEmail'";
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

        function confimerUtilisateur($strEmail, $intStatut)
        {
            // Vérifier si l'adresse courriel existe déjà dans la table
            $requete = "UPDATE $this->strTabUser SET Statut = $intStatut WHERE Courriel = '$strEmail'";
            $resultat = mysqli_query($this->db, $requete);

            return $resultat;

        }

        /*
        |----------------------------------------------------------------------------------|
        | Supprimer un Utilisateur
        |----------------------------------------------------------------------------------|
        */


        public function supprimerUtilisateur($id)
        {
            // Construire la condition de suppression
            $condition = "NoUtilisateur = $id";

            // Appeler la fonction de suppression
            return $this->db->supprimeEnregistrements($this->strTabUser, $condition);
        }

        /*
        |----------------------------------------------------------------------------------|
        | Mettre à jour l'utilisateur
        |----------------------------------------------------------------------------------|
        */

        public function mettreAJourUtilisateur()
        {
            // Initialiser un tableau pour stocker les données
            $data = [];
            if (func_num_args() >= 5) { // Au minimum : NoUtilisateur + 4 champs modifiables
                for ($i = 0; $i < func_num_args(); $i++) {
                    $valeur = func_get_arg($i);
                    if (is_string($valeur)) {
                        $valeur = mysqli_real_escape_string($this->db, $valeur); // Échapper les chaînes pour éviter les injections SQL
                    } elseif (is_null($valeur)) {
                        $valeur = ""; // Gérer les valeurs NULL
                    }
                    $data[$i] = $valeur;
                }

                // Récupérer les données passées en paramètres
                $noUtilisateur = $data[0]; // Identifiant unique de l'utilisateur à mettre à jour
                $nom = $data[1];
                $prenom = $data[2];
                $email = $data[3];
                $telMaison = $data[4];
                $telTravail = $data[5] ?? ""; // Optionnel
                $telCellulaire = $data[6] ?? ""; // Optionnel
                $dateModification = aujourdhui();


                // Construire la requête UPDATE
                $requete = "UPDATE $this->strTabUser SET 
                    Nom = '$nom', 
                    Prenom = '$prenom', 
                    Courriel = '$email', 
                    NoTelMaison = '$telMaison', 
                    NoTelTravail =  '$telTravail' , 
                    NoTelCellulaire = '$telCellulaire',
                    Modification = '$dateModification'
                     
                    WHERE NoUtilisateur = $noUtilisateur";

                // Exécuter la requête
                $this->OK = mysqli_query($this->db, $requete);

                // Vérifier si la mise à jour a réussi
                if (!$this->OK) {
                    die("<p class='text-danger'>Erreur lors de la mise à jour : " . mysqli_error($this->db) . "</p>");
                }

            } else {
                die("<p class='text-danger'>Le nombre de paramètres est incorrect.</p>");
            }

            return $this->OK;
        }


        /*
        |----------------------------------------------------------------------------------|
        | @function : Authentification de l'utilisateur
        | @param : l'adresse courriel et mot de passe
        | @return : []$utilisateur ou String - message d'erreur
        |----------------------------------------------------------------------------------|
        */
        public function authentifierUtilisateur($courriel, $mot_de_passe)
        {
            $requete = "SELECT * FROM utilisateurs WHERE Courriel = '$courriel'";
            $resultat = mysqli_query($this->db, $requete);

            if ($resultat && mysqli_num_rows($resultat) > 0) {
                $utilisateur = mysqli_fetch_assoc($resultat);

                // Vérifier le mot de passe
                if (password_verify($mot_de_passe, $utilisateur['MotDePasse'])) {
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
            $requete = "UPDATE utilisateurs SET NbConnexions = NbConnexions + 1 WHERE NoUtilisateur = $noUtilisateur";
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
                $connexionDAO->fermerConnexion($noConnexion);

                // Détruire la session
                session_destroy();
            }

        }

    }