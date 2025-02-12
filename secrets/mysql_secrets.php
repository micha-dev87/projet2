<?php
    /*
    |----------------------------------------------------------------------------------|
    | Données sensibles,
    | Pour le developpement dans votre serveur local
    | Definissez les valeurs DB_HOST, DB_USERNAME, DB_PASSWORD
    |----------------------------------------------------------------------------------|
    */
    define('DB_NAME', 'if0_38253009_projet2');
    define('PORT', 3306);
    if (getParametre("SERVER_NAME", "SERVER") == "localhost") {
        
        define("HOSTNAME", $_ENV['DB_HOST'] );
        define("USERNAME", $_ENV['DB_USERNAME']);
        define("PASSWORD", $_ENV['DB_PASSWORD']);
    } else {

        define("HOSTNAME", "sql210.infinityfree.net");
        define("USERNAME", "if0_38253009");
        define("PASSWORD", "3JZXLMjuqlSw");

    }


