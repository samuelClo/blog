<?php

function categoryName($categoryId)
{
    $db = dbConnect();
    $selectedCategory = $db->query('SELECT name,description FROM category WHERE id = ' . $categoryId);
    return $selectedCategory->fetch();
}
