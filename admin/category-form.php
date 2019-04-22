<?php
require_once '../tools/common.php';

echo "ehhehe"; 

if(!isset($_SESSION['user']) OR $_SESSION['user']['is_admin'] == 0){
	header('location:../index.php');
	exit;
}

//Si $_POST['save'] existe, cela signifie que c'est un ajout d'une catégorie
if(isset($_POST['save'])){
    $query = $db->prepare('INSERT INTO category (name, description) VALUES (?, ?)');
    $newCategory = $query->execute([
		htmlspecialchars($_POST['name']),
		htmlspecialchars($_POST['description'])
	]);

    if($newCategory){
        header('location:category-list.php');
        exit;
    }
    else {
        $message = "Impossible d'enregistrer la nouvelle categorie...";
    }
}else if (isset($_GET["category_id"], $_GET["action"]) && $_GET["action"] == "edit") {

    $queryCategory = $db->prepare('SELECT * FROM category WHERE id = ?');
    $category = $queryCategory->execute([$_GET["category_id"]]);
    $categoryInfo = $queryCategory->fetch();



    $name = $categoryInfo['name'];
    $description = $categoryInfo['description'];


    if (isset($_POST['submit'])) {

        $name = $_POST['name'];
        $description = $_POST['description'];


        if (empty($_POST['name'])) {
            $messages['name'] = 'le nom est obligatoire';
        }
        if (empty($_POST['description'])) {
            $messages['description'] = 'la description est obligatoire';
        }

        var_dump($_GET["category_id"]);

        if (empty($messages)) {

            $query = $db->prepare('UPDATE category SET name = ?, description = ? WHERE id = ? ');
            $result = $query->execute(
                [
                    $_POST['name'],
                    $_POST['description'],
                    $_GET["category_id"],
                ]
            );
            $msg = '<div class="bg-success text-white p-2 mb-4">Modification effectuer.</div>';

        }
    }
}
?>

<!DOCTYPE html>
<html>
	<head>

		<title>Administration des catégories - Mon premier blog !</title>

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
                                <?php echo "Modifier une categorie "; ?>
                            <? else: echo "Ajouter une catégorie"; ?>
                            <?php endif; ?>

                        </h4>
					</header>

					<?php if(isset($message)): //si un message a été généré plus haut, l'afficher ?>
					<div class="bg-danger text-white">
						<?= $message; ?>
					</div>
					<?php endif; ?>

					<form action="<?php if (isset($_GET["category_id"], $_GET["action"]) && $_GET["action"] == "edit") : ?>
                         <?php echo 'category-form.php?category_id=' . $_GET["category_id"] . '&action=edit'; ?>
                          <?php else: ?>
                            category-form.php
                           <?php endif; ?>"
                          method="post" enctype="multipart/form-data">
						<div class="form-group">
							<label for="name">Nom :</label>
							<input class="form-control" value="<?php if (isset($name)) : ?><?= $name; ?><?php endif; ?>" type="text" placeholder="Nom" name="name" id="name" />
                            <?php if (!empty($messages['name'])) : ?>
                                <?= $messages['name']; ?>
                            <?php endif; ?>
						</div>
						<div class="form-group">
							<label for="description">Description : </label>
							<input class="form-control" value="<?php if (isset($description)) : ?><?= $description; ?><?php endif; ?>" type="text" placeholder="Description" name="description" id="description" />
                            <?php if (!empty($messages['description'])) : ?>
                                <?= $messages['description']; ?>
                            <?php endif; ?>
						</div>
						<div class="text-right">
                            <?php if (isset($_GET["category_id"], $_GET["action"]) && $_GET["action"] == "edit") : ?>
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
