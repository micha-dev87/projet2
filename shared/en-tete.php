

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $strTitreApplication; ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" type="text/css" href="<?=$strNomFichierCSS?>"/>
    <!--    bootstrap integration-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome pour les icônes -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">

</head>
<body>
<div class="container-fluid">
    <header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
        <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none">
            <svg class="bi me-2" width="40" height="32"><use xlink:href="#bootstrap"></use></svg>
            <span class="fs-4"><?= $strNomAuteur?></span>
        </a>

        <?php
            // Définir les routes disponibles
            if(estConnecte()){
                $routes = [
                    'dashboard'     => 'Dashboard',
                    'logout'   => 'Se déconnecter'
                ];
            }else{
                $routes = [
                    'register' => 'register',
                    'login'    => 'login'
                ];
            }

            // Générer dynamiquement les liens du menu
            echo '<ul class="nav nav-pills">';
            foreach ($routes as $route => $label) {
                // Déterminer si le lien est actif
                $isActive = $GLOBALS["controller"] == $route;
                $activeClass = $isActive ? 'aria-current="page" class="nav-link active"' : 'class="nav-link"';
                $lien = lien($route);
                // Afficher le lien
                echo "<li class=\"nav-item\"><a href=\"$lien\" $activeClass>$label</a></li>";
            }
            echo '</ul>';
        ?>
    </header>
</div>
<div class="container mt-5  mx-auto" style="max-width: 1000px;">



