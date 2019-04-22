<?php
$query = $db->query('SELECT * FROM category');
$categories = $query->fetchAll();
?>
