<?php




    // Debut da la session
    session_start();

    //inclusion générale
    require_once ("librairies/librairie-exercice01.php");
    require_once ("librairies/librairie-generale-2025-01-26.php");
    if(getParametre("SERVER_NAME", "SERVER") == "localhost"):
    require_once __DIR__.'/vendor/autoload.php';
    // Charger les variables d'environnement depuis le fichier .env
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
    endif;

    //Chargement les classes et models
    inclureFichiersDossier("classes", "require");
    inclureFichiersDossier("models", "require");
    //charger la base de données
    require_once ("SQL/init.php");






    // Variable style
    $shadowBox = "box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px; border-radius: 17px;";

    //Définir le nom du serveur
    define('SERVER_NAME', getParametre("SERVER_NAME", "SERVER"));
    // Définir le chemin de base des contrôleurs
    define('BASE_PATH', __DIR__);





    /*
     * ------------------
     */
    // Récupérer l'URL demandée
    $requestUri = getParametre("REQUEST_URI", "SERVER");

    // Supprimer la query string (tout ce qui suit "?")
    $requestUri = strtok($requestUri, '?');

    // Nettoyer l'URL (enlever les slashes inutiles)
    $requestUri = trim($requestUri, '/');

    // Diviser l'URL en segments
    $uriSegments = explode('/', $requestUri);


    $defaultRoute = estConnecte() ? "dashboard" : "login";
    /*
    |----------------------------------------------------------------------------------|
    |returner un lien selon le server
    |----------------------------------------------------------------------------------|
    */
    $intNbUriSegment = count($uriSegments);
    if(getParametre("SERVER_NAME", "SERVER") == "localhost"):
        $controller = count($uriSegments)>1 ? end($uriSegments) : $defaultRoute;
    else:
        $controller = count($uriSegments)>0 ? end($uriSegments) : $defaultRoute;
    endif;

    /*
    |----------------------------------------------------------------------------------|
    |Recuperer les paramétres passés dans les lien
    |----------------------------------------------------------------------------------|
    */

    if(is_numeric($controller)){
        //vérifier que le format du lien est correcte sinon retourner le controller par default
        if($intNbUriSegment >2):
            $controller = $uriSegments[$intNbUriSegment-2];
            $id= intval(end($uriSegments));
        endif;
    }

    //recupérer l'id passé dans la console
    afficheMessageConsole("Id passé :");
    afficheMessageConsole($id??"Id inexistant !") ;
    // Inclure le contrôleur correspondant
    $controllerFile = BASE_PATH . '/controllers/' . $controller . '.php';


    /*
    |----------------------------------------------------------------------------------|
    | Entête de la page
    |----------------------------------------------------------------------------------|
    */
    $strTitreApplication = "Application  du groupeII";
    $strNomFichierCSS = "index.css";
    $strNomAuteur = "Michel Ange & Ramces & Franck & Samuel";
    require_once("shared/en-tete.php");

    /*
    |----------------------------------------------------------------------------------|
    | Contenu de la page - selon les vues
    |----------------------------------------------------------------------------------|
    */
    if (file_exists($controllerFile)) {
        include $controllerFile;
    } else {
        // Si le contrôleur n'existe pas, afficher une page d'erreur 404
        header("HTTP/1.0 404 Pas trouvé");
        echo "<H4 >La page  <b class='text-danger'>$controller</b> n'existe pas</H4>\n";

    }
    /*
    |----------------------------------------------------------------------------------|
    | Pied de la page
    |----------------------------------------------------------------------------------|
    */
    require_once("shared/pied-page.php");