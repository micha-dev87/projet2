<?php
// models/AnnonceDAO.php

    class AnnonceDAO
    {
        private $db;

        public function __construct()
        {
            $DB = new mysql();
            $this->db = $DB->connexion();
        }

        /*
         * Lister toutes les annonces
         */
        public function listerAnnonces()
        {
            $requete = "SELECT a.*, c.Description AS Categorie 
                    FROM annonces a 
                    JOIN categories c ON a.Categorie = c.NoCategorie";
            $resultat = mysqli_query($this->db, $requete);
            $annonces = [];
            if (mysqli_num_rows($resultat) > 0 && $resultat) {

                while ($row = mysqli_fetch_assoc($resultat)) {
                    $annonces[] = new Annonce(
                        $row['NoUtilisateur'],
                        $row['NoAnnonce'],
                        $row['DescriptionAbregee'],
                        $row['DescriptionComplete'],
                        $row['Prix'],
                        $row['Parution'],
                        $row['Etat'],
                        $row['Photo'],
                        $row['Categorie']
                    );
                }
            }
            return $annonces;
        }

        /*
         * Récupérer une annonce par son ID
         */
        public function getAnnonceById($id)
        {
            $requete = "SELECT a.*, c.Description AS Categorie 
                    FROM annonces a 
                    JOIN categories c ON a.Categorie = c.NoCategorie
                    WHERE a.NoAnnonce = $id";
            $resultat = mysqli_query($this->db, $requete);

            if (mysqli_num_rows($resultat) > 0 && $resultat) {
                $annonces = [];
                while ($row = mysqli_fetch_assoc($resultat)) {
                    $annonces[] = new Annonce(
                        $row['NoUtilisateur'],
                        $row['NoAnnonce'],
                        $row['DescriptionAbregee'],
                        $row['DescriptionComplete'],
                        $row['Prix'],
                        $row['Parution'],
                        $row['Etat'],
                        $row['Photo'],
                        $row['Categorie']
                    );
                }
                return $annonces;
            } else {
                return null;
            }

        }

        /*
         * Ajouter une nouvelle annonce
         */

        public function ajouterAnnonce($annonce)
        {


            $requete = "INSERT INTO annonces (NoUtilisateu, DescriptionAbregee, DescriptionComplete, 
            Prix, Parution, Etat, Photo, Categorie) 
                VALUES ('$annonce->NoUtilisateur', '$annonce->DescriptionA', '$annonce->Description',
                        $annonce->Prix, '$annonce->Parution', $annonce->Etat, '$annonce->Photo', $annonce->Categorie)";


            afficheMessageConsole("Requete : " . $requete);
            afficheMessageConsole("DB : " . print_r($this->db, true));


            $resultat = mysqli_query($this->db, $requete);

            afficheMessageConsole("Resultat : " . print_r($resultat, true));

            if (mysqli_error($this->db)) {
                return false;
            }
            return $resultat;
        }

        /*
         * Supprimer une annonce
         */
        public function supprimerAnnonce($id)
        {
            $requete = "DELETE FROM annonces WHERE NoAnnonce = $id";
            return mysqli_query($this->db, $requete);
        }
    }