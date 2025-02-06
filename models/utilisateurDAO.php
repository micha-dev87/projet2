<?php
// models/utilisateurDAO.php


    class UtilisateurDAO
    {

        public $OK;
        private $strTabUser = "utilisateurs";
        private $db;
        private $NbUsers = 0;

        public function __construct($db)
        {


            /* --- Création de l'instance, connexion avec mySQL et sélection de la base de données (RÉUSSITE) --- */
            $this->db = $db;


        }


        /*
         * Insérer un nouvel utilisateur
         */
        public function insererUtilisateur()
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


                // Construire la requête INSERT IGNORE (si nous avons juste 4 parametres)
                if (func_num_args() < 5) {
                    $requete = "INSERT IGNORE INTO $this->strTabUser (Nom, Prenom, Courriel, MotDePasse, Statut, NoEmpl, Creation)
                 VALUES ('$nom', '$prenom', '$email', '$motDePasseHache', 0, $this->NbUsers, '$date_creation');";

                } else {

                    $telMaison = $data[4];
                    $telTravail = $data[5];
                    $telCellulaire = $data[6];
                    $requete = "INSERT IGNORE INTO $this->strTabUser (Nom, Prenom, Courriel, MotDePasse,NoTelMaison, NoTelTravail, NoTelCellulaire, Statut, NoEmpl, Creation)
                 VALUES ('$nom', '$prenom', '$email', '$motDePasseHache', '$telMaison', '$telTravail', '$telCellulaire', 0, $this->NbUsers,'$date_creation' );";
                }


                // Exécuter la requête
                $this->OK = mysqli_query($this->db, $requete);


            } else {
                die("<p class='text-danger'> Le nombre de parametre est incorrect </p>");

            }
            return $this->OK;


        }


        /*
         * Supprimer un utilisateur par ID
         */

        function emailExiste($strEmail)
        {
            // Vérifier si l'adresse courriel existe déjà dans la table
            $requeteVerification = "SELECT COUNT(*) AS count FROM $this->strTabUser WHERE Courriel = '$strEmail'";
            $resultatVerification = mysqli_query($this->db, $requeteVerification);

            if ($resultatVerification) {
                $row = mysqli_fetch_assoc($resultatVerification);
                $this->NbUsers = $row['count'];
                if ($this->NbUsers > 0) {
                    return true;
                }
            } else {
                die("Erreur lors de la vérification de l'adresse courriel : " . mysqli_error($this->db));
            }
            return false;
        }

        /*
         * Mettre à jour un utilisateur par ID
         */

        public
        function supprimerUtilisateur($id)
        {
            // Construire la condition de suppression
            $condition = "NoUtilisateur = $id";

            // Appeler la fonction de suppression
            return $this->db->supprimeEnregistrements($this->strTabUser, $condition);
        }

        /*
        |----------------------------------------------------------------------------------|
        | Verifier que l'adresse mail existe
        |----------------------------------------------------------------------------------|
        */

        public
        function mettreAJourUtilisateur()
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
                $telTravail = $data[5] ?? null; // Optionnel
                $telCellulaire = $data[6] ?? null; // Optionnel


                // Construire la requête UPDATE
                $requete = "UPDATE $this->strTabUser SET 
                    Nom = '$nom', 
                    Prenom = '$prenom', 
                    Courriel = '$email', 
                    NoTelMaison = '$telMaison', 
                    NoTelTravail = " . ($telTravail ? "'$telTravail'" : "NULL") . ", 
                    NoTelCellulaire = " . ($telCellulaire ? "'$telCellulaire'" : "NULL") . " 
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




    }