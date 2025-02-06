<?php
   /* OK
   |-------------------------------------------------------------------------------------|
   | AAAAMMJJ (2018-02-01)
   | Scénario : AAAAMMJJ($intJour, $intMois, $intAnnee) = > "$intAnnee-$intMois-$intJour"
   |            Si $intAnnee sur 2 positions 
   |               Si $intAnnee <= 20 => 2000 à 2020 autrement => 1921 à 1999
   |-------------------------------------------------------------------------------------|
   */
   function AAAAMMJJ() {
      if (func_num_args() == 1) {
         $strDate = func_get_arg(0);
         $intJour = intval(substr($strDate, 0, 2));
         $intMois = intval(substr($strDate, 3, 2));
         $intAnnee = intval(substr($strDate, 6, 4));
      }
      else {
         $intJour = intval(func_get_arg(0));
         $intMois = intval(func_get_arg(1));
         $intAnnee = intval(func_get_arg(2));
      }
      $intAnnee = $intAnnee <= 20 ? 2000 + $intAnnee :
                                           ($intAnnee <= 99 ? 1900 + $intAnnee : $intAnnee);
      /* La date retournée doit avoir le format 0J-0M-AAAA */
      return ajouteZeros($intAnnee, 4) . "-" . 
             ajouteZeros($intMois, 2) . "-" .
             ajouteZeros($intJour, 2);
   }
   /* OK
   |-------------------------------------------------------------------------------------|
   | annee (2018-02-05)
   | > annee("aaaa-mm-jj") ou annee("jj-mm-aaaa") retourne aaaa
   |-------------------------------------------------------------------------------------|
   */
   function annee($strDate) {
      if (substr($strDate, 4, 1) == "-") {
         /* AAAA-MM-JJ */
         $intAnnee = intval(substr($strDate, 0, 4), 10);
      }
      else {
         /* JJ-MM-AAAA */
         $intAnnee = intval(substr($strDate, 6, 4), 10);
      }
      return $intAnnee;
   }
   /* OK
   |-------------------------------------------------------------------------------------|
   | aujourdhui (2017-01-29)
   | > aujourdhui()      => aaaa-mm-jj
   | > aujourdhui(true)  => aaaa-mm-jj
   | > aujourdhui(false) => jj-mm-aaaa
   |-------------------------------------------------------------------------------------|
   */
   function aujourdhui($binAAAAMMJJ=true) {
      $strDate = "";
      if (func_num_args() == 1) {
         $binAAAAMMJJ = func_get_arg(0);
      }
      switch ($binAAAAMMJJ) {
         case true :
            $strDate = date("Y-m-d");
            break;
         case false;
            $strDate = date("d-m-Y");
            break;
      }
      return $strDate;
   }
   /* OK
   |-------------------------------------------------------------------------------------|
   | bissextile (2014-03-31; 2017-01-29)
   |-------------------------------------------------------------------------------------|
   */
   function bissextile($intAnnee) {
      $strDate = strtotime("01-01-$intAnnee");
      return date("L", $strDate);
   }
   /* OK
   |-------------------------------------------------------------------------------------|
   | dateEnLitteral (2018-01-31)
   | > dateEnLitteral()                    Date du jour SANS le jour de la semaine
   | > dateEnLitteral("jj-mm-aaaa")        Date passée en argument SANS le jour de la semaine
   | 
   | > dateEnLitteral("C")                 Date du jour AVEC le jour de la semaine
   | > dateEnLitteral("jj-mm-aaaa", "C")   Date passée en argument AVEC le jour de la semaine
   | > dateEnLitteral("C", "jj-mm-aaaa");  Date passée en argument AVEC le jour de la semaine
   |
   | Notes : Le format de la date peut être "jj-mm-aaaa" ou "aaaa-mm-jj"
   |         Minuscule et majuscule traitées indifféremment 
   |-------------------------------------------------------------------------------------|
    */
   function dateEnLitteral() {
      $strDate = aujourdhui();
      $binJourSemaineDemande = false;
      for ($i=0; $i<func_num_args(); $i++) {
         $strParametre = strtoupper(func_get_arg($i));
         if ($strParametre == "C") {
            $binJourSemaineDemande = true;
         }
         if (dateValide($strParametre)) {
            $strDate = $strParametre;
         }
      }
      extraitJSJJMMAAAAv2($intJourSemaine, $intJour, $intMois, $intAnnee, $strDate);
      $strDateEnLitteral = ($binJourSemaineDemande ? jourSemaineEnLitteral($intJourSemaine, true) . " librairie-exercice01.php" : "") . er($intJour) . " " . moisEnLitteral($intMois) . " " . $intAnnee;
      
      return $strDateEnLitteral;
   }
   /* OK
   |-------------------------------------------------------------------------------------|
   | dateValide (2017-01-29)
   |-------------------------------------------------------------------------------------|
   */
   function dateValide($strDate) {
      $intJour;
      $intMois;
      $intAnnee;
      $intJourSemaine;
      extraitJSJJMMAAAAv2($intJourSemaine, $intJour, $intMois, $intAnnee, $strDate);
      return checkdate($intMois, $intJour, $intAnnee);
   }
   /* OK
   |-------------------------------------------------------------------------------------|
   | extraitJSJJMMAAAAv2 (2017-01-29)
   | Scénarios : extraitJSJJMMAAAAv2($intJourSemaine, $intJour, $intMois, $intAnnee)
   |             où date()
   |             extraitJSJJMMAAAAv2($intJourSemaine, $intJour, $intMois, $intAnnee, $strDate)
   |             où $strDate = "jj-mm-aaaa" ou "aaaa-mm-jj"
   |-------------------------------------------------------------------------------------|
   */
   function extraitJSJJMMAAAAv2(&$intJourSemaine, &$intJour, &$intMois, &$intAnnee) {
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
      
      //if (preg_match("/^\d{4}\-\d{2}\-\d{2}$/", $strDate)) {
      if (substr($strDate, 4, 1) == "-") {
         /* AAAA-MM-JJ */
         $intJour = intval(substr($strDate, 8, 2), 10);
         $intMois = intval(substr($strDate, 5, 2), 10);
         $intAnnee = intval(substr($strDate, 0, 4), 10);
      }
      else {
         /* JJ-MM-AAAA */
         $intJour = intval(substr($strDate, 0, 2), 10);
         $intMois = intval(substr($strDate, 3, 2), 10);
         $intAnnee = intval(substr($strDate, 6, 4), 10);
      }
   }
   /* OK
   |-----------------------------------------------------------------------------------|
   | get (2018-01-31)
   | Scénario : get($strNomParametre) retourne la valeur du paramètre ou NULL
   |-----------------------------------------------------------------------------------|
   */
   function get($strNomParametre) {
      return isset($_GET[$strNomParametre]) ? $_GET[$strNomParametre] : null;
   }
   /* OK
   |-------------------------------------------------------------------------------------|
   | input (2018-02-01)
   |-------------------------------------------------------------------------------------|
   */
   function input($strID, $strCLASS, $strMAXLENGTH, $strVALUE, $binECHO=false) {
      $strINPUT = "<input id=\"$strID\" name=\"$strID\" class=\"$strCLASS\" type=\"text\" maxlength=\"$strMAXLENGTH\" value=\"$strVALUE\" />";
      if ($binECHO) {
         echo $strINPUT;
      }
      else {
         return $strINPUT;
      }
   }
   /* OK
   |-------------------------------------------------------------------------------------|
   | jour (2018-02-05)
   | > jour("aaaa-mm-jj") ou jour("jj-mm-aaaa") retourne jj
   |-------------------------------------------------------------------------------------|
   */
   function jour($strDate) {
      if (substr($strDate, 4, 1) == "-") {
         /* AAAA-MM-JJ */
         $intJour = intval(substr($strDate, 8, 2), 10);
      }
      else {
         /* JJ-MM-AAAA */
         $intJour = intval(substr($strDate, 0, 2), 10);
      }
      return $intJour;
   }
   /* OK
   |-------------------------------------------------------------------------------------|
   | mois (2018-02-05)
   | > mois("aaaa-mm-jj") ou mois("jj-mm-aaaa") retourne mm
   |-------------------------------------------------------------------------------------|
   */
   function mois($strDate) {
      if (substr($strDate, 4, 1) == "-") {
         /* AAAA-MM-JJ */
         $intMois = intval(substr($strDate, 5, 2), 10);
      }
      else {
         /* JJ-MM-AAAA */
         $intMois = intval(substr($strDate, 3, 2), 10);
      }
      return $intMois;
   }
   /* OK
   |-------------------------------------------------------------------------------------|
   | nombreJoursAnnee (2014-04-13)
   |-------------------------------------------------------------------------------------|
   */
   function nombreJoursAnnee($intAnnee) {
      return (bissextile($intAnnee) ? 366 : 365);
   }
   /* OK
   |-------------------------------------------------------------------------------------|
   | nombreJoursEntreDeuxDates (2014-04-13, 2017-01-29)
   | $strDate1 et $strDate2 peuvent être saisis selon le format JJ-MM-AAAA ou AAAA-MM-JJ
   |-------------------------------------------------------------------------------------|
    */
   function nombreJoursEntreDeuxDates($strDate1, $strDate2) {
      /* La date ne doit pas être antérieure au 14 décembre 1901 */
      $intNoSerie1 = strtotime($strDate1) / 86400;
      $intNoSerie2 = strtotime($strDate2) / 86400;
      return round($intNoSerie2 - $intNoSerie1);
   }
   /* OK
   |-------------------------------------------------------------------------------------|
   | nombreJoursMois (2014-04-01; 2017-01-29)
   |-------------------------------------------------------------------------------------|
   */
   function nombreJoursMois($intMois, $intAnnee) {
      $strDate = strtotime("01-$intMois-$intAnnee");
      return date("t", $strDate);
   }
   /* OK
   |-----------------------------------------------------------------------------------|
   | post (2018-01-31)
   | Scénario : post($strNomParametre) retourne la valeur du paramètre ou NULL
   |-----------------------------------------------------------------------------------|
   */
   function post($strNomParametre) {
      return isset($_POST[$strNomParametre]) ? $_POST[$strNomParametre] : null;
   }
?>