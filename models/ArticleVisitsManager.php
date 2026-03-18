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

        // Préparation de la requête de mise à jour pour incrémenter le nombre de visites et mettre à jour la date de la dernière visite
        $sqlUpdate = "UPDATE article_visits SET nb_visits = nb_visits + 1, last_visit_at = NOW() 
                WHERE id_article = :id_article";

        // Exécution de la requête de mise à jour
        $resultUpdate = $this->db->query($sqlUpdate, [
            'id_article' => $visits->getIdArticle()
        ]);

        // Si une ligne a bien été mise à jour (ce qui signifie que l'article avait déjà des statistiques), on retourne true
        // Par contre, si aucune ligne n'a été mise à jour, cela signifie que l'article
        // n'avait pas encore de statistiques, donc on insère une nouvelle ligne
        if ($resultUpdate->rowCount() > 0) {
            return ($resultUpdate->rowCount() > 0);
        }

        // Préparation de la requête d'insertion
        $insertSql = "INSERT INTO article_visits (id_article, nb_visits, last_visit_at) 
                        VALUES (:id_article, 1, NOW())";

        // Exécution de la requête d'insertion
        $resultInsert = $this->db->query($insertSql, ['id_article' => $visits->getIdArticle()]);

        return ($resultInsert->rowCount() > 0);
    }
}
