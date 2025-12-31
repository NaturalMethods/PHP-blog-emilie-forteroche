<?php
/**
 * Template du formulaire d'update/creation d'un article.
 */
?>

<form action="index.php" method="post" class="foldedCorner">
    <h2><?= $article->getId() == -1 ? "Création d'un article" : "Modification de l'article " ?></h2>
    <div class="formGrid">
        <label for="title">Titre</label>
        <input type="text" name="title" id="title" value="<?= $article->getTitle() ?>" required>
        <label for="content">Contenu</label>
        <textarea name="content" id="content" cols="30" rows="10" required><?= $article->getContent() ?></textarea>
        <input type="hidden" name="action" value="updateArticle">
        <input type="hidden" name="id" value="<?= $article->getId() ?>">
        <button class="submit"><?= $article->getId() == -1 ? "Ajouter" : "Modifier" ?></button>
    </div>
</form>

    <div class="comments">
        <h2 class="commentsTitle">Commentaires</h2>
        <?php
        if (empty($comments)) {
            echo '<p class="info">Aucun commentaire pour cet article.</p>';
        } else {
            echo '<ul>';
            foreach ($comments as $comment) {
                ?><form action="index.php?action=deleteComment&id=<?= $article->getId() ?>&comid=<?= $comment->getId()?>" method="post" class="foldedCorner"><?php
                echo '<li>';
                echo '  <div class="smiley">☻</div>';
                echo '  <div class="detailComment">';
                echo '      <h3 class="info">Le ' . Utils::convertDateToFrenchFormat($comment->getDateCreation()) . ", " . Utils::format($comment->getPseudo()) . ' a écrit :</h3>';
                echo '      <p class="content">' . Utils::format($comment->getContent()) . '</p>';
                echo '  </div>';
                echo '</li>';
                ?>
                <button class="submit">Supprimer</button><?php
                ?></form><?php
            }
            echo '</ul>';

        }
        ?>


<script>


</script>