<?php
require_once ('./models/article-list.php');

if (isset($_GET['logout']) && isset($_SESSION['user'])) {
    session_destroy();
}

$articles = getArticlelist(false, 3);


require ('./views/index.php');


