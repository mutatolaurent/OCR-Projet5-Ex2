<?php

class ArticleController
{
    /**
     * Affiche la page d'accueil.
     * @return void
     */
    public function showHome(): void
    {
        $articleManager = new ArticleManager();
        $articles = $articleManager->getAllArticles();

        $view = new View("Accueil");
        $view->render("home", ['articles' => $articles]);
    }

    /**
     * Affiche le détail d'un article.
     * @return void
     */
    public function showArticle(): void
    {
        // Récupération de l'id de l'article demandé.
        $id = Utils::request("id", -1);

        $articleManager = new ArticleManager();
        $article = $articleManager->getArticleById($id);

        if (!$article) {
            throw new Exception("L'article demandé n'existe pas.");
        }

        // Récupération des commentaires de l'article.
        $commentManager = new CommentManager();
        $comments = $commentManager->getAllCommentsByArticleId($id);

        // On utilise une variable de session spécifique pour chaque article afin de gérer le TTL de la visite de l'article.
        // Initialisation à une date passée pour permettre la première visite de l'article
        $pageId = 'article_' . $id;
        if (!isset($_SESSION[$pageId])) {
            $_SESSION[$pageId] = time() - 1;
        }

        // Si l'utilisateur n'est pas connecté (non administrateur), et que la page n'a pas déjà été visitée récemment,
        // on enregistre la visite de l'article.
        if (!isset($_SESSION['user']) && $_SESSION[$pageId] < time()) {

            // Récupération des statistiques de visites de l'article.
            $articleVisitsManager = new ArticleVisitsManager();
            $articleVisits = $articleVisitsManager->getVisitByArticleId($id);

            // Si l'article n'avait pas encore de statistiques, on en crée une nouvelle.
            if (!$articleVisits) {
                $articleVisits = new ArticleVisits([
                    'idArticle' => $id,
                    'nbVisits' => 0
                ]);
            }

            // On enregistre la visite de l'article.
            $articleVisitsManager->trackVisit($articleVisits);

            // On met à jour la variable de session pour indiquer que la page a été visitée, avec un TTL de TTL_VISIT secondes.
            // Ce qui signifie qu'aucune ne sera comptabilisée pour cette page pour ce visiteur tant que TTL_VISIT secondes ne seront pas écoulées.
            $_SESSION[$pageId] = time() + TTL_VISIT;

        }

        // Affichage de la vue de détail de l'article.
        $view = new View($article->getTitle());
        $view->render("detailArticle", ['article' => $article, 'comments' => $comments]);
    }

    /**
     * Affiche le formulaire d'ajout d'un article.
     * @return void
     */
    public function addArticle(): void
    {
        $view = new View("Ajouter un article");
        $view->render("addArticle");
    }

    /**
     * Affiche la page "à propos".
     * @return void
     */
    public function showApropos()
    {
        $view = new View("A propos");
        $view->render("apropos");
    }
}
