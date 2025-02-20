<?php
require_once("vues/annonces.vue.php");
?>
<script>
    // Variables globales
    let base = '<?php echo empty(BASE_PATH) ? '/' : '/' . BASE_PATH; ?>';
    const GLOBALS_PARAM_ID = '<?php echo $GLOBALS["paramId"] ?? ""; ?>';
    const GLOBALS_USER_ID = '<?php echo NO_UTILISATEUR ?? ""; ?>';

    // Fonction principale de filtrage
    function appliquerFiltres(page = 1) {
        const formData = new FormData();
        const filtres = {
            'recherche': document.getElementById('recherche')?.value || '',
            'categorie': document.getElementById('categorieFiltre')?.value || '',
            'dateDebut': document.getElementById('dateDebut')?.value || '',
            'dateFin': document.getElementById('dateFin')?.value || '',
            'tri': document.getElementById('tri')?.value || '',
            'id_user': GLOBALS_PARAM_ID == GLOBALS_USER_ID ? GLOBALS_USER_ID : ''
        };

        Object.entries(filtres).forEach(([key, value]) => {
            formData.append(key, value);
        });
        formData.append('page', page);

        afficherLoader();

        fetch(`${base}/data_liste_annonces/p-${page}`, {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                return response.json();
            })
            .then(handleResponse)
            .catch(handleError);
    }

    function afficherLoader() {
        document.getElementById('listeAnnonces').innerHTML = `
        <div class="text-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
        </div>`;
    }

    function handleResponse(data) {
        console.log("requête SQL :   " + data.requete);
        if (!data?.success) {
            throw new Error(data?.message || 'Erreur lors de la récupération des annonces');
        }
        afficherAnnonces(data.data);
        mettreAJourPagination(data.pages, data.page);
    }

    function handleError(error) {
        console.error('Erreur:', error);
        document.getElementById('listeAnnonces').innerHTML = `
        <div class="alert alert-danger" role="alert">
            ${error.message}
        </div>`;
    }

    function afficherAnnonces(annonces) {
        const container = document.getElementById('listeAnnonces');

        if (!Array.isArray(annonces) || annonces.length === 0) {
            container.innerHTML = `
            <div class="alert alert-info" role="alert">
                Aucune annonce trouvée
            </div>`;
            return;
        }

        // Générer le HTML pour toutes les annonces
        container.innerHTML = annonces
            .map(annonce => genererCarteAnnonce(annonce))
            .join('');
    }


    function genererCarteAnnonce(annonce) {
        const date = new Date(annonce.Parution);
        const dateFormatee = formatDate(date);


        const donnesPeronnelles = JSON.stringify(annonce.autresInfos).toLowerCase().includes('hidePrenom') ? '' : `                    
                                        <p class="card-text">
                        <small class="text-muted"><strong>Publié par l'auteur:</strong> ${annonce.PrenomAuteur}</small>
                    </p>
                    `;
                    const courrielInfo = JSON.stringify(annonce.autresInfos).toLowerCase().includes('hideCourriel') ? '' : `                    
                                        <p class="card-text">
                        <small class="text-muted"><strong>Courriel de l'auteur:</strong> ${annonce.CourrielAuteur}</small>
                    </p>
                    `;
                    const telephoneInfo = JSON.stringify(annonce.autresInfos).toLowerCase().includes('hidePhone') ? '' : `                    
                                        <p class="card-text">
                        <small class="text-muted"><strong>Téléphone de l'auteur:</strong> ${annonce.telephoneAuteur}</small>
                    </p>
                    `;
                    


        return `
        <div class="col">
            <div class="card h-100">
                <img class="card-img-top" 
                     style="height: 200px; object-fit: cover;" 
                     src="${securiserURL(annonce.Photo)}" 
                     alt="${htmlspecialchars(annonce.DescriptionA)}">
                
                <div class="card-body">
                    <p class="card-text"><strong>Id :</strong> ${annonce.NoAnnonce}</p>
                    <h5 class="card-title">${htmlspecialchars(annonce.DescriptionA)}</h5>
                    <p class="card-text">${htmlspecialchars(annonce.Description).substring(0, 100)}...</p>
                    <p class="card-text"><strong>Prix:</strong> ${annonce.Prix} $ CA</p>
                    <p class="card-text">
                        <small class="text-muted"><strong>Publié le</strong> ${formatDateLitterale(dateFormatee)}</small>
                    </p>
                    <p class="card-text">
                        <small class="text-muted"><strong>Catégorie:</strong> ${annonce.NomCategories}</small>
                    </p>
                                        <p class="card-text">
                        <small class="text-muted"><strong>Etat :</strong> ${strEtat(annonce.Etat)}</small>
                    </p>
                    ${donnesPeronnelles}
                 
                </div>
                
                <div class="card-footer">
                    <div class="d-grid gap-2">
                        <a href="${lien('annonce/details/' + annonce.NoAnnonce)}" 
                           class="btn btn-outline-primary">Détails</a>
                        ${genererBoutonsAction(annonce)}
                    </div>
                </div>
            </div>
        </div>`;
    }

    function genererBoutonsAction(annonce) {
        if (annonce.NoUtilisateur != GLOBALS_PARAM_ID) return '';

        return `
        <a href="${lien('annonce/modifier-annonce/' + annonce.NoAnnonce)}" 
           class="btn btn-outline-warning mt-2">Modifier</a>
        <button onclick="confirmerSuppression(${annonce.NoAnnonce})" 
                class="btn btn-outline-danger mt-2">Supprimer</button>`;
    }

    function confirmerSuppression(id) {
        if (confirm('Voulez-vous vraiment supprimer cette annonce ?')) {
            window.location.href = lien('annonce/supprimer-annonce/' + id);
        }
    }

    // Fonctions utilitaires
    function securiserURL(url) {
        if (!url) return '';
        return url.includes('http') ? url : lien(url);
    }

    function formatDate(date) {
        return {
            jour: date.getDate().toString().padStart(2, '0'),
            mois: (date.getMonth() + 1).toString().padStart(2, '0'),
            annee: date.getFullYear(),
            heure: date.getHours().toString().padStart(2, '0'),
            minutes: date.getMinutes().toString().padStart(2, '0')
        };
    }

    function formatDateLitterale({
        jour,
        mois,
        annee,
        heure,
        minutes
    }) {
        const jourSemaine = new Date(`${annee}-${mois}-${jour}`).getDay();
        return `${jourSemaineEnLitteral(jourSemaine)} ${jour} ${moisEnLitteral(mois)} ${annee} à ${heure}h${minutes}`;
    }

    //etat de l'annonce

    function strEtat($etat) {
        switch ($etat) {
            case '1':
                return "Actif"; // Statut lors de l'enregistrement
            case '2':
                return "Inactif"; // Statut après confirmation
            case '3':
                return "Retiré"; // Rôle administrateur
            default:
                return "Etat inconnu"; // Cas par défaut si le statut n'est pas reconnu
        }
    }

    function htmlspecialchars(str) {
        if (!str) return '';
        return str
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }
    // ...existing code...

    function mettreAJourPagination(totalPages, pageActuelle) {
        const pagination = document.querySelector('.pagination');
        if (!pagination || totalPages <= 1) return;

        let html = '';

        // Bouton précédent
        html += `
        <li class="page-item ${pageActuelle <= 1 ? 'disabled' : ''}">
            <a class="page-link" href="javascript:void(0)" 
               onclick="appliquerFiltres(${pageActuelle - 1})" 
               ${pageActuelle <= 1 ? 'disabled' : ''}>
                &laquo; Précédent
            </a>
        </li>`;

        // Pages numérotées
        for (let i = 1; i <= totalPages; i++) {
            if (
                i === 1 || // Première page
                i === totalPages || // Dernière page
                (i >= pageActuelle - 2 && i <= pageActuelle + 2) // Pages autour de la page actuelle
            ) {
                html += `
                <li class="page-item ${i === pageActuelle ? 'active' : ''}">
                    <a class="page-link" href="javascript:void(0)" 
                       onclick="appliquerFiltres(${i})">
                        ${i}
                    </a>
                </li>`;
            } else if (
                i === pageActuelle - 3 ||
                i === pageActuelle + 3
            ) {
                html += `
                <li class="page-item disabled">
                    <span class="page-link">...</span>
                </li>`;
            }
        }

        // Bouton suivant
        html += `
        <li class="page-item ${pageActuelle >= totalPages ? 'disabled' : ''}">
            <a class="page-link" href="javascript:void(0)" 
               onclick="appliquerFiltres(${pageActuelle + 1})"
               ${pageActuelle >= totalPages ? 'disabled' : ''}>
                Suivant &raquo;
            </a>
        </li>`;

        pagination.innerHTML = html;
    }


    //retourner le mois en litteral
    function moisEnLitteral(mois) {
        const moisLitteraux = [
            'janvier', 'février', 'mars', 'avril', 'mai', 'juin',
            'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'
        ];
        return moisLitteraux[parseInt(mois) - 1];
    }

    //retourner le jour de la semaine en litteral
    function jourSemaineEnLitteral(jour) {
        const joursSemaine = [
            'Dimanche', 'Lundi', 'Mardi', 'Mercredi',
            'Jeudi', 'Vendredi', 'Samedi'
        ];
        return joursSemaine[jour];
    }

    //gestion du lien absolu
    function lien(path) {
        return base + "/" + path;
    }

    //Reinitialiser le formulaire et la liste des annonces
    function reinitialiserFiltres() {
        // Réinitialiser les champs de recherche
        document.getElementById('recherche').value = '';
        document.getElementById('categorieFiltre').value = '';
        document.getElementById('dateDebut').value = '';
        document.getElementById('dateFin').value = '';
        document.getElementById('tri').value = 'date_asc';

        // Réinitialiser les styles si nécessaire
        document.querySelectorAll('#filterForm select, #filterForm input').forEach(element => {
            element.classList.remove('is-valid', 'is-invalid');
        });

        // Recharger les annonces
        appliquerFiltres(1);
    }


    // Ajouter la fonction debounce pour attendre un temps de frappe avant de lancer la recherche
    function debounce(callback, delai) {
        let timer;
        return function() {
            clearTimeout(timer);
            timer = setTimeout(() => {
                callback.apply(this, arguments);
            }, delai);
        };
    }

    // Initialisation
    document.addEventListener('DOMContentLoaded', () => {
        appliquerFiltres();

        // Écouteur pour la recherche en temps réel avec debounce
        const champRecherche = document.getElementById('recherche');
        if (champRecherche) {
            champRecherche.addEventListener('input', debounce(() => {
                appliquerFiltres(1);
            }, 300)); // Délai de 300ms
        }

        // Écouteurs d'événements pour les filtres
        document.querySelectorAll('#categorieFiltre, #dateDebut, #dateFin, #tri')
            .forEach(element => {
                element.addEventListener('change', () => appliquerFiltres(1));
            });

        // Reinitialiser le formulaire
        document.getElementById('filterForm').addEventListener('reset', (e) => {
            e.preventDefault(); // Empêcher le reset natif
            reinitialiserFiltres();
        });
    });
</script>