<?php 
/**
 * Ce fichier est le template principal qui "contient" ce qui aura été généré par les autres vues.  
 * 
 * Les variables qui doivent impérativement être définie sont : 
 *      $title string : le titre de la page.
 *      $content string : le contenu de la page. 
 */

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="./css/style.css">
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    />
</head>

<body>
    <header>
        <nav>
            <a href="index.php">Articles</a>
            <a href="index.php?action=apropos">À propos</a>
            <?php 
                // Si on est connecté, on affiche le menu d'administration, sinon, on affiche le bouton de connexion : 
                if (isset($_SESSION['user'])) { 
                ?>
                    <div class="menu-deroulant">
                        <span class="menu-titre"><i class="fa-solid fa-bars"></i>Admin</span>
                        <div class="sous-menu">
                            <a href="index.php">Edition des Articles</a>
                            <a href="index.php?action=commentaires">Commentaires</a>
                            <a href="index.php?action=stats">Stats</a>
                            <a href="index.php?action=disconnectUser">Déconnexion</a>
                        </div>
                    </div>
            <?php
                // echo '<a href="index.php?action=disconnectUser">Déconnexion</a>';
                } else {
                    echo '<a href="index.php?action=connectionForm">Connexion</a>';
                }
                ?>
        </nav>
        <h1>Emilie Forteroche</h1>
    </header>

    <main>    
        <?= $content /* Ici est affiché le contenu réel de la page. */ ?>
    </main>
    
    <footer>
        <p>Copyright © Emilie Forteroche 2023 - Openclassrooms - <a href="index.php?action=admin">Admin</a>
    </footer>

</body>
</html>