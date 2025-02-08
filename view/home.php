<?php
session_start();
require '../process/cek.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Aplikasi Kasir</h1>
    <p>Profile : <?= $_SESSION['username'] ?></p>

    <a href="daftar-pelanggan.php">daftar pelanggan</a><br>
    <a href="daftar-penjualan.php">daftar penjualan</a><br>
    <a href="daftar-produk.php">daftar produk</a><br>
    <a href="daftar-akun.php">daftar akun</a><br><br>
    <a href="../process/logout.php">keluar</a>
</body>
</html>