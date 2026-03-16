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

    /**
     * Récupère les 20 dernières visites.
     * @return array : un tableau d'objets Visitor.
     */
    public function getLastVisits(int $limit = 10): array
    {
        $sql = "SELECT a.title, v.date_creation, v.user_agent 
                FROM visitor v
                INNER JOIN article a ON v.id_article = a.id
                ORDER BY v.date_creation DESC LIMIT $limit";

        $query = $this->db->query($sql);
        // $query = $this->db->query($sql, ['limit' => $limit]);

        $reports = [];
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            // On instancie un petit objet léger pour chaque ligne
            $reports[] = new VisitorReport($row);
        }

        return $reports;
    }
}
