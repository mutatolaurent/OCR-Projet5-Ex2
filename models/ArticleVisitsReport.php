<?php

/**
 * Entité ArticleVisitsReport, un articleVisitsReport est défini par les champs
 * id, title, date_publication, visit_count, comment_count, date_last_visit
 */
class ArticleVisitsReport extends AbstractEntity
{
    private int $idArticle;
    private string $title;
    private ?int $visitCount = 0;
    private ?int $commentCount = 0;
    private DateTime $datePublication;
    private ?DateTime $dateLastVisit = null;

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
     * Setter pour le titre.
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * Getter pour le titre.
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Setter pour le nombre de visites.
     * @param int $visitCount
     */
    public function setVisitCount(int $visitCount): void
    {
        $this->visitCount = $visitCount;
    }

    /**
     * Getter pour le nombre de visites.
     * @return int
     */
    public function getVisitCount(): int
    {
        return $this->visitCount;
    }

    /**
     * Setter pour le nombre de commentaires.
     * @param int $commentCount
     */
    public function setCommentCount(int $commentCount): void
    {
        $this->commentCount = $commentCount;
    }

    /**
     * Getter pour le nombre de commentaires.
     * @return int
     */
    public function getCommentCount(): int
    {
        return $this->commentCount;
    }

    /**
     * Getter pour la date de publication.
     * @return DateTime
     */
    public function getDatePublication(): DateTime
    {
        return $this->datePublication;
    }

    /**
     * Setter pour la date de publication. Si la date est une string, on la convertit en DateTime.
     * @param string|DateTime $datePublication
     * @param string $format : le format pour la convertion de la date si elle est une string.
     * Par défaut, c'est le format de date mysql qui est utilisé.
     */
    public function setDatePublication(string|DateTime $datePublication, string $format = 'Y-m-d H:i:s'): void
    {
        if (is_string($datePublication)) {
            $datePublication = DateTime::createFromFormat($format, $datePublication);
        }
        $this->datePublication = $datePublication;
    }

    /**
     * Getter pour la date de dernière visite, null si encore jamais visité.
     * @return DateTime|null
     */
    public function getDateLastVisit(): DateTime|null
    {
        return $this->dateLastVisit;
    }

    /**
     * Setter pour la date de dernière visite. Si la date est une string, on la convertit en DateTime.
     * @param string|DateTime|null $dateLastVisit
     * @param string $format : le format pour la convertion de la date si elle est une string.
     * Par défaut, c'est le format de date mysql qui est utilisé.
     */
    public function setDateLastVisit(string|DateTime|null $dateLastVisit, string $format = 'Y-m-d H:i:s'): void
    {
        if (is_string($dateLastVisit)) {
            $dateLastVisit = DateTime::createFromFormat($format, $dateLastVisit);
        }
        $this->dateLastVisit = $dateLastVisit;
    }
}
