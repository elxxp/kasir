<?php
session_start();
require '../process/cek.php';
require '../process/koneksi.php';

if(isset($_POST['updateProduk'])){
    $idProduk = $_POST['id'];

    $sqlGetProduk = "SELECT * FROM produk WHERE produkID = $idProduk";
    $rstProduk = mysqli_query($koneksi, $sqlGetProduk);
    $produk = $rstProduk->fetch_assoc();

    function namaProduk($id){
        global $koneksi;
        $sqlGetNamaProduk = "SELECT namaProduk FROM produk WHERE produkID = $id";
        $rstGetNamaProduk = mysqli_query($koneksi, $sqlGetNamaProduk);
        $dataProdukGetNamaProduk = $rstGetNamaProduk->fetch_assoc();
        $produk = $dataProdukGetNamaProduk['namaProduk'];
        return $produk;
    }
    function hargaBarang($id){
        global $koneksi;
        $sqlGetHargaProduk = "SELECT harga FROM produk WHERE produkID = $id";
        $rstGetHargaProduk = mysqli_query($koneksi, $sqlGetHargaProduk);
        $dataProdukGetHargaProduk = $rstGetHargaProduk->fetch_assoc();
        $hargaProduk = $dataProdukGetHargaProduk['harga'];
        return $hargaProduk;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <p>ID Produk : #<?= $idProduk ?></p>
    <p>Nama Produk : <?= ucfirst(namaProduk($idProduk)) ?></p>
    <p>Harga Produk : Rp. <?= number_format(hargaBarang($idProduk)) ?></p>
    <form method="post">
        <span>Stok : </span><input type="number" name="updStok" value="<?= $produk['stok'] ?>"></input>
    </form>

    <a href="daftar-produk.php">kembali</a>
</body>
</html>