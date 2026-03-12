<?php 
    /** 
     * Affichage de la partie admin : liste des visites. 
     */
    // var_dump($articlesReport);
?>

<h2>Monitoring des articles</h2>

<table class="table-articles">
  <thead>
    <tr>
      <th>
        <div class="th-content">
          Titre
          <!-- <a href="index.php?action=showMonitorArticles&sort=title_asc" class="sort asc">▲</a>
          <a href="index.php?action=showMonitorArticles&sort=title_desc" class="sort desc">▼</a> -->
          <a href="index.php?action=showMonitorArticles&sort=title_asc" class="sort asc"><i class="fa-solid fa-arrow-up"></i></a>
          <a href="index.php?action=showMonitorArticles&sort=title_desc" class="sort desc"><i class="fa-solid fa-arrow-down"></i></a>
        </div>
      </th>
      <th>
        <div class="th-content">
          Nb Vues
          <a href="index.php?action=showMonitorArticles&sort=nbvisit_asc" class="sort asc"><i class="fa-solid fa-arrow-up"></i></a>
          <a href="index.php?action=showMonitorArticles&sort=nbvisit_desc" class="sort desc"><i class="fa-solid fa-arrow-down"></i></a>
        </div>
      </th>
      <th>
        <div class="th-content">
          Nb Com.
          <a href="index.php?action=showMonitorArticles&sort=nbcomment_asc" class="sort asc"><i class="fa-solid fa-arrow-up"></i></a>
          <a href="index.php?action=showMonitorArticles&sort=nbcomment_desc" class="sort desc"><i class="fa-solid fa-arrow-down"></i></a>
        </div>
      </th>
      <th>
        <div class="th-content">
          Publication
          <a href="index.php?action=showMonitorArticles&sort=datepub_asc" class="sort asc"><i class="fa-solid fa-arrow-up"></i></a>
          <a href="index.php?action=showMonitorArticles&sort=datepub_desc" class="sort desc"><i class="fa-solid fa-arrow-down"></i></a>
        </div>
      </th>
    </tr>
  </thead>

  <tbody>
    <?php foreach ($articlesReport as $articleReport) {  ?>
    <tr>
      <td><a href="index.php?action=showArticle&id=<?= $articleReport->getIdArticle() ?>"><?= $articleReport->getTitle() ?></a></td>
      <td class="num"><?= $articleReport->getVisitCount() ?></td>
      <td class="num"><?= $articleReport->getCommentCount() ?></td>
      <td><?= ucfirst(Utils::convertDateToFrenchFormat($articleReport->getDatePublication(),true)) ?></td>
    </tr>
    <?php } ?>
  </tbody>
</table>