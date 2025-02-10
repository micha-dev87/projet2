<?php
  /*
  |----------------------------------------------------------------------------------|
  | Vue page login
  |----------------------------------------------------------------------------------|
  */


?>
  <div class="container">
        <h2 class="text-center mb-4">Connexion</h2>

        <?php if (!empty($erreur)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($erreur) ?></div>
        <?php endif; ?>

        <form id="loginForm" method="POST" class="p-4" style="<?= $shadowBox." ".$borderRadius. " ".$form_width ?>" action="">
            <!-- Champ Courriel -->
            <div class="mb-3">
                <label for="courriel" class="form-label">Adresse de courriel</label>
                <input type="email" class="form-control" id="courriel" name="courriel" required>
            </div>

            <!-- Champ Mot de passe -->
            <div class="mb-3">
                <label for="mot_de_passe" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" required>
            </div>

            <!-- Bouton Soumettre -->
            <button type="submit" class="btn btn-primary w-100">Se connecter</button>
        </form>

        <div class="text-center mt-3">
            <p class="mt-4 mb-3 ">
                Pas encore inscrit <i class="fas fa-face-smile"></i>  ?
                <a href="<?=SERVER_NAME == 'localhost' ? "/{$uriSegments[0]}/register": "/register"?>" class="link">Inscrivez-vous ici</a>
            </p>
        </div>
    </div>

    <!-- JavaScript pour validation côté client -->
    <script>
        document.getElementById('loginForm').addEventListener('submit', function(event) {
            const courriel = document.getElementById('courriel').value;
            const motDePasse = document.getElementById('mot_de_passe').value;

            // Validation du courriel
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(courriel)) {
                alert("L'adresse de courriel saisie n'est pas valide.");
                event.preventDefault();
                return;
            }

            // Validation du mot de passe
            if (motDePasse.length < 5 || motDePasse.length > 15) {
                alert("Le mot de passe doit contenir entre 5 et 15 caractères.");
                event.preventDefault();
                return;
            }
        });
    </script>