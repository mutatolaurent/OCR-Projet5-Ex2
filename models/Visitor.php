<?php

/**
 * Entité Visitor : une vsisite est définie par les champs id, id_article, user_agent, date_creation
 * Elle représente une visite d'un article par un utilisateur, elle est enregistrée à chaque fois qu'un article est consulté.
 */ 
class Visitor extends AbstractEntity 
{
    private int $idArticle;    
    private ?string $userAgent = null;
    private ?DateTime $dateCreation = null;

    /**
     * Setter pour l'id de l'article.
     * @param int $idArticle
     */
    public function setIdArticle(int $idArticle) : void 
    {
        $this->idArticle = $idArticle;
    }

    /**
     * Getter pour l'id de l'article.
     * @return int
     */
    public function getIdArticle() : int 
    {
        return $this->idArticle;
    }

    /**
     * Setter pour le user agent.
     * @param string $userAgent
     */
    public function setUserAgent(string $userAgent) : void 
    {
        $this->userAgent = $userAgent;
    }

    /**
     * Getter pour le user agent.
     * @return string
     */
    public function getUserAgent() : string 
    {
        return $this->userAgent;
    }

    /**
     * Setter pour la date de création.
     * @param DateTime $dateCreation    
     */
    public function setDateCreation(DateTime $dateCreation) : void 
    {
        $this->dateCreation = $dateCreation;
    }

    /**
     * Getter pour la date de création.
     * @return DateTime
     */     
    public function getDateCreation() : ?DateTime {
        return $this->dateCreation;
    }
}
