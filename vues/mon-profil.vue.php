<div class="container mt-4">
    <h2>Mon Profil</h2>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['message']; unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['erreur'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['erreur']; unset($_SESSION['erreur']); ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="mt-4">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" class="form-control" id="nom" name="nom" 
                       value="<?= htmlspecialchars($utilisateur->nom) ?>" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="prenom" class="form-label">Prénom</label>
                <input type="text" class="form-control" id="prenom" name="prenom" 
                       value="<?= htmlspecialchars($utilisateur->prenom) ?>" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" 
                       value="<?= htmlspecialchars($utilisateur->courriel) ?>" readonly>
            </div>

            <div class="col-md-6 mb-3">
                <label for="statut" class="form-label">Statut</label>
                <select class="form-select" id="statut" name="statut" required>
                    <?php foreach (range(2, 5) as $index): ?>
                        <option value="<?= $index ?>" <?= $utilisateur->statut == $index ? 'selected' : '' ?>>
                            <?= strStatut($index) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-4 mb-3">
                <label for="telMaison" class="form-label">Téléphone Maison</label>
                <input type="tel" class="form-control" id="telMaison" name="telMaison" 
                       value="<?= htmlspecialchars($utilisateur->no_tel_maison) ?>"
                       pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}">
                <small class="form-text text-muted">Format: 123-456-7890</small>
            </div>

            <div class="col-md-4 mb-3">
                <label for="telCellulaire" class="form-label">Téléphone Cellulaire</label>
                <input type="tel" class="form-control" id="telCellulaire" name="telCellulaire" 
                       value="<?= htmlspecialchars($utilisateur->no_tel_cellulaire) ?>"
                       pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}">
            </div>

            <div class="col-md-4 mb-3">
                <label for="telTravail" class="form-label">Téléphone Travail</label>
                <input type="tel" class="form-control" id="telTravail" name="telTravail" 
                       value="<?= htmlspecialchars($utilisateur->no_tel_travail) ?>"
                       pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}">
            </div>

            <div class="col-12 mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="hideinfospersonnel" 
                           name="hideinfospersonnel" 
                           <?= $utilisateur->autreinfos === 'hideinfospersonnel' ? 'checked' : '' ?>>
                    <label class="form-check-label" for="hideinfospersonnel">
                        Masquer mes informations personnelles dans les annonces
                    </label>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
        <a href="<?= lien('dashboard') ?>" class="btn btn-secondary">Retour</a>
    </form>
</div>

<script>
// Formatter les champs téléphone
document.querySelectorAll('input[type="tel"]').forEach(input => {
    input.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '').substring(0,10);
        if (value.length >= 6) {
            value = value.replace(/(\d{3})(\d{3})(\d{4})/, "$1-$2-$3");
        } else if (value.length >= 3) {
            value = value.replace(/(\d{3})(\d{0,3})/, "$1-$2");
        }
        e.target.value = value;
    });
});
</script>