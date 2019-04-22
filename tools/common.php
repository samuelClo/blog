<?php

//fichier de fonctionnalités communes à plusieurs scripts

//paramétrage de la langue de traduction pour PHP


//connexion à la base de données
setlocale(LC_ALL, "fr_FR");
function dbConnect(){
	try{
		return $db = new PDO('mysql:host=localhost;dbname=blog;charset=utf8', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
	}
	catch (Exception $exception)
	{
		die( 'Erreur : ' . $exception->getMessage() );
	}
}
$db = dbConnect();
session_start();


//ouverture de session pour connexions utilisateurs et admins


?>
