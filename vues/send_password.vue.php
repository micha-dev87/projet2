<?php

?>

<div class="container">
    <h2 class="text-center mb-4">Rehercher le lien pour changer votre mot de passe</h2>
    <?php if (!empty($erreur)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($erreur) ?></div>
    <?php endif; ?>
    <?php if (!empty($succes)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($succes) ?></div>
    <?php endif; ?>
    <form id="registerForm" class="p-4 " style="<?= $shadowBox . " " . $borderRadius ?>" method="POST">
       
        <!-- Champ Courriel -->
        <div class="mb-3">
            <label for="courriel" class="form-label"><i class="fas fa-envelope me-2"></i>Adresse de courriel</label>
            <input type="email" class="form-control" id="courriel" name="courriel" placeholder="exemple@domaine.com" value="<?= htmlspecialchars($courriel ?? '') ?>" required>
        </div>
        <!-- Champ Confirmation Courriel -->
        <div class="mb-3">
            <label for="courriel_confirmation" class="form-label"><i class="fas fa-envelope me-2"></i>Confirmez votre adresse de courriel</label>
            <input type="email" class="form-control" id="courriel_confirmation" name="courriel_confirmation" placeholder="exemple@domaine.com" value="<?= htmlspecialchars($courriel_confirmation ?? '') ?>" required>
        </div>
  
</div>

<!-- Bouton Soumettre -->
<button type="submit" class="btn btn-primary w-100 mt-4">Envoyer</button>


</form>
</div>

<!-- JavaScript pour validation côté client -->
<script>

    document.getElementById('registerForm').addEventListener('submit', function(event) {
        const courriel = document.getElementById('courriel').value;
        const courrielConfirmation = document.getElementById('courriel_confirmation').value;

   
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


    });
</script>

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>