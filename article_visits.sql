DROP TABLE IF EXISTS `article_visits`;
CREATE TABLE `article_visits` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_article INT NOT NULL,
    nb_visits INT DEFAULT 0,
    last_visit_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_article) REFERENCES article(id) ON DELETE CASCADE
);  