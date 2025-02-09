<?php
    // Début de la session
    session_start();

    // Inclusion des librairies nécessaires
    require_once __DIR__ . "/librairies/librairie-exercice01.php";
    require_once __DIR__ . "/librairies/librairie-generale-2025-01-26.php";

    // Définir le nom du serveur
    define('SERVER_NAME', getParametre("SERVER_NAME", "SERVER"));
    // Définir des constantes globales
    define('DEFAULT_CONTROLLER', estConnecte() ? "dashboard" : "login"); // Contrôleur par défaut

    // Charger les variables d'environnement si on est en local
    if (SERVER_NAME === "localhost") {
        require_once __DIR__ . '/vendor/autoload.php';
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();
    }

    // Chargement automatique des classes et modèles
    inclureFichiersDossier("classes", "require");
    inclureFichiersDossier("models", "require");

    // Initialisation de la base de données
    require_once __DIR__ . "/SQL/init.php";



    // Style CSS global
    $shadowBox = "box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px; border-radius: 17px;";

    // Récupérer l'URL demandée
    $requestUri = getParametre("REQUEST_URI", "SERVER");
    $requestUri = strtok($requestUri, '?'); // Supprimer la query string
    $requestUri = trim($requestUri, '/'); // Nettoyer l'URL
    $uriSegments = explode('/', $requestUri); // Diviser l'URL en segments

    // Déterminer le contrôleur à charger
    $controller = DEFAULT_CONTROLLER;
    $id = null;

    if (!empty($uriSegments)) {
        //Sachant que le site dans le local host se trouve dans un dossier
        if(SERVER_NAME === "localhost"):
            $controller = (!empty(end($uriSegments)) && count($uriSegments) >2 )?
                end($uriSegments): DEFAULT_CONTROLLER;
        else:
            $controller = !empty(end($uriSegments))?end($uriSegments):DEFAULT_CONTROLLER;
        endif;
        // Si le dernier segment est numérique, il s'agit probablement d'un ID
        if (is_numeric($controller)) {
            $id = intval(array_pop($uriSegments)); // Récupérer l'ID et retirer le dernier segment
            $controller = end($uriSegments);
        }


    }
    afficheMessageConsole("Controlleur utilisé : ".$controller. " Nombre de urisegments : ".count($uriSegments));

    // Afficher l'ID dans la console pour débogage
    afficheMessageConsole("Id passé : " . ($id ?? "Aucun ID détecté"));

    // Construire le chemin du fichier du contrôleur
    $controllerFile = __DIR__ . '/controllers/' . $controller . '.php';

    /*
    |----------------------------------------------------------------------------------|
    | Entête de la page
    |----------------------------------------------------------------------------------|
    */
    $strTitreApplication = "Application  du groupeII";
    $strNomFichierCSS = "index.css";
    $strNomAuteur = "Michel Ange & Ramces & Franck & Samuel";
    require_once("shared/en-tete.php");


    // Vérifier si le fichier du contrôleur existe
    if (file_exists($controllerFile)) {
        include $controllerFile; // Inclure le contrôleur correspondant
    } else {
        // Gérer l'erreur 404 - Page non trouvée
        http_response_code(404);
        echo "<h4>La page <b class='text-danger'>$controller</b> n'existe pas.</h4>";
    }
    /*
    |----------------------------------------------------------------------------------|
    | Pied de la page
    |----------------------------------------------------------------------------------|
    */
    require_once("shared/pied-page.php");