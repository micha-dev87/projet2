<?php

    //Entete de la page
    $strTitreApplication = "Application  du groupeII";
    $strNomFichierCSS = "index.css";
    $strNomAuteur = "Michel Ange & Ramces & Franck & Samuel";
    require_once("shared/en-tete.php");
    //fin entête

    // Debut da la session
    session_start();

    //Chargement les classes et librairies
    require_once("classes/classe-mysql-2025-01-29.php");
    require_once("classes/classe-fichier-2018-03-13.php");
    require_once("librairies/librairie-exercice01.php");
    require_once("librairies/librairie-generale-2025-01-26.php");
    //fin


    // Initialiser la base de données :
    require_once("SQL/init.php");


    // Définir le chemin de base des contrôleurs
    define('BASE_PATH', getParametre("SERVER_NAME", "SERVER"));

    // Récupérer l'URL demandée
    $requestUri = getParametre("REQUEST_URI", "SERVER");

    // Supprimer la query string (tout ce qui suit "?")
    $requestUri = strtok($requestUri, '?');

    // Nettoyer l'URL (enlever les slashes inutiles)
    $requestUri = trim($requestUri, '/');

    // Diviser l'URL en segments
    $uriSegments = explode('/', $requestUri);

    // Le premier segment est considéré comme le contrôleur

    if(count($uriSegments) > 2) {
        $controller = !empty($uriSegments[0]) ? $uriSegments[0] : 'register';
    }else{
        $controller = 'register';
    }



    // Inclure le contrôleur correspondant
    $controllerFile = BASE_PATH . '/controllers/' . $controller . '.php';
    echo $controllerFile;
    if (file_exists($controllerFile)) {
        include $controllerFile;
    } else {
        // Si le contrôleur n'existe pas, afficher une page d'erreur 404
        header("HTTP/1.0 404 Not Found");
        echo "Page not found.";
        exit;
    }

    //pied de page
    require_once("shared/pied-page.php");