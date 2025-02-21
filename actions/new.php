<?php
//definition de la classe Utilisateur
class Utilisateur {
    public $id;
    public $nom;
    public $prenom;
    public $email;
    public $statut;
   
    public function __construct($id, $nom, $prenom, $email, $statut)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->statut = $statut;
    }
}
 
class UtilisateurDAO {
    private $db; // Connection de la base de données
 
    public function __construct($db) {
        $this->db = $db;
    }
 
    // iste des utilisateurs avec le nom et prénom
    public function getUtilisateurs() {
        $sql = "SELECT u.id, u.nom, u.prenom, u.email, u.statut FROM utilisateurs u
                JOIN connexions c ON u.id = c.utilisateur_id
                JOIN annonces a ON u.id = a.utilisateur_id
                GROUP BY u.id
                ORDER BY u.nom, u.prenom";
        $result = $this->db->query($sql);
        $utilisateurs = [];
 
        while ($row = $result->fetch_assoc()) {
            $utilisateurs[] = new Utilisateur($row['id'], $row['nom'], $row['prenom'], $row['email'], $row['statut']);
        }
 
        return $utilisateurs;
    }
 
    //  Suppression des données de l' utilisateurs inscrits pour une periode donnée
    public function supprimerUtilisateursInactifs() {
        $threeMonthsAgo = date('Y-m-d', strtotime('-3 months'));
        $sql = "DELETE FROM utilisateurs WHERE statut = 0 AND date_inscription <= '$threeMonthsAgo'";
        return $this->db->query($sql);
    }
}
?>
 
 
<?php
//definition de la classe Utilisateur
class Utilisateur {
    public $id;
    public $nom;
    public $prenom;
    public $email;
    public $statut;
   
    public function __construct($id, $nom, $prenom, $email, $statut)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->statut = $statut;
    }
}
 
class UtilisateurDAO {
    private $db; // Connection de la base de données
 
    public function __construct($db) {
        $this->db = $db;
    }
 
    // iste des utilisateurs avec le nom et prénom
    public function getUtilisateurs() {
        $sql = "SELECT u.id, u.nom, u.prenom, u.email, u.statut FROM utilisateurs u
                JOIN connexions c ON u.id = c.utilisateur_id
                JOIN annonces a ON u.id = a.utilisateur_id
                GROUP BY u.id
                ORDER BY u.nom, u.prenom";
        $result = $this->db->query($sql);
        $utilisateurs = [];
 
        while ($row = $result->fetch_assoc()) {
            $utilisateurs[] = new Utilisateur($row['id'], $row['nom'], $row['prenom'], $row['email'], $row['statut']);
        }
 
        return $utilisateurs;
    }
 
    <?php
    //definition de la classe Utilisateur
    class Utilisateur {
        public $id;
        public $nom;
        public $prenom;
        public $email;
        public $statut;
       
        public function __construct($id, $nom, $prenom, $email, $statut)
        {
            $this->id = $id;
            $this->nom = $nom;
            $this->prenom = $prenom;
            $this->email = $email;
            $this->statut = $statut;
        }
    }
     
    class UtilisateurDAO {
        private $db; // Connection de la base de données
     
        public function __construct($db) {
            $this->db = $db;
        }
     
        // Liste des utilisateurs avec le nom et prénom
        public function getUtilisateurs() {
            $sql = "SELECT u.id, u.nom, u.prenom, u.email, u.statut FROM utilisateurs u
                    JOIN connexions c ON u.id = c.utilisateur_id
                    JOIN annonces a ON u.id = a.utilisateur_id
                    GROUP BY u.id
                    ORDER BY u.nom, u.prenom";
            $result = $this->db->query($sql);
            $utilisateurs = [];
     
            while ($row = $result->fetch_assoc()) {
                $utilisateurs[] = new Utilisateur($row['id'], $row['nom'], $row['prenom'], $row['email'], $row['statut']);
            }
     
            return $utilisateurs;
        }
     
        //  Suppression des données de l' utilisateurs inscrits pour une periode donnée
        public function supprimerUtilisateursInactifs() {
            $threeMonthsAgo = date('Y-m-d', strtotime('-3 months'));
            $sql = "DELETE FROM utilisateurs WHERE statut = 0 AND date_inscription <= '$threeMonthsAgo'";
            return $this->db->query($sql);
        }
    }
    ?>
}
?>
 
 