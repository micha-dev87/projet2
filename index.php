<?php


// Début de la session
    session_start();

// Inclusion des librairies nécessaires
    require_once __DIR__ . "/librairies/librairie-exercice01.php";
    require_once __DIR__ . "/librairies/librairie-generale-2025-01-26.php";

// Déterminer le sous-dossier dynamiquement
    $scriptName = $_SERVER['SCRIPT_NAME'];
    $subFolder = trim(dirname($scriptName), "/");
    // format lien
    $subFolder = str_replace(" ", "%20", $subFolder);

// Définir des constantes globales
    define('SERVER_NAME', getParametre("SERVER_NAME", "SERVER"));
    define('DEFAULT_CONTROLLER', estConnecte() ? "dashboard" : "login");
    define('BASE_PATH', $subFolder);
    define('CONTROLLERS_PATH', 'controllers/');

// Charger les variables d'environnement si on est en local
    if (SERVER_NAME === "localhost") {
        require_once __DIR__ . '/vendor/autoload.php';
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();
    }

// Chargement automatique des classes, styles et modèles
    inclureFichiersDossier("classes", "require");
    inclureFichiersDossier("models", "require");
    inclureFichiersDossier("variablesStyles", "require");


// Initialisation de la base de données
    require_once __DIR__ . "/SQL/init.php";

// Récupérer l'URL demandée
    $requestUri = $_SERVER['REQUEST_URI'];
    $requestUri = strtok($requestUri, '?'); // Supprimer la query string
    $requestUri = trim($requestUri, '/');  // Nettoyer les slashes

// Supprimer le sous-dossier du chemin
    if (!empty($subFolder)) {
        $requestUri = str_replace($subFolder, '', $requestUri);
    }

// Diviser l'URL en segments
    $uriSegments = explode('/', $requestUri);

// Déterminer le contrôleur et l'action
    $controller = DEFAULT_CONTROLLER;
    $action = "";
    $id = null;
    $intNbSegments= sizeof($uriSegments);

    if (!empty($requestUri)) {
        $controller = end($uriSegments);
        $index = null;

    }

    // Afficher message dans la console navigateur
    afficheMessageConsole("Infos folder, controller et ID :");
    afficheMessageConsole("Default Controller: " . DEFAULT_CONTROLLER);
    afficheMessageConsole("Controller : ".$controller);
    afficheMessageConsole("ID : ".$id);
    afficheMessageConsole("subFolder  : ".$subFolder);
    afficheMessageConsole("Nombre segment : ".$intNbSegments);
    afficheMessageConsole("requestUri : ".$requestUri);
    afficheMessageConsole("Controller path: ".CONTROLLERS_PATH);


    //Rediriger tous utilisateur vers le dashboard
    if(estConnecte() && ($controller=="login" || $controller=="register")) {
        header('Location: dashboard');
    }

// Construire le chemin du fichier du contrôleur
    $controllerFile = CONTROLLERS_PATH . $controller . '.php';

// Inclure les fichiers communs
    $strTitreApplication = "Application du groupe II";
    $strNomFichierCSS = "index.css";
    $strNomAuteur = "Michel Ange & Ramces & Franck & Samuel";
    require_once("shared/en-tete.php");

// Vérifier si le fichier du contrôleur existe
    if (file_exists($controllerFile)) {

        include $controllerFile;
    } else {
        http_response_code(404);
        echo "<h4>La page <b class='text-danger'>$controller</b> n'existe pas.</h4>";
    }

// Inclure le pied de page
    require_once("shared/pied-page.php");