<?php
// Vérifier si l'utilisateur est connecté
    if (!isset($_SESSION['utilisateur'])) {

      echo '<script type="text/javascript">
              window.location.href = "' . lien("login") . '";
            </script>';
      exit();
    }



    $erreur = ""; // Message d'erreur
    $succes = ""; // Message de succès
    $btn_add_annonce = "Ajouter une annonce";
    afficheMessageConsole("NoUtilisateur : ". NO_UTILISATEUR);
    // Si le formulaire est soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        try {

            // Récupérer les données du formulaire
            $DescriptionA = trim($_POST['DescriptionA']);
            $Description = trim($_POST['Description']);
            $Prix = floatval($_POST['Prix']);
            $Parution = $_POST['Parution'];
            $Etat = intval($_POST['Etat']);
            $Categorie = intval($_POST['Categorie']);
            $Photo = $_FILES['Photo'];

            // Validation des champs
            if (empty($DescriptionA) || strlen($DescriptionA) < 10 || strlen($DescriptionA) > 200) {
                throw new Exception("La description abrégée doit contenir entre 10 et 200 caractères.");
            }
            if (empty($Description) || strlen($Description) < 20 || strlen($Description) > 1000) {
                throw new Exception("La description complète doit contenir entre 20 et 1000 caractères.");
            }
    
            if (!validateDate($Parution)) {
                throw new Exception("La date de parution n'est pas valide.");
            }
            if (!is_uploaded_file($Photo['tmp_name'])) {
                throw new Exception("Veuillez télécharger une image valide.");
            }
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/bmp'];
            $fileType = mime_content_type($Photo['tmp_name']);
            if (!in_array($fileType, $allowedTypes)) {
                throw new Exception("Le type de fichier n'est pas autorisé. Types acceptés: JPG, PNG, GIF, WEBP, BMP");
            } else {
                afficheMessageConsole("Photo : ". print_r($Photo, true));
            }

            // Gestion de l'upload de la photo
            $uploadDir = __DIR__ . '/../uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $photoName = uniqid('photo_') . '_' . basename($Photo['name']);
            $photoPath = $uploadDir . $photoName;
            move_uploaded_file($Photo['tmp_name'], $photoPath);
            $photoURL = '/uploads/' . $photoName; // Chemin relatif pour la base de données
            afficheMessageConsole("photoURL : ". $photoURL);
            // Créer une nouvelle annonce
            $annonce = new Annonce(
                NO_UTILISATEUR,
                null, // NoAnnonce sera généré automatiquement
                $DescriptionA,
                $Description,
                $Prix,
                $Parution,
                $Etat,
                $photoURL,
                $Categorie
            );

            afficheMessageConsole("Object  annonce : " . print_r($annonce, true));

            // Ajouter l'annonce dans la base de données
            $resultats = $GLOBALS["annonceDAO"]->ajouterAnnonce($annonce);

            if ($resultats) {
                $succes = "L'annonce a bien été ajoutée !";
            } else {
                throw new Exception("Erreur lors de l'ajout de l'annonce.");
            }
        } catch (Exception $e) {
            afficheMessageConsole("Erreur : " . $e->getMessage());
            $erreur = $e->getMessage();
        }
    }

// Inclure la vue
    require_once("vues/ajouter-annonce.vue.php");

    /**
     * Fonction utilitaire pour valider une date
     */
    function validateDate($date, $format = 'Y-m-d') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }