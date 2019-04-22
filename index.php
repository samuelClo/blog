<?php
if (isset($_GET['page'])) {

    switch ($_GET['page']) {
        case 'article-list':
            require('./controllers/article-list.php');
            break;
        case 'login-register':
            require('./controllers/login-register.php');
            break;
        case 'user-profil':
            require('./controllers/user-profile.php');
            break;
        default:
            require('./controllers/404.php');
    }

} else {
    require('./controllers/index.php');
}


?>

