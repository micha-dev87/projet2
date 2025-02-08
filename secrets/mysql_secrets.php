<?php
    /*
    |----------------------------------------------------------------------------------|
    | Données sensibles,
    | Pour le developpement dans votre serveur local
    | Definissez les valeurs DB_HOST, DB_USERNAME, DB_PASSWORD
    |----------------------------------------------------------------------------------|
    */
    if (getParametre("SERVER_NAME", "SERVER") == "localhost") {


        $HOSTNAME = $_ENV['DB_HOST'] ;
        $USERNAME = $_ENV['DB_USERNAME'];
        $PASSWORD = $_ENV['DB_PASSWORD'];
    } else {

        $HOSTNAME = "sql210.infinityfree.com";
        $USERNAME = "if0_38253009";
        $PASSWORD = "3JZXLMjuqlSw";
    }
    $PORT = 3306;

