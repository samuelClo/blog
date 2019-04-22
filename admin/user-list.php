<?php

require_once '../tools/common.php';

if(!isset($_SESSION['user']) OR $_SESSION['user']['is_admin'] == 0){
	header('location:../index.php');
	exit;
}
if (isset($_GET["user_id"], $_GET["action"]) && $_GET["action"] == "delete") {

    $deleteArticle = $db->prepare('DELETE FROM user WHERE id = ?  ');

    $articleDeleted = $deleteArticle->execute(
        [
            $_GET["user_id"]
        ]
    );
    $msg = '<div class="bg-success text-white p-2 mb-4">Suppression  effectuer.</div>';
}

//séléctionner tous les utilisateurs pour affichage de la liste
$query = $db->query('SELECT * FROM user ORDER BY id DESC');
$users = $query->fetchall();
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Administration des utilisateurs - Mon premier blog !</title>
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


						<h4>Liste des utilisateurs</h4>
						<a class="btn btn-primary" href="user-form.php">Ajouter un utilisateur</a>
					</header>

					<table class="table table-striped">
						<thead>
							<tr>
								<th>#</th>
								<th>First Name</th>
								<th>Last Name</th>
								<th>Email</th>
								<th>Admin</th>
                                <th>Action</th>
							</tr>
						</thead>
						<tbody>

							<?php if($users): ?>
							<?php foreach($users as $user): ?>

							<tr>
								<th><?= $user['id']; ?></th>
								<td><?= $user['firstname']; ?></td>
								<td><?= $user['lastname']; ?></td>
								<td><?= $user['email']; ?></td>
								<td><?= $user['is_admin']; ?></td>
                                <td>

                                    <a href="user-form.php?user_id=<?php echo $user['id']; ?>&action=edit" class="btn btn-warning">Modifier</a>
                                    <a onclick="return confirm('Are you sure?')" href="user-list.php?user_id=<?php echo $user['id']; ?>&action=delete" class="btn btn-danger">Supprimer</a>


                                </td>

							</tr>

							<?php endforeach; ?>
							<?php else: ?>
								Aucun utilisateur enregistré.
							<?php endif; ?>

						</tbody>
					</table>
				</section>
			</div>
		</div>
	</body>
</html>
