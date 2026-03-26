<?php

/**
 * Classe ArticleVisitsManager pour gérer les statistiques de visites des articles.
 */

class ArticleVisitsManager extends AbstractEntityManager
{
    /**
     * Enregistre un nouveau visiteur.
     * @param objet $visits : un objet ArticleVisits contenant les données de la visite.
     * @return bool
     */
    public function trackVisit(ArticleVisits $visits): bool
    {

        // Si le nombre de visites est égal à 0, cela signifie que l'article n'avait pas encore de statistiques, donc on insère une nouvelle ligne
        if ($visits->getNbVisits() === 0) {

            // Préparation de la requête d'insertion
            $insertSql = "INSERT INTO article_visits (id_article, nb_visits, last_visit_at) 
                        VALUES (:id_article, 1, NOW())";

            // Exécution de la requête d'insertion
            $resultInsert = $this->db->query($insertSql, ['id_article' => $visits->getIdArticle()]);

            return ($resultInsert->rowCount() > 0);

        }

        // Préparation de la requête de mise à jour pour incrémenter le nombre de visites et mettre à jour la date de la dernière visite
        $sqlUpdate = "UPDATE article_visits SET nb_visits = :nb_visits + 1, last_visit_at = NOW() 
                WHERE id_article = :id_article";

        // Exécution de la requête de mise à jour
        $resultUpdate = $this->db->query($sqlUpdate, [
            'id_article' => $visits->getIdArticle(),
            'nb_visits' => $visits->getNbVisits()
        ]);

        return ($resultUpdate->rowCount() > 0);

    }

    /**
     * Récupère les statistiques de visites d'un article par son id.
     * @param int $id
     * @return ArticleVisits|null
     */
    public function getVisitById(int $id): ?ArticleVisits
    {
        $sql = "SELECT * FROM article_visits WHERE id_article = :id";
        $result = $this->db->query($sql, ['id' => $id]);
        $visit = $result->fetch();
        if ($visit) {
            return new ArticleVisits($visit);
        }
        return null;
    }

}
