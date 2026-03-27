<?php

// En fonction des routes utilisées, il est possible d'avoir besoin de la session ; on la démarre dans tous les cas.
session_start();

// Ici on met les constantes utiles,
// les données de connexions à la bdd
// et tout ce qui sert à configurer.

define('TEMPLATE_VIEW_PATH', './views/templates/'); // Le chemin vers les templates de vues.
define('MAIN_VIEW_PATH', TEMPLATE_VIEW_PATH . 'main.php'); // Le chemin vers le template principal.

define('DB_HOST', 'localhost');
define('DB_NAME', 'blog_forteroche');
define('DB_USER', ''); // A renseigner avec votre nom d'utilisateur de base de données
define('DB_PASS', ''); // A renseigner avec votre mot de passe de base de données

define('TTL_VISIT', 20); // Durée de vie d'une visite en secondes.
