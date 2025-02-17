<?php
// models/AnnonceDAO.php

class AnnonceDAO
{
    public $db;

    public function __construct()
    {
        $DB = new mysql();
        $this->db = $DB->connexion();
    }


    // Changer l'état d'une annonce
    public function changerEtatAnnonce($id, $etat)
    {
        $requete = "UPDATE annonces SET Etat = $etat WHERE NoAnnonce = $id";
        return mysqli_query($this->db, $requete);
    }

    /*
         * Lister toutes les annonces
         * /!\ Attention, cette méthode peut être très gourmande en ressources
         */
    public function listerAnnonces(
        $offset = 0,
        $limit = 10,
        $recherche=null,
        $categorie=null,
        $dateDebut=null,
        $dateFin=null,
        $tri=null
    ) {
        $conditions = "WHERE a.Etat = 1";
        if ($recherche) {
            $conditions .= " AND a.DescriptionAbregee LIKE '%" . mysqli_real_escape_string($this->db, $recherche) . "%'";
            $conditions .= " OR a.DescriptionComplete LIKE '%" . mysqli_real_escape_string($this->db, $recherche) . "%'";
            $conditions.= " OR c.Description LIKE '%" . mysqli_real_escape_string($this->db, $recherche) . "%'";
            $conditions .= " OR a.Prix LIKE '%" . mysqli_real_escape_string($this->db, $recherche) . "%'";
            

        }
        if ($categorie) {
            $conditions .= " AND a.Categorie = '" . mysqli_real_escape_string($this->db, $categorie) . "'";
        }
        if ($dateDebut) {
            $conditions .= " AND a.Parution >= '" . mysqli_real_escape_string($this->db, $dateDebut) . "'";
        }
        if ($dateFin) {
            $conditions .= " AND a.Parution <= '" . mysqli_real_escape_string($this->db, $dateFin) . "'";
        }
        if ($tri) {
           // trie par rapport au option
           switch($tri){
            case "1":
                //trie date parution croissant
                $conditions .= " ORDER BY a.Parution ASC";
                break;
            case "2":
                //trie date parution decroissant
                $conditions .= " ORDER BY a.Parution DESC";
                break;
            case "3":
                //trie auteur croissant
                $conditions .= " ORDER BY u.Nom ASC";
                $conditions .= " ORDER BY u.Prenom ASC";
                break;
            case "4":
                //trie auteur decroissant
                $conditions .= " ORDER BY u.Nom DESC";
                $conditions .= " ORDER BY u.Prenom DESC";
                break;
            case "5":
                //trie categorie croissant
                $conditions .= " ORDER BY c.Description ASC";
                break;
            case "6":
                //trie categorie decroissant
                $conditions .= " ORDER BY c.Description DESC";
                break;
           }
        } else {
            $conditions .= " ORDER BY a.Parution DESC";
        }

        $requete = "SELECT a.*, c.Description AS Categorie 
                FROM annonces a 
                JOIN categories c ON a.Categorie = c.NoCategorie
                JOIN utilisateurs u ON a.NoUtilisateur = u.NoUtilisateur
                $conditions
                LIMIT $offset, $limit";
        $resultat = mysqli_query($this->db, $requete);
        $annonces = [];
        if ($resultat && mysqli_num_rows($resultat) > 0) {

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

    //counter le nombre d'annonces
    public function getAnnoncesTotal($all = false)
    {
        $requete = "SELECT COUNT(*) AS total FROM annonces ";
        if (!$all) {
            $requete .= "WHERE Etat = 1";
        }
        $resultat = mysqli_query($this->db, $requete);
        $row = mysqli_fetch_assoc($resultat);
        return $row['total'];
    }


    // Update annonce by id


    public function updateAnnonce($annonce)
    {
        $requete = "UPDATE annonces SET 
                        DescriptionAbregee = '$annonce->DescriptionA', 
                        DescriptionComplete = '$annonce->Description', 
                        Prix = $annonce->Prix, 
                        Parution = '$annonce->Parution', 
                        Etat = $annonce->Etat, 
                        Photo = '$annonce->Photo', 
                        Categorie = $annonce->Categorie 
                    WHERE NoAnnonce = $annonce->NoAnnonce";

        return mysqli_query($this->db, $requete);
    }

    /*
         * Lister les annonces pour l'utilisateur connecté
         */
    public function listerAnnoncesPourUtilisateur($idUtilisateur)
    {
        $requete = "SELECT a.*, c.Description AS Categorie 
                        FROM annonces a 
                        JOIN categories c ON a.Categorie = c.NoCategorie
                        WHERE a.NoUtilisateur = $idUtilisateur
                        ORDER BY a.Parution DESC";
        $resultat = mysqli_query($this->db, $requete);
        $annonces = [];
        if ($resultat && mysqli_num_rows($resultat) > 0) {
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
        // Préparer la requête avec un paramètre préparé
        $requete = "SELECT a.*, c.Description AS Categorie 
                        FROM annonces a 
                        JOIN categories c ON a.Categorie = c.NoCategorie
                        WHERE a.NoAnnonce = ?";

        $stmt = mysqli_prepare($this->db, $requete);

        if (!$stmt) {
            return null;
        }

        // Lier le paramètre
        mysqli_stmt_bind_param($stmt, "i", $id);

        // Exécuter la requête
        if (!mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            return null;
        }

        $resultat = mysqli_stmt_get_result($stmt);

        if ($resultat && $row = mysqli_fetch_assoc($resultat)) {
            $annonce = new Annonce(
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

            mysqli_stmt_close($stmt);
            return $annonce;
        }

        mysqli_stmt_close($stmt);
        return null;
    }

    /*
         * Ajouter une nouvelle annonce
         */

    public function ajouterAnnonce($annonce)
    {


        $requete = "INSERT INTO annonces (NoUtilisateur, DescriptionAbregee, DescriptionComplete, 
            Prix, Parution, Etat, Photo, Categorie) 
                VALUES ('$annonce->NoUtilisateur', '$annonce->DescriptionA', '$annonce->Description',
                        $annonce->Prix, '$annonce->Parution', $annonce->Etat, '$annonce->Photo', $annonce->Categorie)";


       


        $resultat = mysqli_query($this->db, $requete);

      

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
