<?php 
    /** 
     * Affichage de la partie admin : liste des visites. 
     */
?>

<h2>Liste des dernières visites</h2>

<div class="adminArticle">
    <?php foreach ($visitorsReport as $visitor) {  ?>
        <div class="articleLine">
            <div class="title"><?= $visitor->getArticleTitle() ?></div>
            <div class="content"><?= $visitor->getUserAgent() ?></div>
            <div class="content"><?= ucfirst(Utils::convertDateToFrenchFormat($visitor->getVisitDate(),true)) ?></div>
        </div>
    <?php } ?>
</div>