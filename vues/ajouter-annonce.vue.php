<?php
// vues/ajouter-annonce.vue.php
?>
<a class="btn btn-outline-primary" href="<?= lien("annonce/liste_annonces/".NO_UTILISATEUR); ?>">Retourner vers le mes annonces</a>
<h2 class="text-center mb-4">Ajouter une Annonce</h2>
<?php if (!empty($erreur)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($erreur) ?></div>
<?php endif; ?>
<?php if (!empty($succes)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($succes) ?></div>
<?php endif; ?>
<!-- Formulaire d'ajout d'une annonce -->
<form id="formAjouterAnnonce" method="post" action="<?= lien($GLOBALS["controller"]."/".$GLOBALS["action"]."/".$GLOBALS['paramId']); ?>" enctype="multipart/form-data">
    <div class="row g-3">
        <!-- Champ Description Abrégée -->
        <div class="col-md-6">
            <label for="descriptionA" class="form-label">Description Abrégée</label>
            <input type="text" class="form-control" id="descriptionA" name="DescriptionA"
                value="<?= $DescriptionA ?? ""; ?>" required>
        </div>
        <!-- Champ Description Complète -->
        <div class="col-md-12">
            <label for="description" class="form-label">Description Complète</label>
            <textarea class="form-control" id="description" name="Description" rows="3"
                required><?= $Description ?? ""; ?></textarea>
        </div>
        <!-- Champ Prix -->
        <div class="col-md-4">
            <label for="prix" class="form-label">Prix</label>
            <input type="number" step="0.01" class="form-control" id="prix" value="<?= $Prix ?? ""; ?>" name="Prix"
                required>
        </div>
        <!-- Champ Date de Parution -->
        <div class="col-md-4">
            <label for="parution" class="form-label">Date de Parution</label>
            <input type="date" class="form-control" id="parution" name="Parution" value="<?= date('Y-m-d') ?>" required>
        </div>
        <!-- Champ État -->
        <div class="col-md-4">
            <label for="etat" class="form-label">État</label>
            <select class="form-select" id="etat" name="Etat" required>
          
                <?php foreach (range(1, 3) as $id): ?>
                    <option value="<?= $id; ?>" <?= isset($Etat) && $Etat == $id || $id==1 ? 'selected' : ''; ?>><?= strEtat($id); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <!-- Champ Photo -->
        <div class="col-md-6">
            <label for="photo" class="form-label">Photo (fichier image)</label>
            <input type="file" class="form-control" id="photo" name="Photo" accept="image/*" 
                   data-default-file="<?= $photoURL ?>" <?= isset($photoURL) ? "" : "required"?>>

            <?php if (isset($photoURL)): ?>
                <img src="<?= lien(htmlspecialchars($photoURL)) ?>" alt="Image actuelle" style="max-width: 150px; margin-top: 10px;">
            <?php endif; ?>
        </div>
        <!-- Champ Catégorie -->
        <div class="col-md-6">
            <label for="categorie" class="form-label">Catégorie</label>
            <select class="form-select" id="categorie" name="Categorie" required>
                <?php foreach ($GLOBALS["categories"] as $i => $categorie): ?>
                    <option value="<?= intval($i) + 1 ?>" <?= isset($Categorie) && $Categorie == intval($i) + 1 ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($categorie) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <!-- Bouton Soumettre -->
    <div class="mt-4 text-center">
        <button type="submit" class="btn btn-primary w-50"><?= $GLOBALS["btn_add_annonce"] ?></button>
    </div>
</form>


<script>
    // Validation côté client avant soumission
    document.getElementById('formAjouterAnnonce').addEventListener('submit', function(event) {
        const descriptionA = document.getElementById('descriptionA').value.trim();
        const description = document.getElementById('description').value.trim();
        const prix = parseFloat(document.getElementById('prix').value);
        const parution = document.getElementById('parution').value;
        const etat = document.getElementById('etat').value;
        const photo = document.getElementById('photo').files[0];
        const categorie = document.getElementById('categorie').value;

        // Validation de la Description Abrégée
        if (descriptionA.length < 10 || descriptionA.length > 200) {
            alert("La description abrégée doit contenir entre 10 et 200 caractères.");
            event.preventDefault();
            return;
        }

        // Validation de la Description Complète
        if (description.length < 20 || description.length > 1000) {
            alert("La description complète doit contenir entre 20 et 1000 caractères.");
            event.preventDefault();
            return;
        }

        // Validation du Prix
        if (isNaN(prix) || prix <= 0) {
            alert("Le prix doit être un nombre positif.");
            event.preventDefault();
            return;
        }

        // Validation de la Date de Parution
        const today = new Date().toISOString().split('T')[0];
        if (parution > today) {
            alert("La date de parution ne peut pas être dans le futur.");
            event.preventDefault();
            return;
        }

       <?php if(!isset($photoURL)): ?>
        // Validation de la Photo
        if (!photo || !photo.type.startsWith('image/')) {
            alert("Veuillez sélectionner une image valide.");
            event.preventDefault();
            return;
        }
        <?php endif; ?>
    });
</script>