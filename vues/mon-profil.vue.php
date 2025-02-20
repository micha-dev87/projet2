<!-- Container principal avec marge supérieure -->
<div class="container mt-4">
    <h2>Mon Profil</h2>

    <!-- Affichage des messages de succès -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['message']; unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>

    <!-- Affichage des messages d'erreur -->
    <?php if (isset($_SESSION['erreur'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['erreur']; unset($_SESSION['erreur']); ?>
        </div>
    <?php endif; ?>

    <!-- Formulaire de profil -->
    <form method="POST" class="mt-4">
        <div class="row">
            <!-- Champs pour le nom -->
            <div class="col-md-6 mb-3">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" class="form-control" id="nom" name="nom" 
                       value="<?= htmlspecialchars(isset($utilisateur->nom) ? $utilisateur->nom : '') ?>" required>
            </div>

            <!-- Champs pour le prénom -->
            <div class="col-md-6 mb-3">
                <label for="prenom" class="form-label">Prénom</label>
                <input type="text" class="form-control" id="prenom" name="prenom" 
                       value="<?= htmlspecialchars(isset($utilisateur->prenom) ? $utilisateur->prenom : '') ?>" required>
            </div>

            <!-- Champs pour l'email (en lecture seule) -->
            <div class="col-md-6 mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" 
                       value="<?= htmlspecialchars(isset($utilisateur->courriel) ? $utilisateur->courriel : '') ?>" readonly>
            </div>

            <!-- Menu déroulant pour le statut -->
            <div class="col-md-6 mb-3">
                <label for="statut" class="form-label">Statut</label>
                <select class="form-select" id="statut" name="statut" required>
                    <?php foreach ([9, 2, 3, 4, 5] as $index): ?>
                        <option value="<?= $index ?>" <?= (isset($utilisateur->statut) && $utilisateur->statut == $index) ? 'selected' : '' ?>>
                            <?= isset($index) ? strStatut($index) : '' ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Champs pour le numéro d'employé -->
            <div class="col-md-6 mb-3">
                <label for="NoEmpl" class="form-label">Numéro d'employé</label>
                <input type="text" class="form-control" id="NoEmpl" name="NoEmpl" 
                       value="<?= isset($utilisateur->NoEmpl) ? ajouteZeros($utilisateur->NoEmpl, 3) : '' ?>" required>
            </div>

            <!-- Champs pour le téléphone maison -->
            <div class="col-md-4 mb-3">
                <label for="telMaison" class="form-label">Téléphone Maison</label>
                <input type="tel" class="form-control" id="telMaison" name="telMaison" 
                       value="<?= htmlspecialchars($utilisateur->no_tel_maison ?? '') ?>"
                       pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}">
                <small class="form-text text-muted">Format: 123-456-7890</small>
            </div>

            <!-- Champs pour le téléphone cellulaire -->
            <div class="col-md-4 mb-3">
                <label for="telCellulaire" class="form-label">Téléphone Cellulaire</label>
                <input type="tel" class="form-control" id="telCellulaire" name="telCellulaire" 
                       value="<?= htmlspecialchars($utilisateur->no_tel_cellulaire ?? '') ?>"
                       pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}">
            </div>
    
            <!-- Champs pour le poste téléphonique -->
            <div class="col-md-2 mb-3">
                <label for="postTelTravail" class="form-label">Poste</label>
                <input type="text" class="form-control" id="postTelTravail" name="postTelTravail"
                       value="<?= htmlspecialchars($post_tel_travail ?? '') ?>"
                       placeholder="1234">
            </div>

            <!-- Champs pour le téléphone travail -->
            <div class="col-md-2 mb-3">
                <label for="numTelTravail" class="form-label">Téléphone Travail</label>
                <input type="tel" class="form-control" id="numTelTravail" name="numTelTravail"
                       value="<?= htmlspecialchars($numero_tel_travail ?? '') ?>"
                       pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"
                       placeholder="123-456-7890">
            </div>

            <!-- Options de confidentialité -->
            <div class="col-12 mb-3">
            <h5><i class="fas fa-user-shield"></i> Options de confidentialité</h5>
                <!-- Option pour masquer les numéros de téléphone -->
                <div class="form-check"></div>
                    <input class="form-check-input" type="checkbox" id="hidePhone" 
                           name="hidePhone" 
                           <?= isset($utilisateur->autreinfos) && str_contains($utilisateur->autreinfos, 'hidePhone') ? 'checked' : '' ?>>
                    <label class="form-check-label" for="hidePhone">
                        Masquer mes numéros de téléphone dans les annonces
                    </label>
                </div>
                <!-- Option pour masquer le courriel -->
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="hideCourriel" 
                           name="hideCourriel" 
                           <?= isset($utilisateur->autreinfos) && str_contains($utilisateur->autreinfos, 'hideCourriel') ? 'checked' : '' ?>>
                    <label class="form-check-label" for="hideCourriel">
                        Masquer mon courriel dans les annonces
                    </label>
                </div>
                <!-- Option pour masquer le prénom -->
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="hidePrenom" 
                           name="hidePrenom" 
                           <?= isset($utilisateur->autreinfos) && str_contains($utilisateur->autreinfos, 'hidePrenom') ? 'checked' : '' ?>>
                    <label class="form-check-label" for="hidePrenom">
                        Masquer mon prénom dans les annonces
                    </label>
                </div>
            </div>
        </div>

        <!-- Boutons de soumission et de retour -->
        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
        
        <a href="<?= lien('dashboard') ?>" class="btn btn-secondary">Retour</a>
        <div>
            
            Mot de passe oublié ? <i class="fas fa-face-sad-tear"></i> <a href="<?= lien("utilisateur/send_password") ?>" class="link">Récupérer le mot de passe</a>
        </div>
    </form>
</div>

<script>
// Script JavaScript pour formater automatiquement les numéros de téléphone
document.querySelectorAll('input[type="tel"]').forEach(input => {
    input.addEventListener('input', function(e) {
        // Supprime tous les caractères non numériques et limite à 10 chiffres
        let value = e.target.value.replace(/\D/g, '').substring(0,10);
        // Formate le numéro selon le pattern XXX-XXX-XXXX
        if (value.length >= 6) {
            value = value.replace(/(\d{3})(\d{3})(\d{4})/, "$1-$2-$3");
        } else if (value.length >= 3) {
            value = value.replace(/(\d{3})(\d{0,3})/, "$1-$2");
        }
        e.target.value = value;
    });
});
</script>