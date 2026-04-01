## Blog d'Emilie Forteroche

- Projet réalisé dans le cadre de la formation OpenClassrooms
- Objectif : enrichir une application blog existante avec des fonctionnalités d’administration

## Contexte du projet

Ce projet consiste à améliorer un blog existant en ajoutant :

- un système de **monitoring des articles**
- une fonctionnalité de **suppression des commentaires**
- une **interface d’administration ergonomique**

## Environnement technique

- PHP (architecture MVC)
- MySQL
- HTML / CSS (sans JavaScript)
- Serveur local XAMPP

## Installation (via starter kit)

- Cloner le dépôt GitHub.
- Placer le projet dans un environnement serveur local.
- Importer la base de données "blog_forteroch" via le fichier : blog_forteroche.sql

## Architecture technique

L’application repose sur une architecture **MVC**.

```text
[Navigateur]
     │
     ▼
[Routeur] (index.php)
     │
     ▼
[Controller ......................]
     │        ▲                 │
     ▼        │                 ▼
[Model .............]         [View]
(Managers + Entités)          (Templates PHP + HTML)
     │        ▲                 │
     ▼        │                 ▼
[Base de données]             [HTML généré]
                                │
                                ▼
                              [Navigateur]
```

## Fonctionnalités à implémenter

### 1 : monitoring des articles

- Créer une nouvelle page dans la partie admin dont le but est d'afficher pour chaque article : son titre, le nombre de vues, le nombre de commentaires et la date de publication de l'article.
- Ce tableau pourra être trié (croissant et décroissant) en fonction de ces quatres critères (vues, commentaires, date et titre).
- Pas besoin de version mobile de cette page, assurez-vous simplement que je puisse l’afficher correctement sur mon ordinateur (1 366 px de large).
- Pour la mise en forme, il faut simplement garder l’identité visuelle du reste du site (couleurs, polices, etc.). Je voudrais également une ligne sur deux avec un fond de couleur différente pour plus de lisibilité.

### 2 : suppression des commentaires

- Créer un système pour que, lorsque je suis connectée, je puisse facilement supprimer certains commentaires.

### 3 : interface d'administration

- Déterminer une solution pour accéder à cette page. Essayez de vous mettre à la place d’Emilie qui doit accéder régulièrement à ce site, pour lui proposer une interface la plus agréable possible

## Détail des implémentations

### 1 - Monitoring des articles

#### Stockage des données

Pour stocker le nombre de vues par article, j'ai opté pour la création d'une table dédiée que j'ai nommée "article_visits".
Cette approche me semble plus pérenne qu'un simple ajout de champ à la table "article" (séparation des responsabilités).

En effet, avec une table dédiée, on peut envisager de stocker d'autres données statistiques liées aux articles (Extensibilité).

Pour ce projet, en plus du compteur de vues, j'ai ajouté la date de la dernière viste.

Structure de cette table :

```sql
CREATE TABLE `article_visits` (
id INT AUTO_INCREMENT PRIMARY KEY,
id_article INT NOT NULL,
nb_visits INT DEFAULT 0,
last_visit_at DATETIME DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (id_article) REFERENCES article(id) ON DELETE CASCADE);
```

#### Principe du comptage du nombre de vues

Le dispositif est composé de 2 classes de type "Modèle" :

- la classe entité : **"ArticleVisits"**
- la classe manager : **"ArticleVisitsManager"** qui dispose de 2 méthodes :
  - **getVisitsByArticleId** : Récupère les statistiques de visites d'un article par son id.
  - **trackVisit** : Incrémente le compteur de visites et enregistre la date de la visite.

J'ai intégré un dispositif de comptage du nombre de vues au niveau de la méthode "showArticle" du contrôleur "ArticleController".

```PHP
// Récupération des statistiques de visites de l'article.
$articleVisitsManager = new ArticleVisitsManager();
$articleVisit = $articleVisitsManager->getVisitByArticleId($id);
// ......
// On enregistre la visite de l'article.
$articleVisitsManager->trackVisit($articleVisit);
```

Ainsi à chaque fois qu'un article est affiché, le dispositif incrémente le nombre de vues.

Par contre, Ce dispositif :

- ne fonctionne que pour les utilisateurs non connectés (les lecteurs du blog).
- ne s'active que si la dernière visite à eu lieu depuis au moins un certain temps. Ainsi on évite de compter des vues si le lecteur raffraichie plusieurs fois la page ou s'il enregistre un commentaire. Ce temps (en secondes) est fixée par la constante TTL_VISIT.

#### Principe de récupération des informations à afficher avec tri dynamique

Rappel du besoin : la nouvelle page doit afficher un tableau avec pour chaque ligne : le titre de l'article, le nombre de vues, le nombre de commentaires et la date de publication de l'article.
J'ai décidé d'ajouter la date de la dernière visite et d'afficher également les articles qui n'ont jamais eu de visite.
Voici la requête SQL utilisée :

```sql
SELECT a.id as id_article, a.title, a.date_creation as date_publication, COALESCE(b.nb_visits, 0) as visit_count,
b.last_visit_at as date_last_visit,
(SELECT COUNT(*) FROM comment c WHERE c.id_article = a.id) AS comment_count
FROM article a
LEFT JOIN article_visits b ON a.id = b.id_article
```

Le résultat de l'exécution de cette requête permet de constituer un tableau d'objets de type **ArticleVisitsReport**.

Rappel du besoin (suite) : Ce tableau pourra être trié (croissant et décroissant) en fonction de ces 4 critères (titre de l'article, nombre de vues, nombre de commentaires et date de publication).

La mise en oeuvre des différents tris est entièrement réalisée en PHP. Je m'appuie sur 2 paramètres passés dans l'URL d'appel à la page :

- [sortField] peut prendre une des valeurs suivantes : title, date_publication, visit_count, comment_count ou date_last_visit
- [orderBy] peut prendre une des valeurs suivantes : asc (tri croissant) ou desc (tri décroissant)

Je soumets ensuite le tableau d'objets **ArticleVisitsReport** à l'instruction php **usort** en lui passant en paramètre les valeurs de **sortField** et **orderBy**.

#### Principe de génération du tableau d'affichage des résultats

##### Route

- action=showMonitorArticleVisits
- paramètres optionnels : **sortField** et **orderBy** : prennent pour valeur respective : Le champ et la direction du tri. S'ils sont absent, le tableau sera trié par défaut sur le nombre de vues dans l'ordre décroissant.

##### Flux d'exécution

Le routeur "index.php" :

- instancie un objet de la classe "AdminController" (le contrôleur).
- Appelle la méthode "showMonitorArticleVisits()".

La méthode "showMonitorArticleVisits()" :

- Vérifie que l'on est bien dans une session d'un utilisateur connecté (un admin).
- Récupère la valeur du paramètre "sort" si elle existe.
- Instancie un objet de la classe modèle "MonitorArticleVisitsManager()".
- Appelle la méthode "getArticlesReport()" en lui passant en paramètre les critères du tri (sortField et orderBy).

la méthode "getArticlesReport()" :

- Vérifie que les critères de tri sont valides.
- Prépare la requête SQL en fonction des critères de tri.
- Exécute la requête.
- Effectue le tri selon les critères.
- Retourne à la méthode "showMonitorArticleVisits()", du contrôleur, un tableau d'objets ArticleVisitsReport trié.

La méthode showMonitorArticleVisits() :

- Instancie un objet de la classe "View" et lui passant en paramètre le titre de la page : "Monitoring des articles".
- Appelle la méthode "render" en lui passant en paramètre :
  - le tableau des objets "ArticleVisitsReport" (résultat du tri).
  - le nom de la vue dédiée à l'affichage : "monitorArticleVisits".

La méthode render() :

- Récupère le chemin vers le template HTML associé à la vue : "monitorArticleVisits.php"
- Initialise la variable $title avec le titre de la page : "Monitoring des articles"
- Initialise la variable $content avec le template HTML et les données du tableau des objets "ArticleVisitsReport" (résultat de la requête triée).
- Inclut le layout principal : main.php qui complète son contenu avec les variables $title et $content et affiche la page finale dans le navigateur du client

#### Principe d'affichage de la page du tableau des résultats

Pour afficher les résultats j'ai opté pour un tableau HTML structuré balises :

```HTML
<table></table>
```

C'est un tableau à 5 colonnes : Titre, Vues, Commentaires, Publié le, Dernière visite le

Pour gérer les tris, j'ai ajouté au niveau de l'entête de chaque colonne 2 icônes flèches (font awesome) :

- une flèche vers le haut qui symbolise le tri croissant
- une flèche vers le bas qui symbolise le tri décroissant
  Chaque flèche est cliquable : c'est un lien vers la page courante avec les paramètres **sortField** et **orderBy** initialisés avec les différentes options de tri.

Pour gérer l'affichage d'une ligne sur deux avec un fond de couleur différent, j'ai utilisé la pseudo-classe CSS **nth-child()** qui sélectionne un élément en fonction de sa position parmi ses frères.

```css
.table-articles tbody tr:nth-child(odd) {
  background-color: var(--headerPaleColor);
}
.table-articles tbody tr:nth-child(even) {
  background-color: white;
}
```

La ligne survolée par le curseur change également de style pour encore améliorer la lisisbilité

```css
.table-articles tbody tr:hover {
  background-color: var(--quoteColor);
}
```

Enfin, et toujours pour améliorer l'expérience d'utilisation, la ligne d'entête du tableau, celle avec le nom des colonnes et les flèches de tris, reste présente en haut de l'écran même si l'utilisateur scrolle vers le bas.

```css
.table-articles thead th {
  ...
  position: sticky;
  top: 0;
  z-index: 10;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}
```

Pour la mise en forme, j'ai utilisé des couleurs et polices de l’identité visuelle du du site.

Les pictos des flèches de tri sont des fonts awesome.

## 2 - Suppression des commentaires

#### Solution

Ma solution pour gérer la suppression des commentaires est de faire apparaître des boutons de suppression directement sous chaque commentaire au niveau de la page détail d'un article.
Les boutons de suppression sont affichés uniquement si l'utilisateur est connecté (admin).

#### Logique de mise en oeuvre de la suppression d'un commentaire

##### Route

- action=deleteComment
- paramètre supplémentaire requis :
  - id_comment = l'id du commentaire à supprimer
  - id_article : l'id de l'article

##### Flux d'exécution

Le routeur "index.php" :

- instancie un objet de la classe "AdminController" (le contrôleur).
- Appelle la méthode "deleteComment()".

La méthode "deleteComment()" :

- Vérifie que l'on est bien dans une session d'un utilisateur connecté (un admin).
- Récupère la valeur du paramètre "id_comment" : l'id du commentaire à supprimer
- Récupère la valeur du paramètre "id_article" : l'id de l'article du commentaire à supprimer
- Instancie un objet de la classe modèle "CommentManager()".
- Appelle la méthode "deleteCommentById" en lui passant en paramètre l'id du commentaire à supprimer.

La méthode "deleteCommentById" :

- Supprime le commentaire en base de donnée.
- Retourne le statut de suppression à la méthode deleteComment() de l'objet "AdminController".

La méthode "deleteComment()" de l'objet "AdminController" :

- Effectue une redirection HTTP vers la page d'affichage du détail de l'article en lui passant en paramètre l'id de l'article.

## 3 - interface d'administration

#### Solution

J'ai opté pour la mise en oeuvre d'un menu déroulant qui apparait en haut à droite de l'écran.

Lorsque l'utilisateur est déconnecté la barre de menu propose l'option CONNEXION. En cliquant sur ce lien on accède au formulaire de connexion.

Une fois connecté l'option CONNEXION est remplacée par l'option ADMIN précédé par un icône "burger" (font awesome). En cliquant sur admin on fait apparaître le menu juste en dessous. Ce menu propose trois options :

- Edition des articles.
- Monitoring des articles.
- Deconnexion.

#### Mise en oeuvre technique

Ce menu est entièrement géré en HTML / CSS sans Javascript

## Correction de bugs au niveau de la version starter récupérée depuis GitHub

#### Crash lors de la création d'un nouvel article

Le problème vient du fait du typage strict de la méthode "setDateUpdate" de la classe Article. cette méthode n’accepte pas des dates null. Or, c’est ce que l’on lui passe en paramètre lors de l’hydratation d’un objet Article qui n’a pas encore de date de modification.

Pour résoudre le problème j'ai modifier le typage de la fonction "setDateUpdate" de la classe "Article" pour qu’elle accepte également des dates null :

```php
public function setDateUpdate(string|DateTime|null $dateUpdate, string $format = 'Y-m-d H:i:s') : void
```

#### Problème d'affichage des cards articles sur la HP

Si sur une ligne on n'a que 2 cards, la 1ère s'affiche à gauche et la 2nd à droite , laissant un trou au milieu.

Pour résoudre le problème, j'ai adapté les attributs CSS des cards.

#### Bug sur l'option de menu CONNEXION

Cette option de menu n'était jamais proposée.

J'ai adapté le layout main.php pour faire apparaître cette option lorsque l'utilisateur n'est pas connecté

## Lancez le projet !

Pour la configuration du projet renomez le fichier _\_config.php_ (dans le dossier _config_) en _config.php_ et éditez le si nécessaire.
Ce fichier contient notamment les informations de connextion à la base de données et la valeur de TTL_VISIT

Pour vous connecter en partie admin, le login est "Emilie" et le mot de passe est "password" (attention aux majuscules)
