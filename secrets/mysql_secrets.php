<?php

    if (getParametre("SERVER_NAME", "SERVER") == "localhost") {
        $HOSTNAME = "localhost";
        $USERNAME = "root";
        $PASSWORD = "Marias1987@";
    } else {

        $HOSTNAME = "sql210.infinityfree.com";
        $USERNAME = "if0_38253009";
        $PASSWORD = "3JZXLMjuqlSw";
    }
    $PORT = 3306;
