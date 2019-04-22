<!DOCTYPE html>
<html>
<head>

    <title><?php if (isset($_GET['category_id'])): ?><?php echo $selectedCategory['name']; ?><?php else: ?>Tous les articles<?php endif; ?>
        - Mon premier blog !</title>
    <?php require 'partials/views/head_assets.php'; ?>

</head>
<body class="article-list-body">
<div class="container-fluid">

    <?php require 'partials/views/header.php'; ?>

    <div class="row my-3 article-list-content">

        <?php require 'partials/controllers/nav.php'; ?>

        <main class="col-9">
            <section class="all_aricles">
                <header>
                    <h1 class="mb-4">
                    <?php if (isset($_GET['category_id'])): ?>
                        <?php echo $selectedCategory['name']; ?>
                    <?php elseif (isset($_GET['article_id'])):?>
                        <?= $articles[0]['title']; ?>
                    <?php else: ?>
                       Tous les articles :
                    <?php endif; ?>
                    </h1>
                </header>

                <?php if (isset($_GET['category_id'])): ?>
                    <div class="category-description mb-4">
                        <?= $selectedCategory['description']; ?>
                    </div>
                <?php endif; ?>
                <!-- s'il y a des articles à afficher -->
                <?php if (count($articles) > 0): ?>
                    <?php foreach ($articles as $key => $article): ?>
                        <?php ///if (!isset($_GET['category_id']) OR (isset($_GET['category_id']) AND $article['category_id'] == $_GET['category_id'])): ?>
                            <article class="mb-4">
                                <?php if (!isset($_GET['article_id'])):?>
                                <h2><?php echo $article['title']; ?></h2>
                                <?php endif; ?>
                                <?php if (isset($article['image'])) : ?>
                                    <img class="img-fluid" src="img/article/<?= $article['image'] ?>"
                                         alt="<?= $article['title'] ?> "/>
                                <?php endif; ?>

                                <?php if (!isset($_GET['category_id'])): ?>
                                    <span class="article-category">[<?php echo $article['category_name']; ?>] </span>
                                <?php endif; ?>
                                <span class="article-date">
									<!-- affichage de la date de l'article selon le format %A %e %B %Y -->
									<?php echo strftime("%A %e %B %Y", strtotime($article['created_at'])); ?>
								</span>
                                <div class="article-content">
                                    <?php if (isset ($article['content'])): ?>
                                    <?= $article['content'] ?>
                                    <?php else : ?>
                                    <?= $article['summary']; ?>
                                        <a href="index.php?page=article-list&article_id=<?= $article['id']; ?>">> Lire l'article</a>
                                    <?php endif ;?>
                                </div>

                            </article>
                        <?php/// endif; ?>
                    <?php endforeach; ?>
                    <!-- s'il n'y a pas d'articles à afficher -->
                <?php else: ?>
                    aucun article
                <?php endif; ?>
            </section>
        </main>

    </div>

    <?php require 'partials/views/footer.php'; ?>

</div>
</body>
</html>
