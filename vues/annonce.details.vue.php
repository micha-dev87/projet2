<?php
// vues/annonce.details.vue.php
$id_annonce = intval($GLOBALS["paramId"]);
$annonce = $GLOBALS["annonceDAO"]->getAnnonceById($id_annonce);

if ($annonce) :

    $utilisateur     = $GLOBALS["utilisateurDAO"]->utilisateurDetail($annonce->NoUtilisateur);
   

            // Format the date
            $date = new DateTime($annonce->Parution);
            $dateFormatee = $date->format('Y-m-d');

            // Check personal info visibility
            $donneesPersonnelles = '';
            $courrielInfo = '';
            $telephoneInfo = '';

            if (!isset($annonce->autresInfos) || !stripos($annonce->autresInfos, 'hidePrenom')) {
                $donneesPersonnelles = "<p class='card-text'><small class='text-muted'><strong>Publié par l'auteur:</strong> " . htmlspecialchars($utilisateur->prenom) . "</small></p>";
            }

            if (!isset($annonce->autresInfos) || !stripos($annonce->autresInfos, 'hideCourriel')) {
                $courrielInfo = "<p class='card-text'><small class='text-muted'><strong>Courriel de l'auteur:</strong> " . htmlspecialchars($utilisateur->courriel) . "</small></p>";
            }

            if (!isset($annonce->autresInfos) || !stripos($annonce->autresInfos, 'hidePhone')) {
                $telephoneInfo = "<p class='card-text'><small class='text-muted'><strong>Téléphone de l'auteur:</strong> " . 
                    htmlspecialchars(
                        !empty($utilisateur->no_tel_cellulaire) ? $utilisateur->no_tel_cellulaire : 
                        (!empty($utilisateur->no_tel_maison) ? $utilisateur->no_tel_maison : 
                        ($utilisateur->no_tel_travail ?? ''))
                    ) . "</small></p>";
            }
        ?>
        <div class="col">
            <div class="card h-100">
                <img class="card-img-top" 
                     style="height: 200px; object-fit: cover;" 
                     src="<?= (str_contains($annonce->Photo, 'http') || str_contains($annonce->Photo, 'https')) ? htmlspecialchars($annonce->Photo) : lien(htmlspecialchars($annonce->Photo)) ?>" 
                     alt="<?= htmlspecialchars($annonce->DescriptionA) ?>">
                
                <div class="card-body">
                    <p class="card-text"><strong>Id :</strong> <?= $annonce->NoAnnonce ?></p>
                    <h5 class="card-title"><?= htmlspecialchars($annonce->DescriptionA) ?></h5>
                    <p class="card-text"><?= htmlspecialchars(substr($annonce->Description, 0, 100)) ?>...</p>
                    <p class="card-text"><strong>Prix:</strong> <?= htmlspecialchars($annonce->Prix) ?> $ CA</p>
                    <p class="card-text"></p>
                        <small class="text-muted"><strong>Publié le</strong> <?= date('d F Y', strtotime($dateFormatee)) ?></small>
                    </p>
                    <p class="card-text">
                        <small class="text-muted"><strong>Catégorie:</strong> <?= htmlspecialchars($annonce->Categorie) ?></small>
                    </p>
                    <p class="card-text">
                        <small class="text-muted"><strong>Etat :</strong> <?= strEtat($annonce->Etat) ?></small>
                    </p>
                    <?= $donneesPersonnelles ?>
                    <?= $courrielInfo ?>
                    <?= $telephoneInfo ?>
                </div>
                
                <div class="card-footer">
                    <div class="d-grid gap-2">
                        <a href="<?= lien("annonce/liste_annonces") ?>" class="btn btn-outline-primary">Retour vers les annonces</a>
                    </div>
                </div>
            </div>
        </div>





<?php
 
else:
    die("<p class=\"text-danger\">Aucune annonce trouvée!</p>");
endif;
?>