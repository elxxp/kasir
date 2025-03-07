<?php
session_start();
if(isset($_SESSION['id_user'])){
    session_unset();
    session_destroy();

    setcookie('userLogout', 'ok', time() + 1, "/");
    header('location: ../view/login.php');
    exit;   
} else {
    header('location: ../view/login.php');
}

?>