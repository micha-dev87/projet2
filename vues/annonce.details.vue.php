<?php
// vues/annonce.details.vue.php
$id_annonce = intval($GLOBALS["paramId"]);
$annonce = $GLOBALS["annonceDAO"]->getAnnonceById($id_annonce);
afficheMessageConsole("Annonce : " . print_r($annonce, true));
if ($annonce) :

    $utilisateur     = $GLOBALS["utilisateurDAO"]->utilisateurDetail($annonce->NoUtilisateur);
    afficheMessageConsole("Utilisateur : " . print_r($utilisateur, true));
    if ($annonce) {
               //Extrait le temps
       $time = date('H\h i', strtotime($annonce->Parution));
       //extraire la date
        $date = date('d-m-Y', strtotime($annonce->Parution));
        extraitJSJJMMAAAA($intSemaine, $jour, $mois, $annee, $date);
        
        
        ?>
        <a href="<?= lien("annonce/liste_annonces"); ?>" class="btn btn-outline-primary">Retour vers les annonces</a>
        <div class="col-md-6" style="width: 60rem;">
            <h2 class="text-center mb-4">Détails de l'annonce</h2>
            <h2 class="pb-2"><?= htmlspecialchars($annonce->DescriptionA) ?></h2>
            <!-- Image de l'annonce -->
            <img style="height: 600px!important; object-fit: cover;" src="<?php

                                                                            // si la "Photo" de contient http ou https
                                                                            echo (str_contains($annonce->Photo, 'http') || str_contains($annonce->Photo, 'https')) ? htmlspecialchars($annonce->Photo) :  lien(htmlspecialchars($annonce->Photo));
                                                                            ?>" class="card-img-top" alt="<?= htmlspecialchars($annonce->Description) ?>">
            <p class="py-2"><strong>Description :</strong> <?= htmlspecialchars($annonce->Description) ?></p>
            <p><strong>Prix :</strong> <?= htmlspecialchars($annonce->Prix) ?> $ CA</p>
            <p class="card-text Prix"><strong>Publié par l'auteur
                    :</strong> <?= htmlspecialchars($utilisateur->prenom) ?> </p>
            <p class="card-text Prix"><strong>Adresse :</strong> <?= htmlspecialchars($utilisateur->courriel) ?> </p>
            <!-- Etat -->

                    <!-- Date de publication -->
                    <p class="card-text"><small class="text-muted Parution"><strong>Parution :</strong> <?=
                        jourSemaineEnLitteral($intSemaine)." le ".$jour." ".moisEnLitteral($mois)." ".$annee." à ".$time
                        
                        ?></small></p>
                    <!-- Etat -->
                    <p class="card-text"><small class="text-muted Etat"><strong>Etat :</strong><?= strEtat($annonce->Etat) ?></small></p>
                    <!-- Categories -->
                    <p class="card-text"><small class="text-muted Categroie"><strong>Catégorie :</strong><?= htmlspecialchars($annonce->Categorie) ?></small></p>
                </div>
<?php
    } else {
        die("<p class=\"text-danger\">Aucune annonce crée!</p>");
    }
else:
    die("<p class=\"text-danger\">Aucune annonce trouvée!</p>");
endif;
?>