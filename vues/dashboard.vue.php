
<?php
// dashboard.vue.php
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
            <?php foreach ($categories??[] as $categorie): ?>
                <option value="<?= htmlspecialchars($categorie['id']) ?>"><?= htmlspecialchars($categorie['nom']) ?></option>
            <?php endforeach; ?>
        </select>

        <!-- Barre de recherche -->
        <input type="text" class="form-control" id="recherche" placeholder="Rechercher...">
    </div>
</div>

<!-- Liste des annonces en grille -->
<div class="row row-cols-1 row-cols-md-3 g-4" id="listeAnnonces">
    <?php foreach ($annonces??[] as $annonce): ?>
        <div class="col">
            <div class="card h-100">
                <!-- Image de l'annonce -->
                <img src="<?= htmlspecialchars($annonce->photo) ?>" class="card-img-top" alt="<?= htmlspecialchars($annonce->titre) ?>">

                <div class="card-body">
                    <!-- Titre de l'annonce -->
                    <h5 class="card-title"><?= htmlspecialchars($annonce->titre) ?></h5>

                    <!-- Description abrégée -->
                    <p class="card-text"><?= substr(htmlspecialchars($annonce->description), 0, 50) . '...' ?></p>

                    <!-- Prix -->
                    <p class="card-text"><strong>Prix :</strong> <?= htmlspecialchars($annonce->prix) ?> €</p>

                    <!-- Date de publication -->
                    <p class="card-text"><small class="text-muted"><?= htmlspecialchars($annonce->date_parution) ?></small></p>
                </div>

                <div class="card-footer">
                    <!-- Bouton Détails -->
                    <a href="/annonce/details/<?= htmlspecialchars($annonce->id) ?>" class="btn btn-outline-primary w-100">Détails</a>
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
            const titre = annonce.querySelector('.card-title').innerText.toLowerCase();
            const dateParution = annonce.querySelector('.text-muted').innerText;
            const categorie = annonce.dataset.categorie;

            const correspondDate = !dateFiltre || dateParution.includes(dateFiltre);
            const correspondCategorie = !categorieFiltre || categorie == categorieFiltre;
            const correspondRecherche = !recherche || titre.includes(recherche);

            if (correspondDate && correspondCategorie && correspondRecherche) {
                annonce.style.display = '';
            } else {
                annonce.style.display = 'none';
            }
        });
    }
</script>