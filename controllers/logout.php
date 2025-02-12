<?php
/*
|----------------------------------------------------------------------------------|
| Controller logout - deconnection
|----------------------------------------------------------------------------------|
*/

// Your logout logic here
$utilisateurDAO = new UtilisateurDAO();
$utilisateurDAO->deconnecterUtilisateur();

// Output JavaScript for redirection
echo '<script type="text/javascript">
        window.location.href = "' . lien("login") . '";
      </script>';
exit();

?>