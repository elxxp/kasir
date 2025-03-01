<?php
require 'koneksi.php';
require 'functions.php';
pageExcept('restocker');

if(isset($_POST['hapus'])){
    $id = $_POST['hapus'];

    $sqlGetDaftarProduk = "SELECT * FROM detailpenjualan WHERE penjualanID = $id";
    $rstDaftarProduk = mysqli_query($koneksi, $sqlGetDaftarProduk);

    if(mysqli_num_rows($rstDaftarProduk) <= 0){
        $sql = "DELETE FROM penjualan WHERE penjualanID = $id";
        $cek = mysqli_query($koneksi, $sql);
    
        setcookie('statusRemove', 'ok', time() + 1, "/");
        header('location: ../view/daftar-penjualan.php');
    } else {
        setcookie('statusRemoveFalse', 'ok', time() + 1, "/");
        header('location: ../view/daftar-penjualan.php');
    }
}
?>