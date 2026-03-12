<?php
// models/VisitorReport.php
class VisitorReport {
    public string $articleTitle;
    public DateTime $visitDate;
    public string $userAgent;

    // Pas besoin de logique complexe ici, juste du stockage de données, donc on peut utiliser un constructeur simple
    public function __construct(array $data) {
        $this->articleTitle = $data['title'];
        $this->userAgent = $data['user_agent'];
        $this->visitDate = DateTime::createFromFormat('Y-m-d H:i:s', $data['date_creation']);
    }

    public function getArticleTitle(): string {
        return $this->articleTitle;
    }

    public function getVisitDate(): DateTime {
        return $this->visitDate;
    }

    public function getUserAgent(): string {
        return $this->userAgent;
    }
}