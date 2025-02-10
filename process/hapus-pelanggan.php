<?php
require 'koneksi.php';
if(isset($_POST['hapus'])){
    $id = $_POST['hapus'];

    $sql = "DELETE FROM pelanggan WHERE pelangganID=$id";
    $cek = mysqli_query($koneksi, $sql);

    setcookie('statusRemove', 'ok', time() + 1, "/");
    header('location: ../view/daftar-pelanggan.php');
}
?>