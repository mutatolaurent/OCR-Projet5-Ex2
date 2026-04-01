<?php

/**
 * Classe MonitorArticleVisitsManager pour enregistrer les visiteurs.
 */
class MonitorArticleVisitsManager extends AbstractEntityManager
{
    /**
     * Récupère toutes les données nécessaires pour le suivi des articles :
     * titre, date de publication, date de dernière visite, nombre de visites et nombre de commentaires.
     * @param string $sortField : le champ de tri (ex: "title", "visitCount", etc.)
     * @param string $sortOrderBy : l'ordre de tri (ex: "asc", "desc")
     * @return array : un tableau d'objets ArticleVisitsReport.
     */
    public function getArticlesReport(string $sortField, string $sortOrderBy): array
    {

        // Définition des champs autorisés pour le tri et des directions de tri autorisées.
        $allowedFields = ['title', 'datePublication', 'visitCount', 'dateLastVisit', 'commentCount'];
        $allowedSortKeys = ['asc','desc'];

        // On vérifie que les critères de tri sont valides, sinon on utilise des valeurs par défaut.
        if (!in_array($sortField, $allowedFields) || !in_array($sortOrderBy, $allowedSortKeys)) {
            $orderByField = 'visitCount'; // Valeur par défaut
            $orderByDirection = 'desc'; // Valeur par défaut
        } else {
            $orderByField = $sortField;
            $orderByDirection = $sortOrderBy;
        }

        // On construit la requête SQL pour récupérer les données des articles
        // avec le nombre de visites et de commentaires.
        // Pour être conforme aux exigences de la mission, on récupère tous les résultats sans tri dans la requête SQL.
        // Le tri sera effectué en PHP après la récupération des données.
        $sql = "SELECT a.id as id_article, a.title, a.date_creation as date_publication, COALESCE(b.nb_visits, 0) as visit_count, b.last_visit_at as date_last_visit,
                (SELECT COUNT(*) FROM comment c WHERE c.id_article = a.id) AS comment_count
                FROM article a
                LEFT JOIN article_visits b ON a.id = b.id_article";

        // Exécution de la requête SQL et récupération des résultats.
        $result = $this->db->query($sql);
        $articleVisitsReport = [];

        // On parcourt les résultats et on crée des objets ArticleVisitsReport pour chaque article.
        while ($articleReport = $result->fetch()) {
            $articleVisitsReport[] = new ArticleVisitsReport($articleReport);
        }

        // Tri des résultats en PHP selon les critères de tri spécifiés.
        usort($articleVisitsReport, function ($a, $b) use ($orderByField, $orderByDirection) {

            // Convertit "visit_count" → "getVisitCount"
            $getter = 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $orderByField)));

            // Vérifie que les méthodes getter existent pour les deux objets, sinon on considère qu'ils sont égaux pour le tri.
            if (!method_exists($a, $getter) || !method_exists($b, $getter)) {
                return 0;
            }

            // Récupère les valeurs à comparer en utilisant les getters.
            $valueA = $a->$getter();
            $valueB = $b->$getter();

            // Si les valeurs sont des objets DateTime, on les convertit en timestamps pour la comparaison.
            if ($valueA instanceof DateTime && $valueB instanceof DateTime) {
                $valueA = $valueA->getTimestamp();
                $valueB = $valueB->getTimestamp();
            }

            // Comparaison des valeurs pour le tri.

            // Si les valeurs sont égales, on retourne 0 pour indiquer - ne change pas l’ordre relatif de ces deux éléments.
            if ($valueA == $valueB) {
                return 0;
            }

            // Si $valueA est inférieur à $valueB, on retourne -1 pour indiquer que $a doit être placé avant $b.
            $result = ($valueA < $valueB) ? -1 : 1;

            // Si la direction de tri est DESC, on inverse le résultat pour placer $a après $b.
            return ($orderByDirection === 'desc') ? -$result : $result;
        });

        return $articleVisitsReport;
    }
}
