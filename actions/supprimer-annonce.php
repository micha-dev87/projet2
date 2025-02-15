<?php
// recuper l'id de lannonce à supprimer dans l'url avec $GLOABLS['paramId'] et changer l'état de l'annonce avec la fonction changerEtatAnnonce(id, etat)
// à 2 et rediriger vers le dashboard avec un message de succès

$annonceDAO = new AnnonceDAO();
$id = $GLOBALS['paramId'];
$result = $annonceDAO->changerEtatAnnonce($id, 3);
if($result){
    echo '<script type="text/javascript">
 window.location.href = "' . lien("annonce/liste_annonces/".NO_UTILISATEUR) . '";
  </script>';
    exit(); 
}else{
    echo '<script type="text/javascript">
    alert("iMpossible de supprimer l\'annonce");
    window.location.href = "' . lien("annonce/liste_annonces/".NO_UTILISATEUR) . '";
  </script>';
    exit(); 
}

