<?php



    // Debut da la session
    session_start();

    //Chargement les classes et librairies
    inclureFichiersDossier("classes", "require");
    inclureFichiersDossier("models", "require");
    inclureFichiersDossier("SQL", "require");






    // Variable style
    $shadowBox = "box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px; border-radius: 17px;";

    //Définir le nom du serveur
    define('SERVER_NAME', getParametre("SERVER_NAME", "SERVER"));
    // Définir le chemin de base des contrôleurs
    define('BASE_PATH', __DIR__);

    // Récupérer l'URL demandée
    $requestUri = getParametre("REQUEST_URI", "SERVER");

    // Supprimer la query string (tout ce qui suit "?")
    $requestUri = strtok($requestUri, '?');

    // Nettoyer l'URL (enlever les slashes inutiles)
    $requestUri = trim($requestUri, '/');

    // Diviser l'URL en segments
    $uriSegments = explode('/', $requestUri);


    // Le premier segment est considéré comme le contrôleur
    if (SERVER_NAME=="localhost") {
        //En local recuperer le lien après un pas
        $controller = !empty($uriSegments[1]) ? $uriSegments[1] : 'register';
    }else{
        $controller =  !empty($uriSegments[0]) ? $uriSegments[0] : 'register';
    }


    // Inclure le contrôleur correspondant
    $controllerFile = BASE_PATH . '/controllers/' . $controller . '.php';


    //Entete de la page
    $strTitreApplication = "Application  du groupeII";
    $strNomFichierCSS = "index.css";
    $strNomAuteur = "Michel Ange & Ramces & Franck & Samuel";
    require_once("shared/en-tete.php");
    //fin entête

    //Main contenu - Routage -  page dynamique
    if (file_exists($controllerFile)) {
        include $controllerFile;
    } else {
        // Si le contrôleur n'existe pas, afficher une page d'erreur 404
        header("HTTP/1.0 404 Pas trouvé");
        echo "<H4 >La page  <b class='text-danger'>$controller</b> n'existe pas</H4>\n";

    }

    //pied de page
    require_once("shared/pied-page.php");