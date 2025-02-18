<?php
// Gestion utilisateur qui ne sont pas administrateur


?>
<nav class="admin-menu">
    <ul class="admin-menu__list">
        <li class="admin-menu__item">
            <a href="<?= lien('annonce/liste_annonces') ?>" class="admin-menu__link">
                <i class="fas fa-bullhorn"></i> Afficher les annonces
            </a>
        </li>
        <li class="admin-menu__item">
            <a href="<?= lien('annonce/liste_annonces/' . NO_UTILISATEUR) ?>" class="admin-menu__link">
                <i class="fas fa-cog"></i> Gérer mes annonces
            </a>
        </li>

        <li class="admin-menu__item">
            <a href="<?= lien('utilisateur/mon-profil') ?>" class="admin-menu__link">
                <i class="fas fa-user-edit"></i> Modifier mon profil
            </a>
        </li>
    </ul>
</nav>

<style>
.admin-menu {
    background: #fff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    padding: 1rem;
}

.admin-menu__list {
    display: flex;
    list-style: none;
    margin: 0;
    padding: 0;
    gap: 2rem;
    justify-content: center;
}

.admin-menu__link {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    text-decoration: none;
    color: #333;
    border-radius: 4px;
    transition: background-color 0.2s;
}

.admin-menu__link:hover {
    background-color: #f0f0f0;
    color: #000;
}

.admin-menu__link i {
    font-size: 1.2rem;
}
</style>

