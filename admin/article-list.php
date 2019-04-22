<?php

require_once '../tools/common.php';

if (!isset($_SESSION['user']) OR $_SESSION['user']['is_admin'] == 0) {
    header('location:../index.php');
    exit;
}
if (isset($_GET["article_id"], $_GET["action"]) && $_GET["action"] == "delete") {


    $deleteArticle = $db->prepare('DELETE FROM article_category WHERE article_id = ? ');

    $articleDeleted = $deleteArticle->execute(
        [
            $_GET["article_id"]
        ]
    );

    $selectImagePath = $db->prepare('SELECT image FROM article WHERE id = ?  ');

    $ImagePath = $selectImagePath->execute(
        [
            $_GET["article_id"]
        ]
    );


    $pathPicture = $selectImagePath->fetchColumn();

    var_dump($pathPicture);

    
    unlink('../img/article/' . $pathPicture);



    $deleteArticle = $db->prepare('DELETE  FROM article WHERE id = ?  ');

    $articleDeleted = $deleteArticle->execute(
        [
            $_GET["article_id"]
        ]
    );







    $msg = '<div class="bg-success text-white p-2 mb-4">Suppression  effectuer.</div>';


}

//séléctionner tous les articles pour affichage de la liste
$query = $db->query('SELECT * FROM article ORDER BY id DESC');
$articles = $query->fetchall();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Administration des articles - Mon premier blog !</title>
    <?php require 'partials/head_assets.php'; ?>
</head>
<body class="index-body">
<div class="container-fluid">

    <?php require 'partials/header.php'; ?>

    <div class="row my-3 index-content">

        <?php require 'partials/nav.php'; ?>

        <section class="col-9">
            <header class="pb-4 d-flex justify-content-between">


                <?php if (isset($msg)) : ?>
                    <?= $msg ?>
                <?php endif; ?>


                <h4>Liste des articles</h4>
                <a class="btn btn-primary" href="article-form.php">Ajouter un article</a>
            </header>

            <table class="table table-striped">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Titre</th>
                    <th>Publié</th>
                </tr>
                </thead>
                <tbody>
                <?php if ($articles): ?>
                    <?php foreach ($articles as $article): ?>
                        <tr>
                            <th><?= $article['id']; ?></th>
                            <td><?= $article['title']; ?></td>
                            <td>
                                <?php echo ($article['is_published'] == 1) ? 'Oui' : 'Non'; ?>
                            </td>
                            <td>
                                <a href="article-form.php?article_id=<?php echo $article['id']; ?>&action=edit"
                                   class="btn btn-warning">Modifier</a>
                                <a onclick="return confirm('Are you sure?')"
                                   href="article-list.php?article_id=<?php echo $article['id']; ?>&action=delete"
                                   class="btn btn-danger">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    Aucun article enregistré.
                <?php endif; ?>
                </tbody>
            </table>
        </section>
    </div>
</div>
</body>
</html>
