<?php

// annonces.vue.php : affichage de la liste des annonces

//gestion pagination
$titre = is_numeric( $GLOBALS["paramId"]) ? "Mes annonces" : "Liste des annonces";

global $categories;
?>

<h2 class="text-center mb-4"><?= $titre?></h2>

<!-- Barre d'actions -->

    <div>
        <a href="<?= lien("annonce/ajouter") ?>" class="btn btn-primary">Ajouter une annonce</a>
    </div>

<!-- Système de filtrage avancé -->
<div class="card mb-4">
    <div class="card-body">
        <form id="filterForm" class="row g-3">
            <!-- Recherche -->
            <div class="col-md-4">
                <label for="recherche" class="form-label">Recherche globale</label>
                <input type="text" class="form-control" id="recherche" placeholder="Rechercher...">
            </div>

            <!-- Filtre par catégorie -->
            <div class="col-md-4">
                <label for="categorieFiltre" class="form-label">Catégorie</label>
                <select class="form-select" id="categorieFiltre" >
                    <option value="">Toutes les catégories</option>
                    <?php foreach ($categories as $id => $categorie): ?>
                        <option value="<?= intval($id) + 1 ?>"><?= htmlspecialchars($categorie) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Filtre par date -->
            <div class="col-md-4">
                <label for="dateDebut" class="form-label">Période</label>
                <div class="input-group">
                    <input type="date" class="form-control" id="dateDebut">
                    <span class="input-group-text">au</span>
                    <input type="date" class="form-control" id="dateFin">
                </div>
            </div>

            <!-- Tri -->
            <div class="col-md-4">
                <label for="tri" class="form-label">Trier par</label>
                <select class="form-select" id="tri" onselect="appliquerFiltres()">
             
                    <option   value="date_asc">Date ↑</option>
                    <option selected value="date_desc">Date ↓</option>
                    <option value="auteur_asc">Auteur A-Z</option>
                    <option value="auteur_desc">Auteur Z-A</option>
                    <option value="categorie_asc">Catégorie A-Z</option>
                    <option value="categorie_desc">Catégorie Z-A</option>
                </select>
            </div>

            <!-- Boutons -->
            <div class="col-12">
                <button type="reset" class="btn btn-secondary" onclick="reinitialiserFiltres()">Réinitialiser</button>
            </div>
        </form>
    </div>
</div>
<class="d-flex justify-content-between align-items-center mb-4">
<!-- Liste des annonces en grille -->
<div class="row row-cols-1 row-cols-md-3 g-4" id="listeAnnonces">


</div>
                    </div>
<!-- Pagination -->

    <div class="pagination justify-content-center mt-4">
    </div>
