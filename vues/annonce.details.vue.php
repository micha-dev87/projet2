<?php
// vues/annonce.details.vue.php
$id_annonce = intval($GLOBALS["paramId"]);
$annonce = $GLOBALS["annonceDAO"]->getAnnonceById($id_annonce);
afficheMessageConsole("Annonce : " . print_r($annonce, true));
if ($annonce) :

    $utilisateur     = $GLOBALS["utilisateurDAO"]->utilisateurDetail($annonce->NoUtilisateur);
    afficheMessageConsole("Utilisateur : " . print_r($utilisateur, true));
    if ($annonce) { ?>
        <a href="<?= lien("dashboard"); ?>" class="btn btn-outline-primary">Retour vers le dashboard</a>
        <h2><?= htmlspecialchars($annonce->DescriptionA) ?></h2>
        <img width="600px" src="<?= lien(htmlspecialchars($annonce->Photo)) ?>" alt="<?= htmlspecialchars($annonce->DescriptionA) ?>"
            class="img-fluid mb-3">
        <p><strong>Description :</strong> <?= htmlspecialchars($annonce->Description) ?></p>
        <p><strong>Prix :</strong> <?= htmlspecialchars($annonce->Prix) ?> $ CA</p>
        <p class="card-text Prix"><strong>Publié par l'auteur
                :</strong> <?= htmlspecialchars($utilisateur->prenom) ?> </p>
        <p class="card-text Prix"><strong>Adresse :</strong> <?= htmlspecialchars($utilisateur->courriel) ?> </p>
        <!-- Etat -->
        <p class="card-text"><small class="text-muted Etat"><?= strEtat($annonce->Etat) ?></small></p>
        <p><strong>Date de publication :</strong> <?= htmlspecialchars($annonce->Parution) ?></p>
        <p><strong>Catégorie :</strong> <?= htmlspecialchars($annonce->Categorie) ?></p>
<?php
    } else {
        die("<p class=\"text-danger\">Aucune annonce crée!</p>");
    }
else:
    die("<p class=\"text-danger\">Aucune annonce trouvée!</p>");
endif;
?>

