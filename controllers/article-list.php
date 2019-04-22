<?php



$categoryId = isset($_GET['category_id']) ? $_GET['category_id'] : false ;
$articleId = isset($_GET['article_id']) ? $_GET['article_id'] : false ;


require_once ('./models/article-list.php');
$categoryId = null ;

if (isset($_GET['category_id'])){
    $categoryId = $_GET['category_id'];
    require_once ('./models/category.php');
    $selectedCategory = categoryName($categoryId);
}

$articles = getArticlelist($categoryId, false, $articleId);
require ('./views/article-list.php');



