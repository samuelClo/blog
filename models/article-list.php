<?php

function getArticlelist($categoryId = false , $limitArticles = false, $articleId = false)
{
    $db = dbConnect();
    $querySelect = 'SELECT a.id,a.title,a.created_at,a.summary,a.is_published,a.image,GROUP_CONCAT(c.name) as category_name';
    $queryParameters = '
    FROM category c
    JOIN article_category ac 
    ON ac.category_id = c.id
    JOIN article a 
    ON a.id = ac.article_id
    WHERE created_at <= NOW() 
    AND is_published = 1
';
    if ($categoryId) {
        if (!ctype_digit($categoryId)){
            header('location:index.php');
            exit;
        }
        $queryParameters .= 'AND c.id = '.$categoryId;
    }
    if ($articleId){
        $querySelect .= ',a.content';
        $queryParameters .= 'AND a.id ='.$articleId;
    }
    $queryParameters .= ' GROUP BY a.id ORDER BY created_at DESC ';
    if ($limitArticles){
        $queryParameters .= 'LIMIT '.$limitArticles;
    }

    $queryArticles = $db->query($querySelect .= $queryParameters);

    $articles = $queryArticles->fetchAll ();
    if($articles == false){
        header('location:index.php');
        exit;
    }else{
        return $articles;
    }
}

?>
