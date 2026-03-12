<?php

/** 
 * Classe MonitorArticlesManager pour enregistrer les visiteurs.
 */
class MonitorArticlesManager extends AbstractEntityManager 
{
    /**
     * Récupère toutes les données nécessaires pour le suivi des articles : titre, date de publication, nombre de visites et nombre de commentaires.
     * @param string $sortParam : le critère de tri (ex: "title_asc", "nbvisit_desc", etc.)
     * @return array : un tableau d'objets ArticlesReport.
     */
    public function getArticlesReport(string $sortParam ) : array
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

        // On construit la requête SQL pour récupérer les données des articles avec le nombre de visites et de commentaires, triées selon les critères spécifiés.
        $sql = "SELECT a.id as id_article, a.title, a.date_creation as date_publication, 
                (SELECT COUNT(*) FROM visitor v WHERE v.id_article = a.id) AS visit_count,
                (SELECT COUNT(*) FROM comment c WHERE c.id_article = a.id) AS comment_count
                FROM article a
                ORDER BY $orderByField $orderByDirection";

        $result = $this->db->query($sql);
        $articlesReport = [];

        while ($articleReport = $result->fetch()) {
            $articlesReport[] = new ArticleReport($articleReport);
        }
        return $articlesReport;
    }
}