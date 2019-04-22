<?php
require_once 'tools/common.php';

if(!isset($_SESSION['user']) || $_SESSION['user']['is_admin'] != 0){
    header('location:index.php');
    exit;
}

$queryInfoUser = $db->prepare('SELECT firstname, lastname, email, bio  FROM user WHERE id = ?');
$result = $queryInfoUser->execute([$_SESSION['user']['id']]);
$infoUser = $queryInfoUser->fetch();

$lastname = $infoUser['lastname'];
$firstname = $infoUser['firstname'];
$email = $infoUser['email'];
$bio = $infoUser['bio'];

if (isset($_POST['update'])) {

    $lastname= $_POST['lastname'];
    $firstname = $_POST['firstname'];
    $email = $_POST['email'];
    $bio = trim($_POST['bio']);

    if (empty($_POST['lastname'])) {
        $messages['lastnamee'] = 'le nom est obligatoire';
    }
    if (empty($_POST['firstname'])) {
        $messages['firstname'] = 'la prénom est obligatoire';
    }
    if (empty($_POST['email'])) {
        $messages['email'] = 'l\'email est obligatoire ';
    }
    if (!empty($_POST['password']) && empty(($_POST['password_confirm']))){
        $messages['password_confirm'] = 'Veuillez confirmer votre mot de passe ';
    }
    if (!empty($_POST['password']) && $_POST['password_confirm'] != $_POST['password']){
        $messages['password_check'] = 'la confirmation de mot de passe ne corresspond pas avec le mot de passe  ';
    }
    if (!empty($_POST['password_confirm']) && empty($_POST['password'])){
        $messages['password'] = 'Veuillez remplir votre mot de passe';
    }

    if (empty($messages)) {

        $query = 'UPDATE user  SET lastname = ?, firstname = ?,  email = ?, bio = ?';

        $result = [
            $_POST['lastname'] ,
            $_POST['firstname'],
            $_POST['email'],
            $_POST['bio'],
        ];

        if (!empty($_POST['password'])){
            $query = $query . ', password = ?  WHERE id = ?';
            $result[] = md5($_POST['password']);
        }else{
            $query = $query. 'WHERE id = ?';
        }
        $result[] = $_SESSION['user']['id'];

        $query = $db->prepare($query);
        $result = $query->execute($result);

        $_SESSION['user']['firstname'] = $_POST['firstname'];

        $msg = '<div class="bg-success text-white p-2 mb-4">Modification effectuer.</div>';
    }
}

?>
