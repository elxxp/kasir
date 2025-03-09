<?php
session_start();
require '../process/cek.php';
require '../process/koneksi.php';
require '../process/functions.php';

$sqlGetJumPelanggan = "SELECT * FROM pelanggan";
$rstJumPelanggan = mysqli_query($koneksi, $sqlGetJumPelanggan);
$jumPelanggan = mysqli_num_rows($rstJumPelanggan);

$sqlGetJumPenjualan = "SELECT * FROM penjualan";
$rstJumPenjualan = mysqli_query($koneksi, $sqlGetJumPenjualan);
$jumPenjualan = mysqli_num_rows($rstJumPenjualan);

$sqlGetJumProduk = "SELECT * FROM produk";
$rstJumProduk = mysqli_query($koneksi, $sqlGetJumProduk);
$jumProduk = mysqli_num_rows($rstJumProduk);

$sqlGetJumProdukLow = "SELECT * FROM produk WHERE stok < 15 AND stok != 0";
$rstJumProdukLow = mysqli_query($koneksi, $sqlGetJumProdukLow);
$jumProdukLow = mysqli_num_rows($rstJumProdukLow);

$sqlGetJumProdukOut = "SELECT * FROM produk WHERE stok = 0";
$rstJumProdukOut = mysqli_query($koneksi, $sqlGetJumProdukOut);
$jumProdukOut = mysqli_num_rows($rstJumProdukOut);

$sqlGetJumAkun = "SELECT * FROM user";
$rstJumAkun = mysqli_query($koneksi, $sqlGetJumAkun);
$jumAkun = mysqli_num_rows($rstJumAkun);

if(isset($_COOKIE['notAllowed'])){
    $notif = "<div class='show notif red' id='notif'><i class='fa-solid fa-circle-xmark icon'></i><p>akses ditolak</p></div>";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php require '../_partials/head.html'; ?>
    <link rel="stylesheet" href="../lib/css/styleHome.css">
</head>
<body>
    <?= @$notif ?>
    <img src="../lib/images/back.jpg">
    <div class="container">
        <?php require '../_partials/header.php'; ?>

        <div class="overlayDial" id="overlayDial"></div>
        <div class="contentDial" id="contentDial" style="height: 80px;">
            <form class="formDial">
                <p class="title">Logout</p>
                <p class="sub">Keluar dari Aplikasi Kasir?</p>
                    <div class="buttons">
                        <button class="noDial" type="button" onclick=closeDialog()>kembali</button>
                        <button class="yesDial" type="button" onclick=logout()>keluar</button>
                    </div>
            </form>
        </div>

        <div class="content-welcome">
            <h1>Welcome back</h1>
            <p><?= $_SESSION['name'] ?>!</p>
        </div>
    
        <div class="content-menus">
            <center>
            <div class="menu" style="animation: objectIn 0.2s cubic-bezier(0.175, 0.885, 0.32, 1);" onclick="dafPelanggan()"><i class="fa-regular fa-address-book menu-icon"></i><div class="menu-detail"><h5>Daftar pelanggan</h5><p><?= $jumPelanggan ?> Pelanggan tersimpan</p></div></div>
            <div class="menu" style="animation: objectIn 0.4s cubic-bezier(0.175, 0.885, 0.32, 1);" onclick="dafPenjualan()"><i class="fa-regular fa-file-invoice-dollar menu-icon"></i><div class="menu-detail"><h5>Daftar penjualan</h5><p><?= $jumPenjualan ?> Struk telah tercatat</p></div></div>
            <div class="menu" style="animation: objectIn 0.6s cubic-bezier(0.175, 0.885, 0.32, 1);" onclick="dafProduk()"><i class="fa-regular fa-boxes-stacked menu-icon"></i><div class="menu-detail"><h5>Daftar produk</h5><div class="produk-status"><p><?= $jumProduk ?> <i class="fa-solid fa-box produk-status-icon"></i></p><p class="produk-low"><?= $jumProdukLow ?> <i class="fa-solid fa-square-info produk-status-icon"></i></p><p class="produk-out"><?= $jumProdukOut ?> <i class="fa-solid fa-square-xmark produk-status-icon"></i></p></div></div></div>
            <?php if($_SESSION['level'] == 'admin'){ ?>
            <div class="menu" style="animation: objectIn 0.8s cubic-bezier(0.175, 0.885, 0.32, 1);" onclick="dafAkun()"><i class="fa-regular fa-user-tie menu-icon"></i><div class="menu-detail"><h5>Daftar akun</h5><p><?= $jumAkun ?> Akun aktif</p></div></div>
            <?php } else { ?>
            <div class="menu" style="animation: objectIn 0.8s cubic-bezier(0.175, 0.885, 0.32, 1);" onclick="openDialog()"><i class="fa-regular fa-person-to-door menu-icon"></i><div class="menu-detail"><h5>Logout</h5><p>keluar dari Aplikasi Kasir</p></div></div>
            <?php } ?>
            </center>
        </div>
    </div>
</body>
<?php require '../_partials/footer.html'; ?>
<script src="../lib/js/home.js"></script>
</html>