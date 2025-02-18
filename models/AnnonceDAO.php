<?php
// models/AnnonceDAO.php

class AnnonceDAO
{
    private $db;
    public $sql;

    public function __construct()
    {
        $DB = new mysql();
        $this->db = $DB->connexion();
    }


    // Changer l'état d'une annonce
    public function changerEtatAnnonce($id, $etat)
    {
        $this->sql = "UPDATE annonces SET Etat = $etat WHERE NoAnnonce = $id";
        return mysqli_query($this->db, $this->sql);
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
        $tri=null,
        $id_user=null
    ) {
        $conditions = $id_user ? "WHERE 1=1" : "WHERE a.Etat = 1";
        //Filtre utilisateur
        if ($id_user) {
            $conditions .= " AND a.NoUtilisateur = " . mysqli_real_escape_string($this->db, $id_user);
        }

        // Filtre recherche
        if ($recherche) {
            $conditions .= " AND a.DescriptionAbregee LIKE '%" . mysqli_real_escape_string($this->db, $recherche) . "%'";
            $conditions .= " OR a.DescriptionComplete LIKE '%" . mysqli_real_escape_string($this->db, $recherche) . "%'";
            $conditions.= " OR c.Description LIKE '%" . mysqli_real_escape_string($this->db, $recherche) . "%'";
            $conditions .= " OR a.Prix LIKE '%" . mysqli_real_escape_string($this->db, $recherche) . "%'";
            

        }
        // Filtre catégorie
        if ($categorie) {
            $conditions .= " AND a.Categorie = '" . mysqli_real_escape_string($this->db, $categorie) . "'";
        }
        // Filtre date
        if ($dateDebut) {
            $conditions .= " AND a.Parution >= '" . mysqli_real_escape_string($this->db, $dateDebut) . "'";
        }
        // Filtre date de fin
        if ($dateFin) {
            $conditions .= " AND a.Parution <= '" . mysqli_real_escape_string($this->db, $dateFin) . "'";
        }
        if ($tri) {
           // trie par rapport aux options
           switch($tri){
            case "date_asc":
                //trie date parution croissant
                $conditions .= " ORDER BY a.Parution ASC";
                break;
            case "date_desc":
                //trie date parution decroissant
                $conditions .= " ORDER BY a.Parution DESC";
                break;
            case "auteur_asc":
                //trie auteur croissant
                $conditions .= " ORDER BY u.Nom ASC, u.Prenom ASC";
                break;
            case "auteur_desc":
                //trie auteur decroissant
                $conditions .= " ORDER BY u.Nom DESC, u.Prenom DESC";
                break;
            case "categorie_asc":
                //trie categorie croissant
                $conditions .= " ORDER BY c.Description ASC";
                break;
            case "categorie_desc":
                //trie categorie decroissant
                $conditions .= " ORDER BY c.Description DESC";
                break;
           }
        } else {
            $conditions .= " ORDER BY a.Parution DESC";
        }

        $this->sql = "SELECT a.*, c.*, u.*
                FROM annonces a 
                JOIN categories c ON a.Categorie = c.NoCategorie
                JOIN utilisateurs u ON a.NoUtilisateur = u.NoUtilisateur
                $conditions
                LIMIT $offset, $limit";
        $resultat = mysqli_query($this->db, $this->sql);
        $annonces = [];
        if ($resultat && mysqli_num_rows($resultat) > 0) {

            while ($row = mysqli_fetch_assoc($resultat)) {
                
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

                //recuperer : nom categoire, prenom et nom auteur et son adresse mail
                $annonce->NomCategories = $row['Description'];
                $annonce->NomAuteur = $row['Nom'];
                $annonce->PrenomAuteur = $row['Prenom'];
                $annonce->CourrielAuteur = $row['Courriel'];
                $annonce->autresInfos = $row['AutresInfos'];
                $annonces[] = $annonce;
                }
        }
        return $annonces;
    }

    // Compter le nombre d'annonces
    public function getAnnoncesTotal(
        $recherche = null, $categorie = null, $dateDebut = null, $dateFin = null, $id_user = null)     
    {
        // Construction de la requête de base
        $sql = "SELECT COUNT(DISTINCT a.NoAnnonce) as total 
                FROM annonces a 
                JOIN categories c ON a.Categorie = c.NoCategorie
                JOIN utilisateurs u ON a.NoUtilisateur = u.NoUtilisateur
                WHERE a.Parution IS NOT NULL";
    
        $params = [];
        $types = "";
    
        // Filtre utilisateur
        if ($id_user) {
            $sql .= " AND a.NoUtilisateur = ?";
            $params[] = $id_user;
            $types .= "i";
        }

    
    
        // Filtre recherche avec conditions regroupées
        if ($recherche) {
            $sql .= " AND (a.DescriptionAbregee LIKE ? 
                       OR a.DescriptionComplete LIKE ? 
                       OR c.Description LIKE ?
                       OR a.Prix LIKE ?)";
            $searchTerm = "%$recherche%";
            $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
            $types .= "ssss";
        }
    
        // Filtre catégorie
        if ($categorie) {
            $sql .= " AND a.Categorie = ?";
            $params[] = $categorie;
            $types .= "i";
        }
    
        // Filtres date
        if ($dateDebut) {
            $sql .= " AND a.Parution >= ?";
            $params[] = $dateDebut;
            $types .= "s";
        }
        if ($dateFin) {
            $sql .= " AND a.Parution <= ?";
            $params[] = $dateFin;
            $types .= "s";
        }

    
    
        // Préparation et exécution de la requête
        $stmt = mysqli_prepare($this->db, $sql);
        
        if (!$stmt) {
            return 0;
        }
    
        if (!empty($params)) {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }
    
        if (!mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            return 0;
        }
    
        $resultat = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($resultat);
        mysqli_stmt_close($stmt);
    
        return (int)($row['total'] ?? 0);
    }
  

    // Update annonce by id


    public function updateAnnonce($annonce)
    {
        $this->sql = "UPDATE annonces SET 
                        DescriptionAbregee = '$annonce->DescriptionA', 
                        DescriptionComplete = '$annonce->Description', 
                        Prix = $annonce->Prix, 
                        Parution = '$annonce->Parution', 
                        Etat = $annonce->Etat, 
                        Photo = '$annonce->Photo', 
                        Categorie = $annonce->Categorie 
                    WHERE NoAnnonce = $annonce->NoAnnonce";

        return mysqli_query($this->db, $this->sql);
    }



    /*
         * Récupérer une annonce par son ID
         */

    public function getAnnonceById($id)
    {
        // Préparer la requête avec un paramètre préparé
        $this->sql = "SELECT a.*, c.Description AS Categorie 
                        FROM annonces a 
                        JOIN categories c ON a.Categorie = c.NoCategorie
                        WHERE a.NoAnnonce = ?";

        $stmt = mysqli_prepare($this->db, $this->sql);

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


        $this->sql = "INSERT INTO annonces (NoUtilisateur, DescriptionAbregee, DescriptionComplete, 
            Prix, Parution, Etat, Photo, Categorie) 
                VALUES ('$annonce->NoUtilisateur', '$annonce->DescriptionA', '$annonce->Description',
                        $annonce->Prix, '$annonce->Parution', $annonce->Etat, '$annonce->Photo', $annonce->Categorie)";


       


        $resultat = mysqli_query($this->db, $this->sql);

      

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
        $this->sql = "DELETE FROM annonces WHERE NoAnnonce = $id";
        return mysqli_query($this->db, $this->sql);
    }
}
