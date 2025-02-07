<div class="container">
    <h2 class="text-center mb-4">Inscription</h2>
    <?php if (!empty($erreur)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($erreur) ?></div>
    <?php endif; ?>
    <?php if (!empty($succes)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($succes) ?></div>
    <?php endif; ?>
    <form id="registerForm" class="p-4 " style="<?=$shadowBox?>" method="POST">
        <!-- Champ Nom -->
        <div class="mb-3">
            <label for="nom" class="form-label"><i class="fas fa-user me-2"></i>Nom</label>
            <input type="text" class="form-control" id="nom" name="nom" placeholder="Entrez votre nom" required>
        </div>
        <!-- Champ Prénom -->
        <div class="mb-3">
            <label for="prenom" class="form-label"><i class="fas fa-user me-2"></i>Prénom</label>
            <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Entrez votre prénom" required>
        </div>
        <!-- Champ Courriel -->
        <div class="mb-3">
            <label for="courriel" class="form-label"><i class="fas fa-envelope me-2"></i>Adresse de courriel</label>
            <input type="email" class="form-control" id="courriel" name="courriel" placeholder="exemple@domaine.com" required>
        </div>
        <!-- Champ Confirmation Courriel -->
        <div class="mb-3">
            <label for="courriel_confirmation" class="form-label"><i class="fas fa-envelope me-2"></i>Confirmez votre adresse de courriel</label>
            <input type="email" class="form-control" id="courriel_confirmation" name="courriel_confirmation" placeholder="exemple@domaine.com" required>
        </div>
        <!-- Champ Mot de passe -->
        <div class="mb-3">
            <label for="mot_de_passe" class="form-label"><i class="fas fa-lock me-2"></i>Mot de passe</label>
            <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" placeholder="Mot de passe" required>
            <small class="text-muted">Entre 5 et 15 caractères, avec des lettres et chiffres combinés.</small>
        </div>
        <!-- Champ Confirmation Mot de passe -->
        <div class="mb-3">
            <label for="mot_de_passe_confirmation" class="form-label"><i class="fas fa-lock me-2"></i>Confirmez votre mot de passe</label>
            <input type="password" class="form-control" id="mot_de_passe_confirmation" name="mot_de_passe_confirmation" placeholder="Confirmez le mot de passe" required>
        </div>
        <!-- Champ NoTelMaison -->
        <div class="mb-3">
            <label for="no_tel_maison" class="form-label"><i class="fas fa-phone me-2"></i>Téléphone (maison)</label>
            <input type="text" class="form-control" id="no_tel_maison" name="no_tel_maison" placeholder="(999) 999-9999P | N">
        </div>
        <!-- Champ NoTelTravail -->
        <div class="mb-3">
            <label for="no_tel_travail" class="form-label"><i class="fas fa-phone me-2"></i>Téléphone (travail)</label>
            <input type="text" class="form-control" id="no_tel_travail" name="no_tel_travail" placeholder="(999) 999-9999 #9999P | N">
        </div>
        <!-- Champ NoTelCellulaire -->
        <div class="mb-3">
            <label for="no_tel_cellulaire" class="form-label"><i class="fas fa-mobile-alt me-2"></i>Téléphone (cellulaire)</label>
            <input type="text" class="form-control" id="no_tel_cellulaire" name="no_tel_cellulaire" placeholder="(999) 999-9999P | N">
        </div>
        <!-- Bouton Soumettre -->
        <button type="submit" class="btn btn-primary w-100 mt-4">S'inscrire</button>
        <p class="mt-4 mb-3 ">
            Déjà membre <i class="fas fa-face-smile"></i>  ? <a href="<?=SERVER_NAME == 'localhost' ? "/{$uriSegments[0]}/login": "/login"?>" class="link">Connectez-vous</a>
        </p>
    </form>
</div>

<!-- JavaScript pour validation côté client -->
<script>
    document.getElementById('registerForm').addEventListener('submit', function(event) {
        const nom = document.getElementById('nom').value;
        const prenom = document.getElementById('prenom').value;
        const courriel = document.getElementById('courriel').value;
        const courrielConfirmation = document.getElementById('courriel_confirmation').value;
        const motDePasse = document.getElementById('mot_de_passe').value;
        const motDePasseConfirmation = document.getElementById('mot_de_passe_confirmation').value;

        // Validation du nom et prénom
        if (!/^[a-zA-ZÀ-ÿ][a-zA-ZÀ-ÿ \-'’]*[a-zA-ZÀ-ÿ]$/.test(nom)) {
            alert("Le nom doit commencer par une lettre et ne peut contenir que des lettres, espaces, tirets ou apostrophes.");
            event.preventDefault();
            return;
        }
        if (!/^[a-zA-ZÀ-ÿ][a-zA-ZÀ-ÿ \-'’]*[a-zA-ZÀ-ÿ]$/.test(prenom)) {
            alert("Le prénom doit commencer par une lettre et ne peut contenir que des lettres, espaces, tirets ou apostrophes.");
            event.preventDefault();
            return;
        }

        // Validation du courriel
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(courriel)) {
            alert("L'adresse de courriel saisie n'est pas valide.");
            event.preventDefault();
            return;
        }

        // Validation de la correspondance des courriels
        if (courriel !== courrielConfirmation) {
            alert("Les deux adresses de courriel ne correspondent pas.");
            event.preventDefault();
            return;
        }

        // Validation du mot de passe
        if (motDePasse.length < 5 || motDePasse.length > 15 || !/[a-zA-Z]/.test(motDePasse) || !/[0-9]/.test(motDePasse)) {
            alert("Le mot de passe doit contenir entre 5 et 15 caractères, avec des lettres et chiffres combinés.");
            event.preventDefault();
            return;
        }

        // Validation de la correspondance des mots de passe
        if (motDePasse !== motDePasseConfirmation) {
            alert("Les deux mots de passe ne correspondent pas.");
            event.preventDefault();
            return;
        }
    });
</script>

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>