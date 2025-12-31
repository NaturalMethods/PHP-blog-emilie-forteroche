<?php
/**
 * Contrôleur de la partie admin.
 */

class AdminController
{

    /**
     * Affiche la page d'administration.
     * @return void
     */
    public function showAdmin(): void
    {
        // On vérifie que l'utilisateur est connecté.
        $this->checkIfUserIsConnected();

        // On récupère les articles.
        $articleManager = new ArticleManager();
        $articles = $articleManager->getAllArticles();

        // On affiche la page d'administration.
        $view = new View("Administration");
        $view->render("admin", [
            'articles' => $articles
        ]);
    }

    public function showMonitoring(): void
    {
        $this->checkIfUserIsConnected();

        //TODO SANITIZE sort htmlspecialchars();
        $action = Utils::request('sort', '');

        $action = htmlspecialchars($action);

        $headerButtonsData = [
            'title'=> [ 'text'=>'','state'=>'titleDesc','link'=>'index.php?action=monitoring&sort=titleDesc'],
            'views'=> [ 'text'=>'','state'=>'viewDesc','link'=>'index.php?action=monitoring&sort=viewDesc'],
            'comments' => [ 'text'=>'','state'=>'comDesc','link'=>'index.php?action=monitoring&sort=comDesc'],
            'date' => [ 'text'=>'','state'=>'dateDesc','link'=>'index.php?action=monitoring&sort=dateDesc']
        ];

        foreach ($headerButtonsData as &$button) {
            if(substr($button['state'], 0,-4) == substr($action, 0, -4)) {

                $button['link'] = 'index.php?action=monitoring&sort='.substr($action, 0, -4).'Asc';
                $button['text'] ='Tri descendant';

            }else if (substr($button['state'], 0,-4) == substr($action, 0, -3)) {

                $button['link'] = 'index.php?action=monitoring&sort=' . substr($action, 0, -3) . 'Desc';
                $button['text'] ='Tri ascendant';

            }
        }

        $articleManager = new ArticleManager();

        $articles = $articleManager->getAllArticles();

        $commentManager = new CommentManager();
        $nbrComments = $commentManager->getCommentsCountForEachArticles();

        $articleManager->setAllArticlesNbrOfComments($articles, $nbrComments);

        if ($action != '' && $action != null) {
            $articles = $articleManager->getArticlesSortedBy($articles, $action);
        }

        // On affiche la page de monitoring.
        $view = new View("Monitoring");
        $view->render("monitoring", [
            'articles' => $articles,
            'headerButtons' => $headerButtonsData
        ]);
    }

    /**
     * Vérifie que l'utilisateur est connecté.
     * @return void
     */
    private function checkIfUserIsConnected(): void
    {
        // On vérifie que l'utilisateur est connecté.
        if (!isset($_SESSION['user'])) {
            Utils::redirect("connectionForm");
        }
    }

    /**
     * Affichage du formulaire de connexion.
     * @return void
     */
    public function displayConnectionForm(): void
    {
        $view = new View("Connexion");
        $view->render("connectionForm");
    }

    /**
     * Connexion de l'utilisateur.
     * @return void
     */
    public function connectUser(): void
    {
        // On récupère les données du formulaire.
        $login = Utils::request("login");
        $password = Utils::request("password");

        // On vérifie que les données sont valides.
        if (empty($login) || empty($password)) {
            throw new Exception("Tous les champs sont obligatoires. 1");
        }

        // On vérifie que l'utilisateur existe.
        $userManager = new UserManager();
        $user = $userManager->getUserByLogin($login);
        if (!$user) {
            throw new Exception("L'utilisateur demandé n'existe pas.");
        }

        // On vérifie que le mot de passe est correct.
        if (!password_verify($password, $user->getPassword())) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            throw new Exception("Le mot de passe est incorrect : $hash");
        }

        // On connecte l'utilisateur.
        $_SESSION['user'] = $user;
        $_SESSION['idUser'] = $user->getId();

        // On redirige vers la page d'administration.
        Utils::redirect("admin");
    }

    /**
     * Déconnexion de l'utilisateur.
     * @return void
     */
    public function disconnectUser(): void
    {
        // On déconnecte l'utilisateur.
        unset($_SESSION['user']);

        // On redirige vers la page d'accueil.
        Utils::redirect("home");
    }

    /**
     * Affichage du formulaire d'ajout d'un article.
     * @return void
     */
    public function showUpdateArticleForm(): void
    {
        $this->checkIfUserIsConnected();

        // On récupère l'id de l'article s'il existe.
        $id = Utils::request("id", -1);

        // On récupère l'article associé.
        $articleManager = new ArticleManager();
        $article = $articleManager->getArticleById($id);

        // Si l'article n'existe pas, on en crée un vide. 
        if (!$article) {
            $article = new Article();
        }

        $commentManager = new CommentManager();
        $comments = $commentManager->getAllCommentsByArticleId($article->getId());

        // On affiche la page de modification de l'article.
        $view = new View("Edition d'un article");
        $view->render("updateArticleForm", [
            'article' => $article,
            'comments' => $comments
        ]);
    }

    /**
     * Ajout et modification d'un article.
     * On sait si un article est ajouté car l'id vaut -1.
     * @return void
     */
    public function updateArticle(): void
    {
        $this->checkIfUserIsConnected();

        // On récupère les données du formulaire.
        $id = Utils::request("id", -1);
        $title = Utils::request("title");
        $content = Utils::request("content");

        // On vérifie que les données sont valides.
        if (empty($title) || empty($content)) {
            throw new Exception("Tous les champs sont obligatoires. 2");
        }

        // On crée l'objet Article.
        $article = new Article([
            'id' => $id, // Si l'id vaut -1, l'article sera ajouté. Sinon, il sera modifié.
            'title' => $title,
            'content' => $content,
            'id_user' => $_SESSION['idUser']
        ]);

        // On ajoute l'article.
        $articleManager = new ArticleManager();
        $articleManager->addOrUpdateArticle($article);

        // On redirige vers la page d'administration.
        Utils::redirect("admin");
    }


    /**
     * Suppression d'un article.
     * @return void
     */
    public function deleteArticle(): void
    {
        $this->checkIfUserIsConnected();

        $id = Utils::request("id", -1);

        // On supprime l'article.
        $articleManager = new ArticleManager();
        $articleManager->deleteArticle($id);

        // On redirige vers la page d'administration.
        Utils::redirect("admin");
    }

    public function deleteComment(): void{

        $this->checkIfUserIsConnected();

        $id = Utils::request("id", -1);
        $comid = Utils::request("comid", -1);

        // On supprime l'article.
        $articleManager = new ArticleManager();

        if($comid >= 0) {
            $commentManager = new CommentManager();
            $comment = $commentManager->getCommentById($comid);
            $commentManager->deleteComment($comment);
        }

        // On redirige vers la page d'administration.
        Utils::redirect("showUpdateArticleForm", ["id" => $id]);

    }
}