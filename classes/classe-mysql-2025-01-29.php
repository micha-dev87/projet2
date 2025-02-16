<?php


    /*
    |----------------------------------------------------------------------------------------|
    | class mysql
    |----------------------------------------------------------------------------------------|
    */

    class mysql
    {
        /*
        |----------------------------------------------------------------------------------|
        | Attributs
        |----------------------------------------------------------------------------------|
        */
        public $cBD = null;                       /* Identifiant de connexion */
        public $listeEnregistrements = null;      /* Liste des enregistrements retournés */
        public $OK = false;                       /* Opération réussie ou non */
        public $requete = "";                     /* Requête exécutée */
        /*
        |----------------------------------------------------------------------------------|
        | __construct
        |----------------------------------------------------------------------------------|
        */
        function __construct( )
        {


        }

        /*
        |----------------------------------------------------------------------------------|
        | connexion()
        |----------------------------------------------------------------------------------|
        */
        function connexion()
        {
            /* --- Connexion avec mySQL --- */
            $this->cBD = mysqli_connect(HOSTNAME, USERNAME, PASSWORD, DB_NAME);
            if (mysqli_connect_errno()) {
                echo "<br />";
                echo "<p class='text-danger'>Problème de connexion... " . "Erreur no " . mysqli_connect_errno() . " (" . mysqli_connect_error() . ") </p>";
                die();
            }
            $this->selectionneBD();
            return $this->cBD;
        }

        /*
        |----------------------------------------------------------------------------------|
        | copieEnregistrements
        |----------------------------------------------------------------------------------|
        */
        function copieEnregistrements($strNomTableSource, $strListeChampsSource,
                                      $strNomTableCible, $strListeChampsCible = "", $strListeConditions = "")
        {
            // Si les champs cibles sont vides, utiliser les champs sources
            if (empty($strListeChampsCible)) {
                $strListeChampsCible = $strListeChampsSource;
            }

            // Convertir les listes de champs en tableaux
            $champsSource = explode(',', str_replace(' ', '', $strListeChampsSource));
            $champsCible = explode(',', str_replace(' ', '', $strListeChampsCible));

            // Vérifier que le nombre de champs source et cible correspond
            if (count($champsSource) !== count($champsCible)) {
                die("Le nombre de champs source et cible ne correspond pas.");
            }


            // Construire la requête de copie des données
            $this->requete = "INSERT INTO $strNomTableCible ($strListeChampsCible) ";
            $this->requete .= "SELECT $strListeChampsSource FROM $strNomTableSource";

            // Ajouter les conditions si présentes
            if (!empty($strListeConditions)) {
                $this->requete .= " WHERE $strListeConditions";
            }

            // Exécuter la requête
            $this->OK = mysqli_query($this->cBD, $this->requete);


            // Retourner le succès de l'opération
            return $this->OK;
        }


        /*
        |----------------------------------------------------------------------------------|
        | creeTable
        |----------------------------------------------------------------------------------|
        */
        function creeTable($strNomTable)
        {

            $this->requete = "CREATE TABLE IF NOT EXISTS $strNomTable (";

            // On récupère le nombre total d'arguments
            $nbArgs = func_num_args();

            // On parcourt les arguments 2 par 2 (nom colonne + type)
            for ($i = 1; $i < $nbArgs; $i += 2) {
                // Si l'argument est une clause comme PRIMARY KEY, on l'ajoute directement
                if (is_string(func_get_arg($i)) && strpos(func_get_arg($i), 'PRIMARY KEY') !== false) {
                    $this->requete .= func_get_arg($i);
                } else {
                    $this->requete .= func_get_arg($i) . " classe-mysql-2025-01-29.php" . func_get_arg($i + 1);
                }

                // On ajoute une virgule après chaque couple, sauf pour le dernier
                if ($i < $nbArgs - 2 && !(is_string(func_get_arg($i)) && strpos(func_get_arg($i), 'PRIMARY KEY') !== false)) {
                    $this->requete .= ",";
                }
            }

            $this->requete .= ") ENGINE=InnoDB";
            $this->OK = mysqli_query($this->cBD, $this->requete);


            return $this->OK;
        }

        /*
        |----------------------------------------------------------------------------------|
        | creeTableGenerique()
        |----------------------------------------------------------------------------------|
        */
        function creeTableGenerique($strNomTable, $strDefinitions, $strCles)
        {


            // Initialiser la requête
            $this->requete = "CREATE TABLE IF NOT EXISTS $strNomTable (";

            // Séparer les définitions (séparées par ;)
            $definitions = explode(";", $strDefinitions);

            // Pour chaque définition
            for ($i = 0; $i < count($definitions); $i++) {
                if (empty($definitions[$i])) continue;

                // Séparer le type et le nom (séparés par )
                list($type, $nom) = explode(",", trim($definitions[$i]));

                // Convertir le type en SQL selon les règles
                switch ($type[0]) {
                    case 'B':
                        $sqlType = "BOOL";
                        break;
                    case 'C':
                        $precision = substr($type, 1);
                        $precision = str_replace(".", ",", $precision);
                        $sqlType = "DECIMAL($precision)";
                        break;
                    case 'D':
                        $sqlType = "DATETIME";
                        break;
                    case 'E':

                        $sqlType = "INT";
                        break;

                    case 'F':
                        $length = substr($type, 1);
                        $sqlType = "CHAR($length)";
                        break;
                    case 'M':
                        $sqlType = "DECIMAL(10,2)";
                        break;
                    case 'N':

                        $sqlType = "INT NOT NULL";
                        break;
                    case 'V':
                        $length = substr($type, 1);
                        $sqlType = "VARCHAR($length)";
                        break;
                    case 'A':
                        $sqlType= "INT NOT NULL AUTO_INCREMENT";
                }

                // Ajouter la colonne à la requête
                $this->requete .= "$nom $sqlType";

                // Ajouter une virgule si ce n'est pas la dernière définition
                if ($i < count($definitions) - 1) {
                    $this->requete .= ", ";
                }
            }

            // Ajouter la clé primaire
            if (!empty($strCles)) {
                $this->requete .= ", PRIMARY KEY($strCles)";
            }

            // Finaliser la requête
            $this->requete .= ") ENGINE=InnoDB";

            // Exécuter la requête et mettre à jour OK
            $this->OK = mysqli_query($this->cBD, $this->requete);

            return $this->OK;


        }

        /*
        |----------------------------------------------------------------------------------|
        | deconnexion
        |----------------------------------------------------------------------------------|
        */
        function deconnexion()
        {
            $this->OK = mysqli_close($this->cBD);

        }

        /*
        |----------------------------------------------------------------------------------|
        |poupulateTableAnnonces et ()
        |----------------------------------------------------------------------------------|
        */

        /*
        |----------------------------------------------------------------------------------|
        | insereEnregistrement
        |----------------------------------------------------------------------------------|
        */
        function insereEnregistrement($strNomTable)
        {
            // Initialiser la requête
            $valeurs = [];
            $nbArgs = func_num_args();


            // Vérifier qu'il y a au moins un argument en plus du nom de la table
            if ($nbArgs < 2) {
                die("Au moins une valeur doit être fournie pour l'insertion.");
            }


            // Parcourir tous les arguments sauf le premier (qui est le nom de la table)
            for ($i = 1; $i < $nbArgs; $i++) {
                $valeur = func_get_arg($i);

                // Traitement selon le type de la valeur
                if ($valeur === NULL) {
                    $valeurs[] = "NULL";
                } elseif (is_string($valeur)) {
                    // Vérifier si la valeur est une date valide
                    if (preg_match("/[0-9]{4}-[0-9]{2}-[0-9]{2}/", $valeur)) {
                        $valeur = dateValide($valeur) ? $valeur : aujourdhui();
                    }



                    // Échapper les chaînes pour éviter les injections SQL
                    $valeur = mysqli_real_escape_string($this->cBD, $valeur);
                    // Ajouter la valeur dans le tableau
                    $valeurs[] = "'$valeur'";
                } elseif (is_bool($valeur)) {
                    // Convertir les booléens en 1 ou 0
                    $valeurs[] = $valeur ? "1" : "0";
                } else {
                    // Ajouter les nombres tels quels
                    $valeurs[] = $valeur;
                }
            }

            // Construire la requête INSERT IGNORE
            $this->requete = "INSERT IGNORE INTO `$strNomTable` VALUES (" . implode(", ", $valeurs) . ")";

            // Exécuter la requête
            $this->OK = mysqli_query($this->cBD, $this->requete);

            // Gérer les erreurs
            if (!$this->OK) {
                die("Erreur lors de l'exécution de la requête : " . mysqli_error($this->cBD));
            }

            // Retourner le succès de l'opération
            return $this->OK;
        }

        /*
        |----------------------------------------------------------------------------------|
        | modifieChamp
        |----------------------------------------------------------------------------------|
        */
        function modifieChamp($strNomTable, $strNomChamp, $strNouvelleDefinition)
        {
            $this->requete = "ALTER TABLE $strNomTable CHANGE $strNomChamp $strNouvelleDefinition";
            $this->OK = mysqli_query($this->cBD, $this->requete);
            return $this->OK;
        }

        /*
        |----------------------------------------------------------------------------------|
        | selectionneBD()
        |----------------------------------------------------------------------------------|
        */
        function selectionneBD()
        {

            $this->requete = "USE " . DB_NAME;
            $this->OK = mysqli_query($this->cBD, $this->requete);
            return $this->OK;
        }

        /*
        |----------------------------------------------------------------------------------|
        | supprimeEnregistrements
        |----------------------------------------------------------------------------------|
        */
        function supprimeEnregistrements($strNomTable, $strListeConditions = "")
        {
            $this->requete = "DELETE FROM $strNomTable";
            $this->requete .= empty($strlisteconditions) ? "" : " WHERE $strlisteconditions";
            $this->OK = mysqli_query($this->cBD, $this->requete);
            return $this->OK;

        }

        /*
        |----------------------------------------------------------------------------------|
        | supprimeTable()
        |----------------------------------------------------------------------------------|
        */
        function supprimeTable($strNomTable)
        {

            $this->requete = "DROP TABLE IF EXISTS " . $strNomTable;
            $this->OK = mysqli_query($this->cBD, $this->requete);
            return $this->OK;
        }

        /*
        |----------------------------------------------------------------------------------|
        | afficheInformationsSurBD()
        | Affiche la structure et le contenu de chaque table de la base de données recherchée
        |----------------------------------------------------------------------------------|
        */
        function afficheInformationsSurBD()
        {
            /* Si applicable, récupération du nom de la table recherchée */
            $strNomTableRecherchee = "";
            if (func_num_args() > 0) {
                $strNomTableRecherchee = func_get_arg(0);
            }

            /* Variables de base pour les styles */
            $strTable = "border-collapse:collapse;";
            $strCommande = "font-family:verdana; font-size:12pt; font-weight:bold; color:black; border:solid 1px black; padding:3px;";
            $strMessage = "font-family:verdana; font-size:10pt; font-weight:bold; color:red;";
            $strBorduresMessage = "border:solid 1px red; padding:3px;";
            $strContenu = "font-family:verdana; font-size:10pt; color:blue;";
            $strBorduresContenu = "border:solid 1px red; padding:3px;";
            $strTypeADefinir = "color:red;font-weight:bold;";
            $strDetails = "color:magenta;";

            /* Application des styles */
            $sTable = "style=\"$strTable\"";
            $sCommande = "style=\"$strCommande\"";
            $sMessage = "style=\"$strMessage\"";
            $sMessageAvecBordures = "style=\"$strMessage $strBorduresMessage\"";
            $sContenu = "style=\"$strContenu\"";
            $sContenuAvecBordures = "style=\"$strContenu $strBorduresContenu\"";
            $sTypeADefinir = "style=\"$strTypeADefinir\"";
            $sDetails = "style=\"$strDetails\"";

            /* --- Entreposage des noms de table --- */
            $ListeTablesBD = array_column(mysqli_fetch_all(mysqli_query($this->cBD, 'SHOW TABLES')), 0);
            $intNbTables = count($ListeTablesBD);

            /* --- Parcours de chacune des tables --- */
            echo "<span $sCommande>Informations sur " . (!empty($strNomTableRecherchee) ?
                    "la table '$strNomTableRecherchee' de " : "") . "la base de données '".DB_NAME."' </span><br />";
            $binTablePresente = false;
            for ($i = 0; $i < $intNbTables; $i++) {
                /* Récupération du nom de la table courante */
                $strNomTable = $ListeTablesBD[$i];
                if (empty($strNomTableRecherchee) || strtolower($strNomTable) == strtolower($strNomTableRecherchee)) {
                    $binTablePresente = true;
                    echo "<p $sMessage>Table no " . strval($i + 1) . " : " . $strNomTable . "</p>";

                    /* Récupération des enregistrements de la table courante */
                    $ListeEnregistrements = mysqli_query($this->cBD, "SELECT * FROM $strNomTable");

                    /* Décompte du nombre de champs et d'enregistrements de la table courante */
                    $NbChamps = mysqli_field_count($this->cBD);
                    $NbEnregistrements = mysqli_num_rows($ListeEnregistrements);
                    echo "<p $sContenu>$NbChamps champs ont été détectés dans la table.<br />";
                    echo "    $NbEnregistrements enregistrements ont été détectés dans la table.</p>";

                    /* Affichage de la structure de table courante */
                    echo "<p $sContenu>";
                    $j = 0;
                    $tabNomChamp = array();
                    while ($champCourant = $ListeEnregistrements->fetch_field()) {
                        $intDivAjustement = 1;
                        $tabNomChamp[$j] = $champCourant->name;
                        $strType = $champCourant->type;
                        switch ($strType) {
                            case 1   :
                                $strType = "BOOL";
                                break;
                            case 3   :
                                $strType = "INTEGER";
                                break;
                            case 10  :
                                $strType = "DATE";
                                break;
                            case 12  :
                                $strType = "DATETIME";
                                break;
                            case 246 :
                                $strType = "DECIMAL";
                                break;
                            case 253 :
                                $strType = "VARCHAR";
                                /* Ajustement temporaire */
                                if ($_SERVER["SERVER_NAME"] == "lmbrousseau.ca") {
                                    $intDivAjustement = 3;
                                }
                                break;
                            case 254 :
                                $strType = "CHAR";
                                break;
                            default  :
                                $strType = "<span $sTypeADefinir>$strType à définir</span>";
                                break;
                        }
                        $strLongueur = intval($champCourant->length) / $intDivAjustement;
                        $intDetails = $champCourant->flags;
                        $strDetails = "";
                        if ($intDetails & 1) $strDetails .= "[NOT_NULL] ";
                        if ($intDetails & 2) $strDetails .= "<span style=\"font-weight:bold;\">[PRI_KEY]</span> ";
                        if ($intDetails & 4) $strDetails .= "[UNIQUE_KEY] ";
                        if ($intDetails & 16) $strDetails .= "[BLOB] ";
                        if ($intDetails & 32) $strDetails .= "[UNSIGNED] ";
                        if ($intDetails & 64) $strDetails .= "[ZEROFILL] ";
                        if ($intDetails & 128) $strDetails .= "[BINARY] ";
                        if ($intDetails & 256) $strDetails .= "[ENUM] ";
                        if ($intDetails & 512) $strDetails .= "[AUTO_INCREMENT] ";
                        if ($intDetails & 1024) $strDetails .= "[TIMESTAMP] ";
                        if ($intDetails & 2048) $strDetails .= "[SET] ";
                        if ($intDetails & 32768) $strDetails .= "[NUM] ";
                        if ($intDetails & 16384) $strDetails .= "[PART_KEY] ";
                        if ($intDetails & 32768) $strDetails .= "[GROUP] ";
                        if ($intDetails & 65536) $strDetails .= "[UNIQUE] ";
                        echo ($j + 1) . ". $tabNomChamp[$j], $strType($strLongueur) <span $sDetails>$strDetails</span><br />";
                        $j++;
                    }
                    echo "</p>";

                    /* Affichage des enregistrements composant la table courante */
                    echo "<table $sTable>";
                    echo "<tr>";
                    for ($k = 0; $k < $NbChamps; $k++)
                        echo "<td $sMessageAvecBordures>" . $tabNomChamp[$k] . "</td>";
                    echo "</tr>";
                    if (empty($NbEnregistrements)) {
                        echo "<tr>";
                        echo "<td $sContenuAvecBordures colspan=\"$NbChamps\">";
                        echo " Aucun enregistrement";
                        echo "</td>";
                        echo "</tr>";
                    }
                    while ($listeChampsEnregistrement = $ListeEnregistrements->fetch_row()) {

                        echo "<tr>";
                        for ($j = 0; $j < count($listeChampsEnregistrement); $j++)
                            echo "      <td $sContenuAvecBordures>" . $listeChampsEnregistrement[$j] . "</td>";
                        echo "   </tr>";
                    }
                    echo "</table>";
                    $ListeEnregistrements->free();
                }
            }
            if (!$binTablePresente)
                echo "<p $sMessage>Aucune table !</p>";
        }
    }

?>