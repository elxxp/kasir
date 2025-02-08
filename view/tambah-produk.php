<?php
session_start();
require '../process/cek.php';

if(isset($_POST['tambahProduk'])){
    $nama = $_POST['namaProduk'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    
    if($nama != '' && $harga != '' && $stok != ''){
        require '../process/koneksi.php';

        $sql = "INSERT INTO produk (namaProduk, harga, stok) VALUES ('$nama', '$harga', '$stok')";
        mysqli_query($koneksi, $sql);

        header('location: daftar-produk.php');
    } else {
        echo 'data tidak lengkap';
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
    <h3>Tambah produk</h3>
    <form method="post">
        <span>Nama : </span><input type="text" placeholder="Nama produk" name="namaProduk" autocomplete="off"><br><br>
        <span>Harga : </span><input type="number" placeholder="Harga produk" name="harga" autocomplete="off"><br><br>
        <span>Stok : </span><input type="number" placeholder="Stok produk" name="stok" autocomplete="off"><br><br>
        <button name="tambahProduk">tambah</button>
    </form>
    <a href="daftar-produk.php">kembali</a>
</body>
</html>