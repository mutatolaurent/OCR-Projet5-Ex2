<?php

/**
 * Entité ArticleVisits : une visite est définie par les champs id, id_article, nb_visits, last_visit_at
 * Elle représente une statistique de visites d'un article par les utilisateurs,
 * elle est enregistrée à chaque fois qu'un article est consulté.
 */
class ArticleVisits extends AbstractEntity
{
    private int $idArticle;
    private ?int $nbVisits = 0;
    private ?DateTime $lastVisitAt;

    /**
     * Setter pour l'id de l'article.
     * @param int $idArticle
     */
    public function setIdArticle(int $idArticle): void
    {
        $this->idArticle = $idArticle;
    }

    /**
     * Getter pour l'id de l'article.
     * @return int
     */
    public function getIdArticle(): int
    {
        return $this->idArticle;
    }

    /**
     * Setter pour le nombre de visites.
     * @param int $nbVisits
     */
    public function setNbVisits(int $nbVisits): void
    {
        $this->nbVisits = $nbVisits;
    }

    /**
     * Getter pour le nombre de visites.
     * @return int
     */
    public function getNbVisits(): int
    {
        return $this->nbVisits;
    }

    /**
     * Setter pour la date de la dernière visite.
     * @param DateTime $lastVisitAt
     */
    // public function setLastVisitAt(DateTime $lastVisitAt) : void
    // {
    //     $this->lastVisitAt = $lastVisitAt;
    // }

    /**
     * Setter pour la date de la dernière visite. Si la date est une string, on la convertit en DateTime.
     * @param string|DateTime $lastVisitAt
     * @param string $format : le format pour la convertion de la date si elle est une string.
     * Par défaut, c'est le format de date mysql qui est utilisé.
     */
    public function setLastVisitAt(string|DateTime $lastVisitAt, string $format = 'Y-m-d H:i:s'): void
    {
        if (is_string($lastVisitAt)) {
            $lastVisitAt = DateTime::createFromFormat($format, $lastVisitAt);
        }
        $this->lastVisitAt = $lastVisitAt;
    }

    /**
     * Getter pour la date de la dernière visite.
     * @return DateTime
     */
    public function getLastVisitAt(): ?DateTime
    {
        return $this->lastVisitAt;
    }
}
