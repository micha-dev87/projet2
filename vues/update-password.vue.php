<?php

?>

<div class="container">
    <h2 class="text-center mb-4">Modification du mot de passe</h2>
    <?php if (!empty($erreur)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($erreur) ?></div>
    <?php endif; ?>
    <?php if (!empty($succes)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($succes) ?></div>
    <?php endif; ?>
    <form id="registerForm" class="p-4 " style="<?= $shadowBox . " " . $borderRadius ?>" method="POST">
        
        <!-- Champ Mot de passe -->
        <div class="mb-3">
            <label for="mot_de_passe" class="form-label"><i class="fas fa-lock me-2"></i>Mot de passe</label>
            <div class="input-group">
            <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" placeholder="Mot de passe" value="<?= htmlspecialchars($mot_de_passe ?? '') ?>" required>
                <span class="input-group-text" onclick="togglePassword('mot_de_passe')">
                    <i class="fas fa-eye" id="mot_de_passe_icon"></i>
                </span>
            </div>
            <small class="text-muted">Entre 5 et 15 caractères, avec des lettres et chiffres combinés.</small>
        </div>
        <!-- Champ Confirmation Mot de passe -->
        <div class="mb-3">
            <label for="mot_de_passe_confirmation" class="form-label"><i class="fas fa-lock me-2"></i>Confirmez votre mot de passe</label>
            <div class="input-group">
                <input type="password" class="form-control" id="mot_de_passe_confirmation" name="mot_de_passe_confirmation" placeholder="Confirmez le mot de passe" value="<?= htmlspecialchars($mot_de_passe_confirmation ?? '') ?>" required>
                <span class="input-group-text" onclick="togglePassword('mot_de_passe_confirmation')">
                    <i class="fas fa-eye" id="mot_de_passe_confirmation_icon"></i>
                </span>
            </div>
        </div>
</div>

<!-- Bouton Soumettre -->
<button type="submit" class="btn btn-primary w-100 mt-4">Modifier le mot de passe</button>


</form>
</div>

<!-- JavaScript pour validation côté client -->
<script>
    //implementer la fonction togglePassword
    function togglePassword(id) {
        const input = document.getElementById(id);
        const icon = document.getElementById(id + '_icon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
    document.getElementById('registerForm').addEventListener('submit', function(event) {

        const motDePasse = document.getElementById('mot_de_passe').value;
        const motDePasseConfirmation = document.getElementById('mot_de_passe_confirmation').value;

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