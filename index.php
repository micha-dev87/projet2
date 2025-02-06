<?php

    //Entete de la page
    $strTitreApplication = "Application  du groupeII";
    $strNomFichierCSS = "index.css";
    $strNomAuteur = "Michel Ange & Ramces & Franck & Samuel";
    require_once ("shared/en-tete.php");
    //fin entête
    session_start();

    //Chargement les classes et librairies
    require_once("classes/classe-mysql-2025-01-29.php");
    require_once("classes/classe-fichier-2018-03-13.php");
    require_once("librairies/librairie-exercice01.php");
    require_once("librairies/librairie-generale-2025-01-26.php");
    //fin


    // Initialiser la base de données :
    require_once("SQL/init.php");




    //pied de page
    require_once ("shared/pied-page.php");