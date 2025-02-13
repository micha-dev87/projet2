
<?php
// annonces.vue.php
    global $annonces;
    global $categories;
?>
<h2 class="text-center mb-4">Mes Annonces</h2>

<!-- Barre d'actions -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="annonce/ajouter" class="btn btn-primary">Ajouter une annonce</a>
    </div>
    <div class="d-flex gap-2">

        <!-- Filtre par date -->
        <input type="date" class="form-control" id="dateFiltre" placeholder="Filtrer par date">

        <!-- Filtre par catégorie -->
        <select class="form-select" id="categorieFiltre">
            <option value="">Toutes les catégories</option>
            <?php foreach ($categories as $id => $categorie): ?>
                <option value="<?= intval($id)+1 ?>"><?= htmlspecialchars($categorie) ?></option>
                <?php endforeach; ?>
        </select>

        <!-- Barre de recherche -->
        <input type="text" class="form-control" id="recherche" placeholder="Rechercher...">
        <form >
        <!-- reset -->
        <input type="submit"  onsubmit="this.submit()" class="btn btn-primary" value="Reset">
        </form>
    </div>
</div>

<!-- Liste des annonces en grille -->
<div class="row row-cols-1 row-cols-md-3 g-4" id="listeAnnonces">
    <?php  foreach ($annonces??[] as $annonce): ?>
        <div class="col">
            <div class="card h-100">
                <!-- Image de l'annonce -->
                <img src="<?= lien(htmlspecialchars($annonce->Photo)) ?>" class="card-img-top" alt="<?= htmlspecialchars($annonce->Description) ?>">

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

                <div class="card-footer">
                    <!-- Bouton Détails -->
                    <a href="<?= lien("annonce/details/".htmlspecialchars($annonce->NoAnnonce))?>" class="btn btn-outline-primary w-100">Détails</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

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
            const correspondRecherche = !recherche || descriptionA.includes(recherche)|| descriptionC.includes(recherche);

            if (correspondDate && correspondCategorie && correspondRecherche) {
                annonce.style.display = '';
            } else {
                annonce.style.display = 'none';
            }
        });
    }
</script>