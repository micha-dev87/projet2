<?php
    /*
    |--------------------------------------------------------------------------------|
    | chargeFichierEnMemoire() (2018-03-13; 2019-03-12; 2020-03-22)
    | Réf.: http://php.net/manual/fr/function.count.php
    | http://ca.php.net/manual/fr/function.file.php
    | http://php.net/manual/fr/function.file-get-contents.php
    | http://ca.php.net/manual/fr/function.str-replace.php
    | http://php.net/manual/fr/function.strlen.php
    |--------------------------------------------------------------------------------|
    */
    function chargeFichierEnMemoire($strNomFichier,
                                    &$tContenu, &$intNbLignes,
                                    &$strContenu, &$intTaille,
                                    &$strContenuHTML) {
        /* Récupère toutes les lignes et les entrepose dans un tableau
        Retrait de tous les CR et LF
        Récupère le nombre de lignes */
        $tContenu = file($strNomFichier);
        $tContenu = str_replace("\n", "", str_replace("\r", "", $tContenu));
        $intNbLignes = count($tContenu);
        /* Récupère toutes les lignes et les entrepose dans une chaîne */
        $strContenu = file_get_contents($strNomFichier);
        $intTaille = strlen($strContenu);
        /* Entrepose la chaîne résultante dans une autre après l'avoir XHTMLisé ! */
        $strContenuHTML = str_replace("\n\r", "<br />",
            str_replace("\r\n", "<br />", $strContenu));
    }
    /*
    |--------------------------------------------------------------------------------|
    | compteLignesFichier() (2018-03-13; 2019-03-12; 2020-03-22)
    | Réf.: http://ca.php.net/manual/fr/function.count.php
    | http://ca.php.net/manual/fr/function.file.php
    |--------------------------------------------------------------------------------|
    */
    function compteLignesFichier($strNomFichier) {
        $intNbLignes = -1;
        if (fichierExiste($strNomFichier)) {
            $intNbLignes = count(file($strNomFichier));
        }
        return $intNbLignes;
    }
    /*
    |--------------------------------------------------------------------------------|
    | detecteFinFichier() (2018-03-13; 2019-03-12; 2020-03-22)
    | Réf.: http://php.net/manual/fr/function.feof.php
    |--------------------------------------------------------------------------------|
    */
    function detecteFinFichier($fp) {
        $binVerdict = true;
        if ($fp) {
            $binVerdict = feof($fp);
        }
        return $binVerdict;
    }
    /*
    |--------------------------------------------------------------------------------|
    | ecritLigneDansFichier() (2018-03-13; 2019-03-12; 2020-03-22)
    | Réf.: http://php.net/manual/fr/function.fputs.php
    | http://php.net/manual/fr/function.gettype.php
    |--------------------------------------------------------------------------------|
    */
    function ecritLigneDansFichier($fp, $strLigneCourante,
                                   $binSaut_intNbLignesSaut = false) {
        $binVerdict = fputs($fp, $strLigneCourante);
        if ($binVerdict) {
            switch (gettype($binSaut_intNbLignesSaut)) {
                case "integer" :
                    for ($i=1; $i<=$binSaut_intNbLignesSaut && $binVerdict; $i++) {
                        $binVerdict = fputs($fp, "\r\n");
                    }
                    break;
                case "boolean" :
                    if ($binSaut_intNbLignesSaut) {
                        $binVerdict = fputs($fp, "\r\n");
                    }
            }
        }
        return $binVerdict;
    }
    /*
    |--------------------------------------------------------------------------------|
    | fermeFichier() (2018-03-13; 2019-03-12; 2020-03-22)
    | Réf.: http://ca.php.net/manual/fr/function.fclose.php
    |--------------------------------------------------------------------------------|
    */
    function fermeFichier($fp) {
        $binVerdict = false;
        if ($fp) {
            $binVerdict = fclose($fp);
        }
        return $binVerdict;
    }
    /*
    |--------------------------------------------------------------------------------|
    | fichierExiste() (2018-03-13; 2019-03-12; 2020-03-22)
    | Réf.: http://ca.php.net/manual/fr/function.file-exists.php
    |--------------------------------------------------------------------------------|
    */
    function fichierExiste($strNomFichier) {
        return file_exists($strNomFichier);
    }

    /*
|--------------------------------------------------------------------------------|
| litLigneDansFichier() (2018-03-13; 2019-03-12; 2020-03-22)
| Réf.: http://ca.php.net/manual/fr/function.fgets.php
| http://ca.php.net/manual/fr/function.str-replace.php
|--------------------------------------------------------------------------------|
*/
    function litLigneDansFichier($fp) {
        return str_replace("\n", "", str_replace("\r", "", fgets($fp)));
    }
    /*
    |--------------------------------------------------------------------------------|
    | ouvreFichier() (2018-03-13; 2019-03-12; 2020-03-22)
    | Réf.: http://ca.php.net/manual/fr/function.fopen.php
    | http://ca.php.net/manual/fr/function.strtoupper.php
    |--------------------------------------------------------------------------------|
    */
    function ouvreFichier($strNomFichier, $strMode="L") {
        switch (strtoupper($strMode)) {
            case "A" :
            case "A" :
                $strMode = "a";
                break;
            case "E" :
            case "W" :
                $strMode = "w";
                break;
            case "L" :
            case "R" :
                $strMode = "r";
                break;
        }
        $fp = fopen($strNomFichier, $strMode);
        return $fp;
    }

    /*
|-------------------------------------------------------------------------------------|
| detecteServeur
|-------------------------------------------------------------------------------------|
*/
    function detecteServeur(&$strMonIP, &$strIPServeur, &$strNomServeur, &$strInfosSensibles) {
        $strMonIP = $_SERVER["REMOTE_ADDR"];
        $strIPServeur = $_SERVER["SERVER_ADDR"];
        $strNomServeur = $_SERVER["SERVER_NAME"];
        $strInfosSensibles = str_replace(".", "-", $strNomServeur) . ".php";
    }
