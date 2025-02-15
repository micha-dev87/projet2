<?php

// annonces.vue.php : affichage de la liste des annonces

$titre = is_numeric( $GLOBALS["paramId"]) ? "Mes annonces" : "Liste des annonces";
$totalAnnonces = $GLOBALS["annonceDAO"]->getAnnoncesTotal();
$annonceParPage = 10;
$totalPages = ceil($totalAnnonces / $annonceParPage);

afficheMessageConsole("nombre total d'annonces : " . $totalAnnonces);
afficheMessageConsole("nombre total de pages : " . $totalPages);
if (is_numeric($GLOBALS["paramId"])) {

    $annonces = $GLOBALS["annonceDAO"]->listerAnnoncesPourUtilisateur($GLOBALS["paramId"]);
} else {

    $params = $GLOBALS["paramId"] != null ? explode("=", $GLOBALS["paramId"]) : null;
    if (!is_null($params)) {
        $pageActuelle = intval($params[1]);
    } else {
        $pageActuelle = 1;
    }
    $offset = ($pageActuelle - 1) * $annonceParPage;
    // recuperer les paramatres de pagination dans le lien format p-offset ici offset serat le count
    $annonces = $GLOBALS["annonceDAO"]->listerAnnonces($offset, $annonceParPage);
}
global $categories;
?>
<a href="<?= lien("dashboard"); ?>" class="btn btn-outline-primary">Retour vers le dashboard</a>

<h2 class="text-center mb-4"><?= $titre?></h2>

<!-- Barre d'actions -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="<?= lien("annonce/ajouter") ?>" class="btn btn-primary">Ajouter une annonce</a>
    </div>
    <div class="d-flex gap-2">

        <!-- Filtre par date -->
        <input type="date" class="form-control" id="dateFiltre" placeholder="Filtrer par date">

        <!-- Filtre par catégorie -->
        <select class="form-select" id="categorieFiltre">
            <option value="">Toutes les catégories</option>
            <?php foreach ($categories as $id => $categorie): ?>
                <option value="<?= intval($id) + 1 ?>"><?= htmlspecialchars($categorie) ?></option>
            <?php endforeach; ?>
        </select>

        <!-- Barre de recherche -->
        <input type="text" class="form-control" id="recherche" placeholder="Rechercher...">
        <form>
            <!-- reset -->
            <input type="submit" onsubmit="this.submit()" class="btn btn-primary" value="Reset">
        </form>
    </div>
</div>

<!-- Liste des annonces en grille -->
<div class="row row-cols-1 row-cols-md-3 g-4" id="listeAnnonces">
    <?php foreach ($annonces ?? [] as $annonce): ?>
        <div class="col">
            <div class="card" style="width: 18rem;">
                <!-- Image de l'annonce -->
                <img  src="<?= lien(htmlspecialchars($annonce->Photo)) ?>" class="card-img-top" alt="<?= htmlspecialchars($annonce->Description) ?>">

                <div class="card-body">
                    <!-- Descritpion Abregee -->
                    <h5 class="card-title DescriptionA"><?= htmlspecialchars($annonce->DescriptionA) ?></h5>

                    <!-- Description complete -->
                    <p class="card-text Description"><?= substr(htmlspecialchars($annonce->Description), 0, 50) . '...' ?></p>

                    <!-- Prix -->
                    <p class="card-text Prix"><strong>Prix :</strong> <?= htmlspecialchars($annonce->Prix) ?> $ CA</p>

                    <?php
                    // UtilisateurDAO
                    $utilisateurById = $GLOBALS["utilisateurDAO"]->utilisateurDetail(($annonce->NoUtilisateur))
                    ?>
                    <p class="card-text Prix"><strong>Publié par l'auteur :</strong> <?= htmlspecialchars($utilisateurById->prenom) ?> </p>
                    <p class="card-text Prix"><strong>Adresse :</strong> <?= htmlspecialchars($utilisateurById->courriel) ?> </p>

                    <!-- Date de publication -->
                    <p class="card-text"><small class="text-muted Parution"><?= htmlspecialchars($annonce->Parution) ?></small></p>
                    <!-- Etat -->
                    <p class="card-text"><small class="text-muted Etat"><?= strEtat($annonce->Etat) ?></small></p>
                    <!-- Categories -->
                    <p class="card-text"><small class="text-muted Categroie"><?= htmlspecialchars($annonce->Categorie) ?></small></p>
                </div>

                <div class="card-body">
                    <!-- Bouton Détails -->
                    <a href="<?= lien("annonce/details/" . htmlspecialchars($annonce->NoAnnonce)) ?>" class="btn btn-outline-primary w-100">Détails</a>
                    <!-- if is the owner of the annonce -->
                    <?php if ($annonce->NoUtilisateur == $GLOBALS["paramId"]): ?>
                        <!-- Bouton Modifier -->
                        <a href="<?= lien("annonce/modifier-annonce/" . htmlspecialchars($annonce->NoAnnonce)) ?>" class="btn btn-outline-warning w-100">Modifier</a>
                        <!-- Bouton Supprimer -->
                        <a href="<?= lien("annonce/supprimer-annonce/" . htmlspecialchars($annonce->NoAnnonce)) ?>" class="btn btn-outline-danger w-100">Supprimer</a>
                    <?php endif; ?> <!-- Closing the if statement here for owner check -->

                </div>
            </div>
        </div>
    <?php endforeach; ?>

</div>

<!-- Pagination -->
<?php if (!is_numeric($GLOBALS["paramId"])): ?>
    <div class="pagination justify-content-center mt-4">
        <?php


        // Afficher les liens de pagination
        for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="<?= lien("annonce/liste_annonces/p=" . $i) ?>" class="btn btn-outline-primary mx-1 <?= $i === $pageActuelle ? 'active' : '' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>
<?php endif; ?>

<script>
    // Fonction pour filtrer les annonces
    document.getElementById('dateFiltre').addEventListener('change', filtrerAnnonces);
    document.getElementById('categorieFiltre').addEventListener('change', filtrerAnnonces);
    document.getElementById('recherche').addEventListener('input', filtrerAnnonces);

    function filtrerAnnonces() {
        const dateFiltre = document.getElementById('dateFiltre').value;
        const categorieFiltre = document.getElementById('categorieFiltre').value;
        const recherche = document.getElementById('recherche').value.toLowerCase();

        const annonces = document.querySelectorAll('#listeAnnonces .col');

        annonces.forEach(annonce => {
            const descriptionA = annonce.querySelector('.DescriptionA').innerText.toLowerCase();
            const descriptionC = annonce.querySelector('.Description').innerText.toLowerCase();
            const prix = annonce.querySelector('.Prix').innerText;
            const parution = annonce.querySelector('.Parution').innerText;
            annonce.querySelector('.Etat').innerText.toLowerCase();
            const categorie = annonce.querySelector('.Categroie').innerText.toLowerCase();


            const correspondDate = !dateFiltre || parution.includes(dateFiltre);
            const correspondCategorie = !categorieFiltre || categorie === categorieFiltre;
            const correspondRecherche = !recherche || descriptionA.includes(recherche) || descriptionC.includes(recherche);

            if (correspondDate && correspondCategorie && correspondRecherche) {
                annonce.style.display = '';
            } else {
                annonce.style.display = 'none';
            }
        });
    }
</script>