<?php
/**
 * Affichage de la partie admin : liste des articles avec un bouton "modifier" pour chacun.
 * Et un formulaire pour ajouter un article.
 */
?>

<h2>Statistiques des articles</h2>

<a class="submit" href="index.php?action=admin">Retour</a>

<div class="adminArticleMonitoring">
    <div class="articleMonitoringLine">
        <div class="articleMonitoringCell">Titre de l'article</div>
        <div class="articleMonitoringCell">Vues</div>
        <div class="articleMonitoringCell">Commentaires</div>
        <div class="articleMonitoringCell">Date de création</div>
    </div>
    <?php foreach ($articles as $article) { ?>
        <div class="articleMonitoringLine">
            <div class="articleMonitoringCell"><?= $article->getTitle() ?></div>
            <div class="articleMonitoringCell"><?= $article->getNbrOfView() ?></div>
            <div class="articleMonitoringCell"><?= $article->getNbrOfComments() ?></div>
            <div class="articleMonitoringCell"><?= $article->getDateCreation()->format('Y-m-d') ?></div>
        </div>
    <?php } ?>
</div>

