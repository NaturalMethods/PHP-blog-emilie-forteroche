<?php
/**
 * Affichage de la partie monitoring : liste des articles avec des boutons en en-tête pour trier par colonne.
 */
?>

<h2>Statistiques des articles</h2>

<a class="submit" href="index.php?action=admin">Retour</a>

<h4>Cliquez sur une entête de colonne pour la trier</h4>

<div class="adminArticleMonitoring">
    <div class="articleMonitoringLine">
        <a class="articleMonitoringCell articleTitleHeader" href="<?= $headerButtons['title']['link']; ?>" >Titre de l'article <h6><?= $headerButtons['title']['text']; ?></h6></a>
        <a class="articleMonitoringCell articleTitleHeader" href="<?= $headerButtons['views']['link']; ?>" >Vues <h6><?= $headerButtons['views']['text']; ?></h6></a>
        <a class="articleMonitoringCell articleTitleHeader" href="<?= $headerButtons['comments']['link']; ?>" >Commentaires <h6><?= $headerButtons['comments']['text']; ?></h6></a>
        <a class="articleMonitoringCell articleTitleHeader" href="<?= $headerButtons['date']['link']; ?>" >Date de création <h6><?= $headerButtons['date']['text']; ?></h6></a>
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

