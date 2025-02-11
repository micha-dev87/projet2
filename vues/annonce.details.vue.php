<?php

    // vues/annonce.details.vue.php
   global  $annonce;

   ?>
<h2><?= htmlspecialchars($annonce->DescriptionA) ?></h2>
<img src="<?= htmlspecialchars($annonce->Photo) ?>" alt="<?= htmlspecialchars($annonce->DescriptionA) ?>"
     class="img-fluid mb-3">
<p><strong>Description :</strong> <?= htmlspecialchars($annonce->Description) ?></p>
<p><strong>Prix :</strong> <?= htmlspecialchars($annonce->Prix) ?> $ CA</p>
<p><strong>État :</strong> <?= htmlspecialchars($annonce->Etat) ?></p>
<p><strong>Date de publication :</strong> <?= htmlspecialchars($annonce->Parution) ?></p>
<p><strong>Catégorie :</strong> <?= htmlspecialchars($annonce->Categorie) ?></p>
<a href="<?= lien("dashboard"); ?>" class="btn btn-outline-primary">Retour</a>

