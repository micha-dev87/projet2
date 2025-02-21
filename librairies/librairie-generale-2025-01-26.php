<?php
    /*
    |--------------------------------------------------------------------------------|
    | chargeFichierEnMemoire() (2018-03-13; 2019-03-12; 2020-03-22)
    | Réf.: http://php.net/manual/fr/function.count.php
    | http://ca.php.net/manual/fr/function.file.php
    | http://php.net/manual/fr/function.file-get-contents.php
    | http://ca.php.net/manual/fr/function.str-replace.php
    | http://php.net/manual/fr/function.strlen.php
    |--------------------------------------------------------------------------------|
    */
    function chargeFichierEnMemoire($strNomFichier,
                                    &$tContenu, &$intNbLignes,
                                    &$strContenu, &$intTaille,
                                    &$strContenuHTML) {
        /* Récupère toutes les lignes et les entrepose dans un tableau
        Retrait de tous les CR et LF
        Récupère le nombre de lignes */
        $tContenu = file($strNomFichier);
        $tContenu = str_replace("\n", "", str_replace("\r", "", $tContenu));
        $intNbLignes = count($tContenu);
        /* Récupère toutes les lignes et les entrepose dans une chaîne */
        $strContenu = file_get_contents($strNomFichier);
        $intTaille = strlen($strContenu);
        /* Entrepose la chaîne résultante dans une autre après l'avoir XHTMLisé ! */
        $strContenuHTML = str_replace("\n\r", "<br />",
            str_replace("\r\n", "<br />", $strContenu));
    }
    /*
    |--------------------------------------------------------------------------------|
    | compteLignesFichier() (2018-03-13; 2019-03-12; 2020-03-22)
    | Réf.: http://ca.php.net/manual/fr/function.count.php
    | http://ca.php.net/manual/fr/function.file.php
    |--------------------------------------------------------------------------------|
    */
    function compteLignesFichier($strNomFichier) {
        $intNbLignes = -1;
        if (fichierExiste($strNomFichier)) {
            $intNbLignes = count(file($strNomFichier));
        }
        return $intNbLignes;
    }
    /*
    |--------------------------------------------------------------------------------|
    | detecteFinFichier() (2018-03-13; 2019-03-12; 2020-03-22)
    | Réf.: http://php.net/manual/fr/function.feof.php
    |--------------------------------------------------------------------------------|
    */
    function detecteFinFichier($fp) {
        $binVerdict = true;
        if ($fp) {
            $binVerdict = feof($fp);
        }
        return $binVerdict;
    }
    /*
    |--------------------------------------------------------------------------------|
    | ecritLigneDansFichier() (2018-03-13; 2019-03-12; 2020-03-22)
    | Réf.: http://php.net/manual/fr/function.fputs.php
    | http://php.net/manual/fr/function.gettype.php
    |--------------------------------------------------------------------------------|
    */
    function ecritLigneDansFichier($fp, $strLigneCourante,
                                   $binSaut_intNbLignesSaut = false) {
        $binVerdict = fputs($fp, $strLigneCourante);
        if ($binVerdict) {
            switch (gettype($binSaut_intNbLignesSaut)) {
                case "integer" :
                    for ($i=1; $i<=$binSaut_intNbLignesSaut && $binVerdict; $i++) {
                        $binVerdict = fputs($fp, "\r\n");
                    }
                    break;
                case "boolean" :
                    if ($binSaut_intNbLignesSaut) {
                        $binVerdict = fputs($fp, "\r\n");
                    }
            }
        }
        return $binVerdict;
    }
    /*
    |--------------------------------------------------------------------------------|
    | fermeFichier() (2018-03-13; 2019-03-12; 2020-03-22)
    | Réf.: http://ca.php.net/manual/fr/function.fclose.php
    |--------------------------------------------------------------------------------|
    */
    function fermeFichier($fp) {
        $binVerdict = false;
        if ($fp) {
            $binVerdict = fclose($fp);
        }
        return $binVerdict;
    }
    /*
    |--------------------------------------------------------------------------------|
    | fichierExiste() (2018-03-13; 2019-03-12; 2020-03-22)
    | Réf.: http://ca.php.net/manual/fr/function.file-exists.php
    |--------------------------------------------------------------------------------|
    */
    function fichierExiste($strNomFichier) {
        return file_exists($strNomFichier);
    }

    /*
|--------------------------------------------------------------------------------|
| litLigneDansFichier() (2018-03-13; 2019-03-12; 2020-03-22)
| Réf.: http://ca.php.net/manual/fr/function.fgets.php
| http://ca.php.net/manual/fr/function.str-replace.php
|--------------------------------------------------------------------------------|
*/
    function litLigneDansFichier($fp) {
        return str_replace("\n", "", str_replace("\r", "", fgets($fp)));
    }
    /*
    |--------------------------------------------------------------------------------|
    | ouvreFichier() (2018-03-13; 2019-03-12; 2020-03-22)
    | Réf.: http://ca.php.net/manual/fr/function.fopen.php
    | http://ca.php.net/manual/fr/function.strtoupper.php
    |--------------------------------------------------------------------------------|
    */
    function ouvreFichier($strNomFichier, $strMode="L") {
        switch (strtoupper($strMode)) {
            case "A" :
            case "A" :
                $strMode = "a";
                break;
            case "E" :
            case "W" :
                $strMode = "w";
                break;
            case "L" :
            case "R" :
                $strMode = "r";
                break;
        }
        $fp = fopen($strNomFichier, $strMode);
        return $fp;
    }

    /*
|-------------------------------------------------------------------------------------|
| detecteServeur
|-------------------------------------------------------------------------------------|
*/
    function detecteServeur(&$strMonIP, &$strIPServeur, &$strNomServeur, &$strInfosSensibles) {
        $strMonIP = $_SERVER["REMOTE_ADDR"];
        $strIPServeur = $_SERVER["SERVER_ADDR"];
        $strNomServeur = $_SERVER["SERVER_NAME"];
        $strInfosSensibles = str_replace(".", "-", $strNomServeur) . ".php";
    }
/*
|
|
| getParametre()
| Récupère la valeur d'une variable $_POST, $_GET, $_SERVER, ou $_SESSION.
|
|
*/
    function getParametre($strNom, $strType = 'POST') {
        // Convertir le type en majuscules pour éviter les erreurs de casse
        $strType = strtoupper($strType);

        // Récupérer la valeur en fonction du type spécifié
        switch ($strType) {
            case 'POST':
                return isset($_POST[$strNom]) ? $_POST[$strNom] : null;
            case 'GET':
                return isset($_GET[$strNom]) ? $_GET[$strNom] : null;
            case 'SERVER':
                return isset($_SERVER[$strNom]) ? $_SERVER[$strNom] : null;
            case 'SESSION':
                return isset($_SESSION[$strNom]) ? $_SESSION[$strNom] : null;
            default:
                // Si le type n'est pas spécifié ou invalide, chercher dans $_POST puis $_GET
                if (isset($_POST[$strNom])) {
                    return $_POST[$strNom];
                } elseif (isset($_GET[$strNom])) {
                    return $_GET[$strNom];
                } else {
                    return null; // Retourner null si la variable n'existe pas
                }
        }
    }


/*
|
|
| afficheMessageConsole()
| Affiche un message dans la console du navigateur.
| Si c'est une erreur, le texte est rouge ; sinon, il est vert.
|
|
*/
    function afficheMessageConsole($message, $estErreur = false) {
        // Définir la couleur en fonction du type de message
        $couleur = $estErreur ? 'red' : 'green';

        // Générer le script JavaScript pour afficher le message dans la console
        echo "<script>
            console.log(` %c$message` , ` color: $couleur;` );
          </script>";
    }

/*
 *  fonction pour inclure tous les fichiers d'un dossiers
 */
    function inclureFichiersDossier($dossier, $typeInclusion = 'include')
    {
        // Vérifier si le dossier existe
        if (!is_dir($dossier)) {
            return "Erreur : Le dossier '$dossier' n'existe pas.";
        }

        // Récupérer tous les fichiers .php dans le dossier
        $fichiers_php = glob($dossier . '/*.php');

        // Initialiser un tableau pour stocker les résultats
        $resultats = [];

        // Parcourir chaque fichier trouvé
        foreach ($fichiers_php as $fichier) {
            if (file_exists($fichier)) {
                // Inclure ou requérir le fichier en fonction du type spécifié
                try {
                    if ($typeInclusion === 'require') {
                        require_once $fichier;
                        $resultats[] = "Fichier '$fichier' inclus avec require.";
                    } else {
                        include $fichier;
                        $resultats[] = " Fichier '$fichier' inclus avec include.";
                    }
                } catch (Exception $e) {
                    $resultats[] = "Erreur lors de l'inclusion du fichier '$fichier' : " . $e->getMessage();
                }
            } else {
                $resultats[] = "Erreur : Le fichier '$fichier' n'existe pas.";
            }
        }

        // Retourner les messages dans la console
        if(count($resultats) > 0) {
            afficheMessageConsole("message inclusion fichiers :::\n");
            foreach ($resultats as $resultat) {
                afficheMessageConsole($resultat);
            }
        }




    }

    /*
    |----------------------------------------------------------------------------------|
    | @function : obtenir le chemin correct d'un controller
    | @param : route et nom du dossier local du site
    | @return : string - chemin correcte
    |----------------------------------------------------------------------------------|
    */

   function lien($valeur){
       return    empty(BASE_PATH) ? "/" . $valeur: "/" . BASE_PATH . "/" . $valeur;
   }

    /*
    |----------------------------------------------------------------------------------|
    | @function : Verifie si l'utilisateur est connecté
    | @return : bool
    |----------------------------------------------------------------------------------|
    */

    function estConnecte() {
        return isset($_SESSION['no_connexion']);
    }

    /*
    |----------------------------------------------------------------------------------|
    | @function : Status utilisateur
    | @return : string
    |----------------------------------------------------------------------------------|
    */

    function strStatut($statut) {
        switch ($statut) {
            case 0:
                return "En attente"; // Statut lors de l'enregistrement
            case 9:
                return "Confirmé"; // Statut après confirmation
            case 1:
                return "Administrateur"; // Rôle administrateur
            case 2:
                return "Cadre"; // Rôle cadre
            case 3:
                return "Employé de soutien"; // Rôle employé de soutien
            case 4:
                return "Enseignant"; // Rôle enseignant
            case 5:
                return "Professionnel"; // Rôle professionnel
            default:
                return "Statut inconnu"; // Cas par défaut si le statut n'est pas reconnu
        }
    }


    /*
    |----------------------------------------------------------------------------------|
    | @function : Annonce etat
    | @return : string
    |----------------------------------------------------------------------------------|
    */

    function strEtat($etat) {
        switch ($etat) {
            case 1:
                return "Actif"; // Statut lors de l'enregistrement
            case 2:
                return "Inactif"; // Statut après confirmation
            case 3:
                return "Retiré"; // Rôle administrateur
            default:
                return "Etat inconnu"; // Cas par défaut si le statut n'est pas reconnu
        }
    }

    // @function : redirect
    function redirectTo($url){
        echo '<script type="text/javascript">
        window.location.href = "' . lien($url) . '";
      </script>';
        exit();
    }

    //@function : sleep(time in seconds)
    function sleep_js($seconds) {
        echo '<script type="text/javascript">
        setTimeout(function(){
            window.location.reload(1);
        }, ' . $seconds * 1000 . ');
        </script>';
    }

//function estAdmin
function estAdmin() {
    return intval(STATUT_UTILISATEUR) == 1;
}




