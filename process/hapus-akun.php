<?php
session_start();
require 'koneksi.php';
require 'functions.php';
pageOnly('admin');

if(isset($_POST['hapusAkun'])){
    $id = $_POST['hapusAkun'];

    $sqlRemoveAkun = "DELETE FROM user WHERE id = $id";
    mysqli_query($koneksi, $sqlRemoveAkun);

    setcookie('statusRemove', 'ok', time() + 1, "/");
    header('location: ../view/daftar-akun.php');
    exit;   
}
?>