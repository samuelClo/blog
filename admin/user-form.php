<?php
require_once '../tools/common.php';

if (!isset($_SESSION['user']) OR $_SESSION['user']['is_admin'] == 0) {
    header('location:../index.php');
    exit;
}

//Si $_POST['save'] existe, cela signifie que c'est un ajout d'utilisateur
if (isset($_POST['save'])) {

    $query = $db->prepare('INSERT INTO user (firstname, lastname, password, email, is_admin, bio) VALUES (?, ?, ?, ?, ?, ?)');
    $newUser = $query->execute([
        htmlspecialchars($_POST['firstname']),
        htmlspecialchars($_POST['lastname']),
        hash('md5', $_POST['password']),
        htmlspecialchars($_POST['email']),
        $_POST['is_admin'],
        htmlspecialchars($_POST['bio']),
    ]);

    //redirection après enregistrement
    //si $newUser alors l'enregistrement a fonctionné
    if ($newUser) {
        header('location:user-list.php');
        exit;
    } else { //si pas $newUser => enregistrement échoué => générer un message pour l'administrateur à afficher plus bas
        $message = "Impossible d'enregistrer le nouvel utilisateur...";
    }
} else if (isset($_GET["user_id"], $_GET["action"]) && $_GET["action"] == "edit") {

    $queryUser = $db->prepare('SELECT * FROM user WHERE id = ?');
    $result = $queryUser->execute([$_GET["user_id"]]);
    $user = $queryUser->fetch();

    $firstname = $user['firstname'];
    $lastname = $user['lastname'];
    $email = $user['email'];
    $bio = $user['bio'];
    $isAdmin = $user['is_admin'];


    if (isset($_POST['submit'])) {


        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $bio = $_POST['bio'];
        $isAdmin = $_POST['is_admin'];


        if (empty($_POST['email'])) {
            $messages['$email'] = 'L\'email est obligatoire';
        }
        if (empty($_POST['firstname'])) {
            $messages['$firstname'] = 'Le prénom est obligatoire';
        }
        if (empty($_POST['lastname'])) {
            $messages['$lastname'] = 'Le choix du nom est obligatoire';
        }
        if (empty($messages)) {



            $queryUser = $db->prepare('UPDATE user SET firstname = ?, lastname= ?, email = ?, is_admin = ?, bio = ?    WHERE id = ? ');
            $resultUser = $queryUser->execute(
                [
                    $_POST['firstname'],
                    $_POST['lastname'],
                    $_POST['email'],
                    intval($_POST['is_admin']),
                    $_POST['bio'],
                    intval($_GET["user_id"])
                ]
            );
            $msg = '<div class="bg-success text-white p-2 mb-4">Modification effectuer </div>';


        }
    }
}

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
            <header class="pb-3">


                <?php if (isset($msg)) : ?>
                <?= $msg ?>
                <?php endif; ?>


                <h4>
                    <?php if (isset($_GET["user_id"], $_GET["action"]) && $_GET["action"] == "edit"): ?>
                        <?php echo "Modifier un utilisateur"; ?>
                    <? else: echo "Ajouter un utilisateur"; ?>
                    <?php endif; ?>
                </h4>
            </header>

            <?php if (isset($message)): //si un message a été généré plus haut, l'afficher ?>
                <div class="bg-danger text-white">
                    <?= $message; ?>
                </div>
            <?php endif; ?>


            <form action="<?php if (isset($_GET["user_id"], $_GET["action"]) && $_GET["action"] == "edit") : ?>
                         <?php echo 'user-form.php?user_id=' . $_GET["user_id"] . '&action=edit'; ?>
                            <?php else: ?>user-form.php<?php endif; ?>


                    " method="post">

                <div class="form-group">
                    <label for="firstname">Prénom :</label>
                    <input class="form-control"
                           value="<?php if (isset($firstname)) : ?><?= $firstname; ?><?php endif; ?>" type="text"
                           placeholder="Prénom" name="firstname"
                           id="firstname"/>
                </div>
                <div class="form-group">
                    <label for="lastname">Nom de famille : </label>
                    <input class="form-control" value="<?php if (isset($lastname)) : ?><?= $lastname; ?><?php endif; ?>"
                           type="text" placeholder="Nom de famille" name="lastname"
                           id="lastname"/>
                </div>
                <div class="form-group">
                    <label for="email">Email :</label>
                    <input class="form-control" value="<?php if (isset($email)) : ?><?= $email; ?><?php endif; ?>"
                           type="email" placeholder="Email" name="email" id="email"/>
                </div>
                <?php if (!isset($_GET["user_id"], $_GET["action"])) : ?>
                    <div class="form-group">
                        <label for="password">Password : </label>
                        <input class="form-control" type="password" placeholder="Mot de passe" name="password"
                               id="password"/>
                    </div>
                <?php endif; ?>


                <div class="form-group">
                    <label for="bio">Biographie :</label>
                    <textarea class="form-control" name="bio" id="bio"
                              placeholder="Sa vie son oeuvre..."><?php if (isset($bio)) : ?><?= $bio; ?><?php endif; ?></textarea>
                </div>
                <div class="form-group">
                    <label for="is_admin"> Admin ?</label>
                    <select class="form-control" name="is_admin" id="is_admin">
                        <option value="0" <?php if ( $isAdmin == 0): ?><?php echo 'selected' ?><?php endif ?> >
                            Non
                        </option>
                        <option value="1" <?php if ( $isAdmin == 1): ?><?php echo 'selected' ?><?php endif ?> >
                            Oui
                        </option>
                    </select>
                </div>


                <div class="text-right">

                    <?php if (isset($_GET["user_id"], $_GET["action"]) && $_GET["action"] == "edit") : ?>
                        <input class="btn btn-success" type="submit" name="submit" value="Changer l'utilisateur"/>
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
