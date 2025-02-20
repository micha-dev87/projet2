<?php
    // Liste des utilisateurs
            //liste des utilisateur :::: administrateur
            $listeUtilisateurs = $GLOBALS["utilisateurDAO"]->listerUtilisateurs();
?>
<h2 class="text-center mb-4">Tableau de bord Administrateur</h2>

<!-- Tableau des utilisateurs -->
<table class="table table-striped table-hover w-100">
    <thead>
    <tr>
        <th>#</th>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Courriel</th>
        <th>Statut</th>
        <th>Nombre de connexions</th>
        <th>Date de connexion</th>
        <th>Date de déconnexoin</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
        <?php 
      
   foreach ($listeUtilisateurs as $utilisateur): 
       
        $connexions = $GLOBALS["connexionDAO"]->getAllConnexionById($utilisateur->NoUtilisateur);
        $nombreConnexions = count($connexions);
        $dernierConnexion = end($connexions);
        $dateConnexion = $dernierConnexion ? htmlspecialchars($dernierConnexion['Connexion']) : 'Aucune connexion';
        $dateDeconnexion = $dernierConnexion && isset($dernierConnexion['Deconnexion']) ? htmlspecialchars($dernierConnexion['Deconnexion']) : 'Aucune déconnexion';
        ?>
        <tr>
            <td><?= htmlspecialchars($utilisateur->NoUtilisateur) ?></td>
            <td><?= htmlspecialchars($utilisateur->nom) ?></td>
            <td><?= htmlspecialchars($utilisateur->prenom) ?></td>
            <td><?= htmlspecialchars($utilisateur->courriel) ?></td>
            <td><?= strStatut(intval($utilisateur->statut)) ?></td>
            <td><?= $nombreConnexions ?></td>
            <td><?= $dateConnexion ?></td>
            <td><?= $dateDeconnexion ?></td>
            <td>
                <!-- Bouton pour ouvrir le modal -->
                <button type="button" data-bs-toggle="modal" data-bs-target="#status-change-<?= $utilisateur->NoUtilisateur ?>" class="btn btn-sm btn-success">
                    <i class="fas fa-user-check" aria-hidden="true"></i>
                    <span class="visually-hidden">Modifier le statut</span>
                </button>
                <!-- Icône pour supprimer -->
                <button type="button" class="btn btn-sm btn-danger" onclick="supprimerUtilisateur(<?= $utilisateur->NoUtilisateur ?>)">
                    <i class="fas fa-trash" aria-hidden="true"></i>
                    <span class="visually-hidden">Supprimer l'utilisateur</span>
                </button>
            </td>
        </tr>

        <!-- Modal pour changer le statut -->
        <div class="modal fade" id="status-change-<?= $utilisateur->NoUtilisateur ?>" tabindex="-1" aria-labelledby="status-change-label-<?= $utilisateur->NoUtilisateur ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="status-change-label-<?= $utilisateur->NoUtilisateur ?>">Modifier le statut</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formChangerStatut" method="POST" action="utilisateur/validateUser">
                            <input type="hidden" name="no_utilisateur" value="<?= $utilisateur->NoUtilisateur ?>">
                            <div class="mb-3">
                                <label for="statut" class="form-label">Nouveau statut</label>
                                <select class="form-select" id="statut" name="statut">
                                    <?php foreach (range(0, 5) as $index): ?>
                                        <option value="<?= $index ?>"><?= strStatut($index) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">OK</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    </tbody>
</table>

<script>
    // Fonction pour supprimer un utilisateur
    function supprimerUtilisateur(noUtilisateur) {
        if (confirm("Voulez-vous vraiment supprimer cet utilisateur ?")) {
            window.location.href = "utilisateur/deleteUser/" + noUtilisateur;
        }
    }
</script>