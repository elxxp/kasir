<?php
if(!isset($_SESSION['username'])){
    header('location: login.php');

    setcookie('notAllowed', 'ok', time() + 1, "/");
    header('location: ../view/login.php');
    exit;
}
?>