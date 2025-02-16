
<?php
    /*
    |-------------------------------------------------------------------------------------|
    | extraitJJMMAAAA (2022-01-jj)
    | Scénarios : extraitJJMMAAAA($intJour, $intMois, $intAnnee)           <= date()
    |             extraitJJMMAAAA($intJour, $intMois, $intAnnee, $strDate) <= $strDate
    |-------------------------------------------------------------------------------------|
    */
    function extraitJJMMAAAA(&$intJour, &$intMois, &$intAnnee) {
        /* Par défaut, l'extraction s'effectue à partir de la date courante;
         * autrement elle s'effectue à partir du 4e argument spécifié à l'appel */
        if (func_num_args() == 3) {
            /* Récupération de la date courante */
            $strDate = date("d-m-Y");
        }
        else {
            /* Récupération du 4e argument */
            $strDate = func_get_arg(3);
        }
        $intJour = intval(substr($strDate, 0, 2));
        $intMois = intval(substr($strDate, 3, 2));
        $intAnnee = intval(substr($strDate, 6, 4));
    }
    /*
    |-------------------------------------------------------------------------------------|
    | extraitJSJJMMAAAA (2022-01-jj)
    | Scénarios : extraitJSJJMMAAAA($intJourSemaine, $intJour, $intMois, $intAnnee)
    |             extraitJSJJMMAAAA($intJourSemaine, $intJour, $intMois, $intAnnee, strDate)
    |-------------------------------------------------------------------------------------|
    */
    function extraitJSJJMMAAAA(&$intJourSemaine, &$intJour, &$intMois, &$intAnnee) {
        /* Par défaut, l'extraction s'effectue à partir de la date courante;
         * autrement elle s'effectue à partir du 5e argument spécifié à l'appel */
        if (func_num_args() == 4) {
            /* Récupération de la date courante */
            $strDate = date("d-m-Y");
            /* Récupération du jour de la semaine */
            $intJourSemaine = date("N");
        }
        else {
            /* Récupération du 5e argument */
            $strDate = func_get_arg(4);
            /* Récupération du jour de la semaine après avoir convertit la date passée
             * sous forme d'une chaîne en timestamp Unix */
            $intJourSemaine = date("N", strtotime($strDate));
        }
        $intJour = intval(substr($strDate, 0, 2));
        $intMois = intval(substr($strDate, 3, 2));
        $intAnnee = intval(substr($strDate, 6, 4));
    }

    /*
       |-------------------------------------------------------------------------------------|
       | JJMMAAAA (2022-mm-jj)
       | Scénario : JJMMAAAA($intJour, $intMois, $intAnnee) = > "$intJour-$intMois-$intAnnee"
       |            Si $intAnnee sur 2 positions
       |               Si $intAnnee <= 22 => 2000 à 2022 autrement => 1923 à 1999
       |-------------------------------------------------------------------------------------|
       */
    function JJMMAAAA($intJour, $intMois, $intAnnee) {
        $intAnnee = $intAnnee <= 22 ? 2000 + $intAnnee :
            ($intAnnee <= 99 ? 1900 + $intAnnee : $intAnnee);
        /* La date retournée doit avoir le format 0J-0M-AAAA */
        return ajouteZeros($intJour, 2) . "-" .
            ajouteZeros($intMois, 2) . "-" .
            ajouteZeros($intAnnee, 4);
    }
    /*
    |-------------------------------------------------------------------------------------|
    | jourSemaineEnLitteral (2022-01-jj)
    | Scénarios : jourSemaineEnLitteral($intNoJour)
    |             jourSemaineEnLitteral($intNoJour, $binMajuscule)
    |-------------------------------------------------------------------------------------|
    */
    function jourSemaineEnLitteral($intNoJour, $binMajuscule=false) {
        /* Par défaut, la première lettre du jour de la semaine s'affiche en minuscule
         * ($binMajuscule=false). Si un deuxième argument est saisi, il déterminera si
         * la première lettre doit s'afficher en majuscule ou non */
        $strJourSemaine = "N/A";
        switch ($intNoJour) {
            case  1 : $strJourSemaine = "lundi"; break;
            case  2 : $strJourSemaine = "mardi"; break;
            case  3 : $strJourSemaine = "mercredi"; break;
            case  4 : $strJourSemaine = "jeudi"; break;
            case  5 : $strJourSemaine = "vendredi"; break;
            case  6 : $strJourSemaine = "samedi"; break;
            case  7 : $strJourSemaine = "dimanche"; break;
        }
        $strJourSemaine = $binMajuscule ? ucfirst($strJourSemaine) : $strJourSemaine;

        return $strJourSemaine;
    }


    /*
     |-------------------------------------------------------------------------------------|
     | moisEnLitteral (2022-01-jj)
     | Scénarios : moisEnLitteral($intMois)                => Première lettre en minuscule
     |             moisEnLitteral($intMois, $binMajuscule) => En fonction de $binMajuscule
     |-------------------------------------------------------------------------------------|
     */
    function moisEnLitteral($intMois, $binMajuscule=false) {
        /* Par défaut, la première lettre du mois s'affiche en minuscule ($binMajuscule=false)
         * Si un deuxième argument est saisi, il déterminera si la première lettre doit
         * s'afficher en majuscule ou non */
        $strMois = "N/A";
        switch ($intMois) {
            case  1 : $strMois = "janvier";break;
            case  2 : $strMois = "f&eacute;vrier";break;
            case  3 : $strMois = "mars";break;
            case  4 : $strMois = "avril";break;
            case  5 : $strMois = "mai";break;
            case  6 : $strMois = "juin";break;
            case  7 : $strMois = "juillet";break;
            case  8 : $strMois = "ao&ucirc;t";break;
            case  9 : $strMois = "septembre";break;
            case 10 : $strMois = "octobre";break;
            case 11 : $strMois = "novembre";break;
            case 12 : $strMois = "d&eacute;cembre";break;
        }
        /*
         * if ($binMajuscule)
         *    $strMois = ucfirst($strMois);
         */
        $strMois = $binMajuscule ? ucfirst($strMois) : $strMois;

        return $strMois;
    }
?>