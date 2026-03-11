<?php

/** 
 * Classe VisitorManager pour enregistrer les visiteurs.
 */

class VisitorManager extends AbstractEntityManager 
{
    /**
     * Enregistre un nouveau visiteur.
     * @param int $articleId
     * @return bool
     */
    public function trackVisitOld(int $articleId): bool 
    {
        // 1. Récupération des données
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';

        // 2. Préparation de la requête
        $sql = "INSERT INTO visitor (id_article, user_agent) 
                VALUES (:id_article, :user_agent)";
        
        // 3. Exécution de la requête
        $result = $this->db->query($sql, [
            'id_article' => $articleId,
            'user_agent' => $userAgent
        ]);
        return $result->rowCount() > 0;

    }

    /**
     * Enregistre un nouveau visiteur.
     * @param int $articleId
     * @return bool
     */
    public function trackVisit(Visitor $visitor): bool 
    {

        // Préparation de la requête
        $sql = "INSERT INTO visitor (id_article, user_agent) 
                VALUES (:id_article, :user_agent)";
        
        // 3. Exécution de la requête
        $result = $this->db->query($sql, [
            'id_article' => $visitor->getIdArticle(),
            'user_agent' => $visitor->getUserAgent()
        ]);
        return $result->rowCount() > 0;

    }
}