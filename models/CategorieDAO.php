<?php
    //Acces données Categories
Class CategorieDAO {

    private $db;

    public function __construct() {
        $DB = new mysql();
        $this->db = $DB->connexion();
    }


    // Get categories par id
    function getCategorie($NoCategorie){

        $resultat = mysqli_query($this->db, "SELECT * FROM categories where NoCategorie = '$NoCategorie'");
        if($resultat && mysqli_num_rows($resultat) > 0) {

            while ($row = mysqli_fetch_assoc($resultat)) {
                $categories = $row["Description"];
            }

        }
            return $categories;
    }

    // Get all categories
    function getAllCategorie():array  {
        $categories = [];
        $resultat = mysqli_query($this->db, "SELECT * FROM categories");
        if($resultat && mysqli_num_rows($resultat) > 0) {

            while ($row = mysqli_fetch_assoc($resultat)) {
                $categories[] = $row["Description"];
            }

        }
        return $categories;


    }

    //Add Categorie
    function addCategorie($Description):bool {
        $resultat = mysqli_query($this->db, "INSERT INTO categories (Description) VALUES ('$Description')");
        if($resultat && mysqli_affected_rows($this->db)> 0) {
           return true;


        }else{
            return false;
        }

    }

}