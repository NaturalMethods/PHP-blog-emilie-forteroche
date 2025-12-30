<?php

/**
 * Classe qui gère les articles.
 */
class ArticleManager extends AbstractEntityManager
{
    /**
     * Récupère tous les articles.
     * @return array : un tableau d'objets Article.
     */
    public function getAllArticles(): array
    {
        $sql = "SELECT * FROM article";
        $result = $this->db->query($sql);
        $articles = [];

        while ($article = $result->fetch()) {
            $articles[] = new Article($article);
        }
        return $articles;
    }

    public function getArticlesSortedBy(array $articles, string $action): mixed
    {

        switch (true) {
            case preg_match('/^title(asc|desc)$/i', $action, $matches):
                return $this->sortAlphabeticallyBy($articles, 'getTitle', $matches[1]);

            case preg_match('/^view(asc|desc)$/i', $action, $matches):
                return $this->sortNumericallyBy($articles, 'getNbrOfView', $matches[1]);

            case preg_match('/^com(asc|desc)$/i', $action, $matches):
                return $this->sortNumericallyBy($articles, 'getNbrOfComments', $matches[1]);

            case preg_match('/^date(asc|desc)$/i', $action, $matches):
                return $this->sortNumericallyBy($articles, 'getDateCreation', $matches[1]);

            default:
                return $articles;
        }
    }

    public function sortAlphabeticallyBy(array $articles, string $getter, $order): array
    {
        usort($articles, function ($a, $b) use ($getter, $order) {
            if (!strcasecmp($order, 'Desc'))
                return strcasecmp($b->$getter(), $a->$getter());
            else
                return strcasecmp($a->$getter(), $b->$getter());
        });
        return $articles;
    }

    public function sortNumericallyBy(array $articles, string $getter, $order): array
    {
        usort($articles, function ($a, $b) use ($getter, $order) {
            if (!strcasecmp($order, 'Desc'))
                return $b->$getter() <=> $a->$getter();
            else
                return $a->$getter() <=> $b->$getter();
        });
        return $articles;
    }

    /**
     * Récupère un article par son id.
     * @param int $id : l'id de l'article.
     * @return Article|null : un objet Article ou null si l'article n'existe pas.
     */
    public function getArticleById(int $id): ?Article
    {
        $sql = "SELECT * FROM article WHERE id = :id";
        $result = $this->db->query($sql, ['id' => $id]);
        $article = $result->fetch();
        if ($article) {
            return new Article($article);
        }
        return null;
    }

    /**
     * Ajoute ou modifie un article.
     * On sait si l'article est un nouvel article car son id sera -1.
     * @param Article $article : l'article à ajouter ou modifier.
     * @return void
     */
    public function addOrUpdateArticle(Article $article): void
    {
        if ($article->getId() == -1) {
            $this->addArticle($article);
        } else {
            $this->updateArticle($article);
        }
    }

    /**
     * Ajoute un article.
     * @param Article $article : l'article à ajouter.
     * @return void
     */
    public function addArticle(Article $article): void
    {
        $sql = "INSERT INTO article (id_user, title, content, date_creation) VALUES (:id_user, :title, :content, NOW())";
        $this->db->query($sql, [
            'id_user' => $article->getIdUser(),
            'title' => $article->getTitle(),
            'content' => $article->getContent()
        ]);
    }

    /**
     * Modifie un article.
     * @param Article $article : l'article à modifier.
     * @return void
     */
    public function updateArticle(Article $article): void
    {
        $sql = "UPDATE article SET title = :title, content = :content, date_update = NOW(), nbr_of_view = :nbrOfView WHERE id = :id";
        $this->db->query($sql, [
            'title' => $article->getTitle(),
            'content' => $article->getContent(),
            'nbrOfView' => $article->getNbrOfView(),
            'id' => $article->getId()
        ]);
    }

    /**
     * Supprime un article.
     * @param int $id : l'id de l'article à supprimer.
     * @return void
     */
    public function deleteArticle(int $id): void
    {
        $sql = "DELETE FROM article WHERE id = :id";
        $this->db->query($sql, ['id' => $id]);
    }

    public function setAllArticlesNbrOfComments(array $articles, array $nbrComments): void
    {
        foreach ($articles as $article) {
            foreach ($nbrComments as $nbrComment) {
                if ($article->getId() == $nbrComment['id']) {
                    $article->setNbrOfComments($nbrComment['nb_comments']);
                }
            }
        }
    }

}