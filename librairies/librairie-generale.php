
<?php
    /*
    |-------------------------------------------------------------------------------------|
    | ajouteZeros (2022-mm-jj)
    | Scénario : ajouteZeros($numValeur, $intLargeur)
    |-------------------------------------------------------------------------------------|
    */
    function ajouteZeros($numValeur, $intLargeur) {
        $strFormat = "%0" . $intLargeur . "d";
        return sprintf($strFormat, $numValeur);
    }
    /*
    |-------------------------------------------------------------------------------------|
    | convertitSousChaineEnEntier (2022-01-jj)
    |-------------------------------------------------------------------------------------|
    */
    function convertitSousChaineEnEntier($strChaine, $intDepart, $intLongueur) {
        $intEntier = intval(substr($strChaine, $intDepart, $intLongueur));
        return $intEntier;
    }
    /*
    |-------------------------------------------------------------------------------------|
    | er (2022-01-jj)
    | Scénarios : er($intEntier)
    |             er($intEntier, $binExposant)
    |-------------------------------------------------------------------------------------|
    */
    function er($intEntier, $binExposant=true) {
        return $intEntier . ($intEntier == 1 ? ($binExposant ? "<sup>er</sup>" : "er") : "");
    }

?>
