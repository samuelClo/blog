<?php

require_once '../tools/common.php';

if (!isset($_SESSION['user']) OR $_SESSION['user']['is_admin'] == 0) {
    header('location:../index.php');
    exit;
}

//Si $_POST['save'] existe, cela signifie que c'est un ajout d'article

if (isset($_FILES['image'] ) && $_FILES['image']['error'] === 0) {

        $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif');

        $my_file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);


        if (in_array($my_file_extension, $allowed_extensions)) {

            do {
                $new_file_name = time() . rand();

                $nameImg = $new_file_name . '.' . $my_file_extension;

                $destination = '../img/article/' . $new_file_name . '.' . $my_file_extension;

            } while (file_exists($destination));

        } else {
            $messages['error'] = "Fichiers non autorisé";
        }


}



if (isset($_POST['save'])) {




    $title = $_POST['title'];
    $summary = $_POST['summary'];
    $content = $_POST['content'];
    $date = $_POST['created_at'];
    $is_published = intval($_POST['is_published']);


    if (empty($_POST['title'])) {
        $messages['title'] = 'le nom est obligatoire';
    }
    if (empty($_POST['created_at'])) {
        $messages['created_at'] = 'la date est obligatoire';
    }
    if (empty($_POST['category_id'])) {
        $messages['category_id'] = 'le choix de la categorie est obligatoire';
    }

    if (empty($messages)) {


        $query = $db->prepare('
        INSERT INTO article
        (title, content, summary, created_at, is_published, image)
        VALUES (?, ?, ?, ?, ?, ?)');

        $newArticle = $query->execute(
            [
                htmlspecialchars($_POST['title']),
                htmlspecialchars($_POST['content']),
                htmlspecialchars($_POST['summary']),
                htmlspecialchars($_POST['created_at']),
                ctype_digit($_POST['is_published']),
                $nameImg
            ]
        );

        move_uploaded_file($_FILES['image']['tmp_name'], $destination);
        $lastArticleIdInsert = $db->lastInsertId();


        foreach ($_POST['category_id'] as $key => $categoryName) {


            $query = $db->prepare('
        INSERT INTO article_category
        (article_id, category_id)
        VALUES (?, ?)');

            $newArticle = $query->execute(

                [
                    $lastArticleIdInsert,
                    $categoryName
                ]
            );


        }


        //redirection après enregistrement
        //si $newArticle alors l'enregistrement a fonctionné
        if ($newArticle) {
            //redirection après enregistrement
            header('location:article-list.php');
            exit;
        } else { //si pas $newArticle => enregistrement échoué => générer un message pour l'administrateur à afficher plus bas
            $message = "Impossible d'enregistrer le nouvel article...";
        }
    }

} else if (isset($_GET["article_id"], $_GET["action"]) && $_GET["action"] == "edit") {

    $query = $db->prepare('SELECT c.id

                            FROM category c
                            JOIN article_category ac
                            ON ac.category_id = c.id
                            JOIN article a
                            ON a.id = ac.article_id
                            WHERE a.id = ?

                            ');
    $selectArticle = $query->execute([$_GET["article_id"]]);
    $categorySelected = $query->fetchAll();


    $queryArticle = $db->prepare('SELECT * FROM article WHERE id = ?');
    $selectArticle = $queryArticle->execute([$_GET["article_id"]]);
    $articles = $queryArticle->fetch();




     //var_dump($articles);

    $title = $articles['title'];
    $summary = $articles['summary'];
    $content = $articles['content'];
    $date = $articles['created_at'];
    $image = $articles['image'];
    $is_published = intval($articles['is_published']);


    if (isset($_POST['submit'])) {

        var_dump($_POST);


        $title = $_POST['title'];
        $summary = trim($_POST['summary']);
        $content = $_POST['content'];
        $date = $_POST['created_at'];



        if (empty($_POST['title'])) {
            $messages['title'] = 'le nom est obligatoire';
        }
        if (empty($_POST['created_at'])) {
            $messages['created_at'] = 'la date est obligatoire';
        }
        if (empty($_POST['category_id'])) {
            $messages['category_id'] = 'le choix de la categorie est obligatoire';
        }

        if (empty($messages)) {
            if (isset($_FILES['image'] ) && $_FILES['image']['error'] === 0) {
                unlink('../img/article/' . $image);
                $image = $nameImg;
            }

            $query = $db->prepare('UPDATE article SET title = ?, created_at = ?,  summary = ?, is_published = ?, content = ?, image= ? WHERE id = ? ');
            $result = $query->execute(
                [
                    $_POST['title'],
                    $_POST['created_at'],
                    $_POST['summary'],
                    $_POST['is_published'],
                    $_POST['content'],
                    $image,
                    $_GET["article_id"]
                ]
            );



            foreach ($_POST["category_id"] as $key => $category) {

                var_dump( $_POST["category_id"][$key]);
                var_dump( $_GET["article_id"]);


                $updateCategory = $db->prepare('UPDATE article_category
                  SET category_id = ?,
                  WHERE article_id = ?
            ');

                $categoryUpdated = $updateCategory->execute(
                    [
                        $_POST["category_id"][$key],
                        $_GET["article_id"],
                        $_GET["article_id"],
                    ]
                );

            }


            $msg = '<div class="bg-success text-white p-2 mb-4">Modification effectuer.</div>';

        }
    }
}
if (isset($_FILES['image']) && $_FILES['image']['error'] === 0){
    move_uploaded_file($_FILES['image']['tmp_name'], $destination);
}

//selection des catégories pour SELECT list plus bas
$queryCategory = $db->query('SELECT name, id FROM category');
$categories = $queryCategory->fetchAll();

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
            <header class="pb-3">

                <?php if (isset($msg)) : ?>
                    <?= $msg ?>
                <?php endif; ?>

                <h4>

                    <?php if (isset($_GET["article_id"], $_GET["action"]) && $_GET["action"] == "edit"): ?>
                        <?php echo "Modifier un article"; ?>
                    <? else: echo "Ajouter un article"; ?>
                    <?php endif; ?>


                </h4>
            </header>

            <?php if (isset($message)): //si un message a été généré plus haut, l'afficher ?>
                <div class="bg-danger text-white">
                    <?= $message; ?>
                </div>
            <?php endif; ?>

            <form action="<?php if (isset($_GET["article_id"], $_GET["action"]) && $_GET["action"] == "edit") : ?>
                         <?php echo 'article-form.php?article_id=' . $_GET["article_id"] . '&action=edit'; ?>
                          <?php else: ?>
                            article-form.php
                           <?php endif; ?>"


                  method="post" enctype="multipart/form-data"
            ">
            <div class="form-group">
                <label for="title">Titre :</label>
                <input class="form-control" type="text" placeholder="Titre" name="title" id="title"
                       value="<?php if (isset($title)) : ?><?= $title; ?><?php endif; ?>"/>
                <?php if (!empty($messages['title'])) : ?>
                    <?= $messages['title'] ?>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="summary">Résumé :</label>
                <input class="form-control" value="<?php if (isset($summary)) : ?> <?= $summary ?> <?php endif; ?>"
                       type="text" placeholder="Résumé" name="summary" id="summary"/>
            </div>
            <div class="form-group">
                <label for="content">Contenu :</label>
                <textarea class="form-control" name="content" id="content"
                          placeholder="Contenu"><?php if (isset($content)) : ?><?= $content; ?><?php endif; ?></textarea>
            </div>

            <div class="form-group">
                <label for="published_at">Date de publication :</label>
                <input class="form-control" type="date" placeholder="" name="created_at" id="published_at"
                       value="<?php if (isset($date)) : ?><?= $date; ?><?php endif; ?>"/>
                <?php if (!empty($messages['created_at'])) : ?>
                    <?= $messages['created_at']; ?>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="category_id"> Catégorie :</label>
                <select multiple class="form-control" name="category_id[]" id="categories">

                    <?php if (isset($_GET["article_id"], $_GET["action"]) && $_GET["action"] == "edit") : ?>

                        <?php foreach ($categories as $key => $category) : ?>
                            <option value="<?= $category['id']; ?>"
                                <?php
                                foreach ($categorySelected as $keyCategorySelected => $infoCategorySelected) {
                                    if ($category['id'] == $infoCategorySelected['id']) {
                                        echo "selected";
                                    }
                                }
                                ?>
                            > <?= $category['name']; ?> </option>
                        <?php endforeach; ?>

                    <?php else : ?>
                        <?php foreach ($categories as $key => $category) : ?>
                            <option value="<?= $category['id'] ?>"> <?= $category['name'] ?></option>
                        <?php endforeach; ?>

                    <?php endif ?>
                </select>
            </div>


            <?php if (!empty($messages['category_id'])) : ?>
                <?= $messages['category_id']; ?>
            <?php endif; ?>

            <div class="form-group">
                <label for="image">Image :</label>
                <input class="form-control" type="file" name="image" id="image"/>
                <?php  if (isset($image)) : ?>
                <img class="img-fluid py-4" src='../img/article/<?= $image;  ?>' alt=" " />
                <?php endif; ?>
            </div>


            <div class="form-group">
                <label for="is_published"> Publié ?</label>
                <select class="form-control" name="is_published" id="is_published">

                    <option value="0" <?php if (isset($is_published) && $is_published == 0): ?><?php echo 'selected' ?><?php endif ?> >
                        Non
                    </option>
                    <option value="1" <?php if (isset($is_published) && $is_published == 1): ?><?php echo 'selected' ?><?php endif ?> >
                        Oui
                    </option>


                </select>
            </div>


            <div class="text-right">
                <?php if (isset($_GET["article_id"], $_GET["action"]) && $_GET["action"] == "edit") : ?>
                    <input class="btn btn-success" type="submit" name="submit" value="Changer l'article"/>
                <?php else: ?>
                    <input class="btn btn-success" type="submit" name="save" value="Enregistrer"/>
                <?php endif; ?>


            </div>
            </form>

        </section>
    </div>
</div>
</body>
</html>
