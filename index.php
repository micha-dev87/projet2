<?php

// Début de la session
    session_start();
// Charger les variables d'environnement si on est en local
if ($_SERVER['SERVER_NAME'] === "localhost") {
    require_once __DIR__ . '/vendor/autoload.php';
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

//config data
//Controller de config
$config_controllers = ["data_liste_annonces"];

foreach($config_controllers as $controller)
{
    // verifions si le lien contient le controller 
    if (strpos($_SERVER['REQUEST_URI'], $controller) !== false) {


        require_once "config/" . $controller . ".php";
        exit();}
}
// Inclusion des librairies nécessaires
    require_once __DIR__ . "/librairies/librairie-exercice01.php";
    require_once __DIR__ . "/librairies/librairie-generale-2025-01-26.php";
    require_once __DIR__ . "/librairies/librairie-date.php";
    require_once __DIR__ . "/librairies/librairie-generale.php";


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
    define('ACTIONS_PATH', 'actions/');

    // Récupérer les informations de l'utilisateur connecté
    define('NO_CONNEXION', $_SESSION['no_connexion'] ?? null);
    define('NO_UTILISATEUR', $_SESSION['utilisateur']['NoUtilisateur'] ?? null);
    define('NOM_UTILISATEUR', $_SESSION['utilisateur']['Nom'] ?? null);
    define('PRENOM_UTILISATEUR', $_SESSION['utilisateur']['Prenom'] ?? null);
    define('COURRIEL_UTILISATEUR', $_SESSION['utilisateur']['Courriel'] ?? null);
    define('STATUT_UTILISATEUR', $_SESSION['utilisateur']['Statut'] ?? null);



// Chargement automatique des classes, styles et modèles
    inclureFichiersDossier("classes", "require");
    inclureFichiersDossier("models", "require");
    inclureFichiersDossier("variables", "require");
    inclureFichiersDossier("secrets", "require");


// Initialisation de la base de données
    require_once __DIR__ . "/SQL/init.php";

// Récupérer l'URL demandée
    $requestUri = $_SERVER['REQUEST_URI'];
    $requestUri = strtok($requestUri, '?'); // Supprimer la query string
    $requestUri = trim($requestUri, '/');  // Nettoyer les slashes

// Supprimer le sous-dossier du chemin
    if (!empty($subFolder)) {

        $requestUri = str_replace(strtolower($subFolder), "", $requestUri);


    }

// Diviser l'URL en segments
    $uriSegments = explode('/', $requestUri);

// Déterminer le contrôleur et l'action
    $controller = null;
    $action = null;
    $paramId = null;
    $intNbSegments = sizeof($uriSegments);
    $controllers = glob(CONTROLLERS_PATH . "*.php");
    $actions = glob(ACTIONS_PATH . "*.php");


  // Vérification du serveur local et des segments d'URL
    if(SERVER_NAME==="localhost"){
        if($intNbSegments >1){
            //si controleur

            if (in_array(CONTROLLERS_PATH . $uriSegments[1] . ".php", $controllers) || in_array( $uriSegments[1], $config_controllers)) {
                afficheMessageConsole("le controleur " .  $uriSegments[1] . " existe !");
                $controller =  $uriSegments[1];

                //action ?
                if($intNbSegments > 2){
                    //si action
                    if (in_array(ACTIONS_PATH . $uriSegments[2] . ".php", $actions)) {
                        afficheMessageConsole("l'action " .  $uriSegments[2] . " existe !");
                        $action =  $uriSegments[2];

                        if($intNbSegments ==4){
                            $paramId = $uriSegments[3];
                        }

                    }
                }
            }

        }
    }else{
        if($intNbSegments >0){
            //si controleur
            if (in_array(CONTROLLERS_PATH . $uriSegments[0] . ".php", $controllers) || in_array( $uriSegments[0], $config_controllers)) {
                afficheMessageConsole("le controleur " .  $uriSegments[0] . " existe !");
                $controller =  $uriSegments[0];

                //action ?
                if($intNbSegments == 2){
                    //si action
                    if (in_array(ACTIONS_PATH . $uriSegments[1] . ".php", $actions)) {
                        afficheMessageConsole("l'action " .  $uriSegments[1] . " existe !");
                        $action =  $uriSegments[1];

                        if($intNbSegments ==3){
                            $paramId = $uriSegments[2];
                        }

                    }
                }
            }

        }
    }




    afficheMessageConsole("Controller : ". $controller);
    afficheMessageConsole("Action : ". $action);
    afficheMessageConsole("ID param : ". $paramId);
    afficheMessageConsole("nombre de segment uri : ". $intNbSegments);
    afficheMessageConsole("BASE_PATH : ". BASE_PATH);
    afficheMessageConsole("METHODE : ". $_SERVER['REQUEST_METHOD']);



    //Si le controlleur est inexistant et lien trop long ridiger vers les routes de default
    if(is_null($controller) ){
        echo '<script type="text/javascript">
        window.location.href = "' . lien(DEFAULT_CONTROLLER) . '";
      </script>';
        exit();
    }


    //Rediriger tous utilisateur vers le dashboard
    if (estConnecte() && ($controller == "login" || $controller == "register")) {

        echo '<script type="text/javascript">
        window.location.href = "' . lien("dashboard") . '";
      </script>';
        exit();

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

