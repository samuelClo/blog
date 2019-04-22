

<!DOCTYPE html>
<html>
<head>

    <title>Profile - Mon premier blog !</title>
    <?php require 'partials/views/head_assets.php'; ?>

</head>
<body class="article-body">
<div class="container-fluid">
    <?php require 'partials/views/header.php'; ?>

    <div class="row my-3 article-content">




        <?php require 'partials/controllers/nav.php'; ?>

        <main class="col-9">
            <?php if (isset($msg)) : ?><?= $msg ?><?php endif; ?>
            <form action="user-profile.php" method="post" class="p-4 row flex-column">

                <h4 class="pb-4 col-sm-8 offset-sm-2">Mise à jour des informations utilisateur</h4>


                <div class="form-group col-sm-8 offset-sm-2">
                    <label for="firstname">Prénom <b class="text-danger">*</b></label>
                    <input class="form-control"
                           value="<?php if (isset($firstname)) : ?><?= $firstname; ?><?php endif; ?>" type="text"
                           placeholder="Prénom" name="firstname" id="firstname"/>
                    <?php if (!empty($messages['firstname'])) : ?>
                        <?= $messages['firstname']; ?>
                    <?php endif; ?>
                </div>
                <div class="form-group col-sm-8 offset-sm-2">
                    <label for="lastname">Nom de famille</label>
                    <input class="form-control" value="<?php if (isset($lastname)) : ?><?= $lastname; ?><?php endif; ?>"
                           type="text" placeholder="Nom de famille" name="lastname" id="lastname"/>
                    <?php if (!empty($messages['lastnamee'])) : ?>
                        <?= $messages['lastname']; ?>
                    <?php endif; ?>
                </div>
                <div class="form-group col-sm-8 offset-sm-2">
                    <label for="email">Email <b class="text-danger">*</b></label>
                    <input class="form-control" value="<?php if (isset($email)) : ?><?= $email; ?><?php endif; ?>"
                           type="email" placeholder="Email" name="email" id="email"/>
                    <?php if (!empty($messages['email'])) : ?>
                        <?= $messages['email']; ?>
                    <?php endif; ?>
                </div>
                <div class="form-group col-sm-8 offset-sm-2">
                    <label for="password">Mot de passe (uniquement si vous souhaitez modifier votre mot de passe
                        actuel)</label>
                    <input class="form-control" value="<?php if (isset($password)) : ?><?= $password; ?><?php endif; ?>"
                           type="password" placeholder="Mot de passe" name="password" id="password"/>
                </div>
                <div class="form-group col-sm-8 offset-sm-2">
                    <label for="password_confirm">Confirmation du mot de passe (uniquement si vous souhaitez modifier
                        votre mot de passe actuel)</label>
                    <input class="form-control"
                           type="password" placeholder="Confirmation du mot de passe" name="password_confirm"
                           id="password_confirm"/>
                    <?php if (!empty($messages['password_confirm'])) : ?>
                        <?= $messages['password_confirm']; ?>
                    <?php elseif (!empty($messages['password_check'])): ?>
                    <?= $messages['password_check']; ?>
                    <?php endif; ?>
                </div>
                <div class="form-group col-sm-8 offset-sm-2">
                    <label for="bio">Biographie</label>
                    <textarea class="form-control" name="bio" id="bio"
                              placeholder="Ta vie Ton oeuvre..."><?php if (isset($bio)) : ?><?= $bio; ?><?php endif; ?></textarea>
                </div>

                <div class="text-right col-sm-8 offset-sm-2">
                    <p class="text-danger">* champs requis</p>
                    <input class="btn btn-success" type="submit" name="update" value="Valider"/>
                </div>

            </form>
        </main>
    </div>

    <footer class="row mt-3">
        <?php require 'partials/views/footer.php'; ?>
    </footer>

    <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.1/jquery.fancybox.min.js"></script>
    <script src="js/main.js"></script>

</div>
</body>
</html>