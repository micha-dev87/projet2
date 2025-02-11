<?php
    //Acces données Categories
Class CategorieDAO {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }


    // Get categories par id
    function getCategorie($NoCategorie) {
        $resultat = mysqli_query($this->db, "SELECT * FROM categories where NoCategorie = '$NoCategorie'");
        if($resultat && mysqli_num_rows($resultat) > 0) {
            $categories = [];
            while ($row = mysqli_fetch_assoc($resultat)) {
                $categories = $row["Description"];
            }

            return $categories;
        }
    }

    // Get all categories
    function getAllCategorie()  {
        $categories = [];
        $resultat = mysqli_query($this->db, "SELECT * FROM categories");
        if($resultat && mysqli_num_rows($resultat) > 0) {

            while ($row = mysqli_fetch_assoc($resultat)) {
                $categories = $row["Description"];
            }

        }
            return $categories;


    }

    //Add Categorie
    function addCategorie($Description) {
        $resultat = mysqli_query($this->db, "INSERT INTO categories (Description) VALUES ('$Description')");
        if($resultat && mysqli_affected_rows($this->db)> 0) {
           return true;


        }else{
            return false;
        }

    }

}