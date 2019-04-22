<!DOCTYPE html>
<html>
<head>
    <title>Homepage - Mon premier blog !</title>
    <?php require 'partials/views/head_assets.php'; ?>
</head>
<body class="index-body">
<div class="container-fluid">

    <?php require 'partials/views/header.php'; ?>

    <div class="row my-3 index-content">

        <?php require 'partials/controllers/nav.php'; ?>

        <main class="col-9">
            <section class="latest_articles">
                <header class="mb-4"><h1>Les 3 derniers articles :</h1></header>

                <!-- les trois derniers articles -->

                <?php foreach ($articles as $key => $article): ?>
                    <article class="mb-4">
                        <h2><?php echo $article['title']; ?></h2>
                        <?php if (isset($article['image'])) : ?>
                            <img class="img-fluid" src="img/article/<?= $article['image'] ?>" alt="<?= $article['title'] ?> " />
                        <?php endif; ?>
                        <span class="article-category">[ <?= $article['category_name'] ?> ]</span>
                        <span class="article-date">
								<!-- affichage de la date de l'article selon le format %A %e %B %Y -->
								<?php echo strftime("%A %e %B %Y", strtotime($article['created_at'])); ?>
							</span>
                        <div class="article-content">
                            <?php echo $article['summary']; ?>
                        </div>
                        <a href="index.php?page=article-list&article_id=<?php echo $article['id']; ?>">> Lire l'article</a>
                    </article>
                <?php endforeach; ?>

            </section>
            <div class="text-right">
                <a href="index.php?page=article-list">Tous les articles</a>
            </div>
        </main>
    </div>

    <?php require 'partials/views/footer.php'; ?>

</div>
</body>
</html>
