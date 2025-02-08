<?php
    /*
    |----------------------------------------------------------------------------------|
    | Données sensibles,
    | Pour le developpement dans votre serveur local
    | Definissez les valeurs DB_HOST, DB_USERNAME, DB_PASSWORD
    | Dans votre systeme (terminal)
    | Sur linux : export DB_HOST = ""...
    | Sur Windows : set DB_HOST = ""...
    |----------------------------------------------------------------------------------|
    */
    if (getParametre("SERVER_NAME", "SERVER") == "localhost") {
        $HOSTNAME = getenv("DB_HOST");
        $USERNAME = getenv("DB_USERNAME");
        $PASSWORD = getenv("DB_PASSWORD");
    } else {

        $HOSTNAME = "sql210.infinityfree.com";
        $USERNAME = "if0_38253009";
        $PASSWORD = "3JZXLMjuqlSw";
    }
    $PORT = 3306;

