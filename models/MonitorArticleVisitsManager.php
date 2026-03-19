<?php

/**
 * Classe MonitorArticleVisitsManager pour enregistrer les visiteurs.
 */
class MonitorArticleVisitsManager extends AbstractEntityManager
{
    /**
     * Récupère toutes les données nécessaires pour le suivi des articles :
     * titre, date de publication, date de dernière visite, nombre de visites et nombre de commentaires.
     * @param string $sortParam : le critère de tri (ex: "title_asc", "nbvisit_desc", etc.)
     * @return array : un tableau d'objets ArticleVisitsReport.
     */
    public function getArticlesReport(string $sortParam): array
    {
        // On définit les critères de tri autorisés et leurs correspondances avec les champs de la base de données.
        $allowedSort = [
        "title_asc"        => ["field" => "title",             "direction" => "ASC"],
        "title_desc"       => ["field" => "title",             "direction" => "DESC"],
        "nbvisit_asc"      => ["field" => "visit_count",       "direction" => "ASC"],
        "nbvisit_desc"     => ["field" => "visit_count",       "direction" => "DESC"],
        "nbcomment_asc"    => ["field" => "comment_count",     "direction" => "ASC"],
        "nbcomment_desc"   => ["field" => "comment_count",     "direction" => "DESC"],
        "datepub_asc"      => ["field" => "date_publication",  "direction" => "ASC"],
        "datepub_desc"     => ["field" => "date_publication",  "direction" => "DESC"],
        "datelastvisit_asc" => ["field" => "date_last_visit",   "direction" => "ASC"],
        "datelastvisit_desc" => ["field" => "date_last_visit",   "direction" => "DESC"],
        ];

        // On vérifie que le critère de tri est valide, sinon on utilise un tri par défaut.
        if ($sortParam && isset($allowedSort[$sortParam])) {
            $orderByField = $allowedSort[$sortParam]['field'];
            $orderByDirection = $allowedSort[$sortParam]['direction'];
        } else {
            // Valeur par défaut si aucun tri ou tri invalide
            $orderByField = $allowedSort['nbvisit_desc']['field'];
            $orderByDirection = $allowedSort['nbvisit_desc']['direction'];
        }

        // On construit la requête SQL pour récupérer les données des articles
        // avec le nombre de visites et de commentaires, triées selon les critères spécifiés.
        $sql = "SELECT a.id as id_article, a.title, a.date_creation as date_publication, COALESCE(b.nb_visits, 0) as visit_count, b.last_visit_at as date_last_visit,
                (SELECT COUNT(*) FROM comment c WHERE c.id_article = a.id) AS comment_count
                FROM article a
                LEFT JOIN article_visits b ON a.id = b.id_article
                ORDER BY $orderByField $orderByDirection";

        $result = $this->db->query($sql);
        $articleVisitsReport = [];

        while ($articleReport = $result->fetch()) {
            $articleVisitsReport[] = new ArticleVisitsReport($articleReport);
        }

        return $articleVisitsReport;
    }
}
