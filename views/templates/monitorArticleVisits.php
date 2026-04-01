<?php

/**
 * Affichage de la partie admin : liste des visites.
 */

?>

<h2>Monitoring des articles</h2>

<table class="table-articles">
  <thead>
    <tr>
      <th>
        <div class="th-content">
          Titre
          <a href="index.php?action=showMonitorArticleVisits&sortField=title&orderBy=asc" class="sort asc">
            <i class="fa-solid fa-arrow-up"></i>
          </a>
          <a href="index.php?action=showMonitorArticleVisits&sortField=title&orderBy=desc" class="sort desc">
            <i class="fa-solid fa-arrow-down"></i></a>
        </div>
      </th>
      <th>
        <div class="th-content">
          Vues
          <a href="index.php?action=showMonitorArticleVisits&sortField=visitCount&orderBy=asc" class="sort asc">
            <i class="fa-solid fa-arrow-up"></i>
          </a>
          <a href="index.php?action=showMonitorArticleVisits&sortField=visitCount&orderBy=desc" class="sort desc">
            <i class="fa-solid fa-arrow-down"></i>
          </a>
        </div>
      </th>
      <th>
        <div class="th-content">
          Commentaires
          <a href="index.php?action=showMonitorArticleVisits&sortField=commentCount&orderBy=asc" class="sort asc">
            <i class="fa-solid fa-arrow-up"></i>
          </a>
          <a href="index.php?action=showMonitorArticleVisits&sortField=commentCount&orderBy=desc" class="sort desc">
            <i class="fa-solid fa-arrow-down"></i>
          </a>
        </div>
      </th>
      <th>
        <div class="th-content">
          Publier le
          <a href="index.php?action=showMonitorArticleVisits&sortField=datePublication&orderBy=asc" class="sort asc">
            <i class="fa-solid fa-arrow-up"></i>
          </a>
          <a href="index.php?action=showMonitorArticleVisits&sortField=datePublication&orderBy=desc" class="sort desc">
            <i class="fa-solid fa-arrow-down"></i>
          </a>
        </div>
      </th>
      <th>
        <div class="th-content">
          Dernière visite le
          <a href="index.php?action=showMonitorArticleVisits&sortField=dateLastVisit&orderBy=asc" class="sort asc">
            <i class="fa-solid fa-arrow-up"></i>
          </a>
          <a href="index.php?action=showMonitorArticleVisits&sortField=dateLastVisit&orderBy=desc" class="sort desc">
            <i class="fa-solid fa-arrow-down"></i>
          </a>
        </div>
      </th>
    </tr>
  </thead>

  <tbody>
    <?php foreach ($articleVisitsReport as $articleVisitReport) {  ?>
    <tr>
      <td>
        <a href="index.php?action=showArticle&id=<?= $articleVisitReport->getIdArticle() ?>">
          <?= $articleVisitReport->getTitle() ?>
        </a>
      </td>
      <td class="num"><?= $articleVisitReport->getVisitCount() ?> vue(s)</td>
      <td class="num"><?= $articleVisitReport->getCommentCount() ?> commentaire(s)</td>
      <td><?= ucfirst(Utils::convertDateToFrenchFormat($articleVisitReport->getDatePublication())) ?></td>
      <td><?= $articleVisitReport->getDateLastVisit() !== null ? ucfirst(Utils::convertDateToFrenchFormat($articleVisitReport->getDateLastVisit(), true)) : 'jamais visité' ?></td>
    </tr>
    <?php } ?>
  </tbody>
</table>