DROP TABLE IF EXISTS `visitor`;
CREATE TABLE `visitor` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_article INT NOT NULL,
    user_agent TEXT,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_article) REFERENCES article(id) ON DELETE CASCADE
);