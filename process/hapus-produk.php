<?php
session_start();
require 'koneksi.php';
require 'functions.php';
pageExcept('restocker');

if(isset($_POST['hapusProduk'])){
    $id = $_POST['hapusProduk'];

    $sqlRemoveProduk = "DELETE FROM produk WHERE produkID = $id";
    mysqli_query($koneksi, $sqlRemoveProduk);

    setcookie('statusRemove', 'ok', time() + 1, "/");
    header('location: ../view/daftar-produk.php');
    exit;   
}
?>