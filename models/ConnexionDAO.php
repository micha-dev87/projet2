<?php
    /*
    |----------------------------------------------------------------------------------|
    | Model DAO Connexion
    |----------------------------------------------------------------------------------|
    */

    class ConnexionDAO
    {
        private $db;

        public function __construct()
        {

            $DB = new mysql();
            $this->db = $DB->connexion();

        }

        /*
        |----------------------------------------------------------------------------------|
        | @function : Ajouter une connexion dans la db - "Connexion"
        | @param : l'id de l'utilisateur
        | @return l'id de la connexion
        |----------------------------------------------------------------------------------|
        */
        public function ajouterConnexion($noUtilisateur)
        {


            $date_connexion = date('Y-m-d H:i:s'); // Date et heure actuelles
            $requete = "INSERT INTO connexions (NoUtilisateur, Connexion) VALUES ($noUtilisateur, '$date_connexion')";
            mysqli_query($this->db, $requete);

            // Retourner l'ID de la connexion insérée
            return mysqli_insert_id($this->db);
        }

        /*
        |----------------------------------------------------------------------------------|
        | @function : fermer la connexion
        | @param : l'id de connexion
        | @return : bool
        |----------------------------------------------------------------------------------|
        */
        public function fermerConnexion($noConnexion)
        {
            $date_deconnexion = date('Y-m-d H:i:s'); // Date et heure actuelles
            $requete = "UPDATE connexions 
                    SET Deconnexion = '$date_deconnexion' 
                    WHERE NoConnexion = $noConnexion";
            return mysqli_query($this->db, $requete);
        }

        /*
        |----------------------------------------------------------------------------------|
        | @function : recupérer la dernière connexion de l'user
        | @param : l'id de l'utilisateur
        | @return l'id de la connexion active
        |----------------------------------------------------------------------------------|
        */
        public function recupererDerniereConnexionActive($noUtilisateur)
        {
            $requete = "SELECT NoConnexion FROM connexions 
                    WHERE NoUtilisateur = $noUtilisateur AND Deconnexion IS NULL 
                    ORDER BY NoConnexion DESC LIMIT 1";
            $resultat = mysqli_query($this->db, $requete);

            if ($resultat && mysqli_num_rows($resultat) > 0) {
                $row = mysqli_fetch_assoc($resultat);
                return $row['NoConnexion'];
            }

            return null; // Aucune connexion active trouvée
        }


    }