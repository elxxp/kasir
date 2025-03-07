<?php
session_start();
require 'koneksi.php';
require 'functions.php';
pageExcept('restocker');

if(isset($_POST['hapusPelanggan'])){
    $id = $_POST['hapusPelanggan'];

    $sql = "DELETE FROM pelanggan WHERE pelangganID=$id";
    $cek = mysqli_query($koneksi, $sql);

    setcookie('statusRemove', 'ok', time() + 1, "/");
    header('location: ../view/daftar-pelanggan.php');
}
?>