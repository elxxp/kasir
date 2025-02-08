<?php
session_start();
require '../process/cek.php';
require '../process/koneksi.php';

$sqlPelanggan = "SELECT * FROM pelanggan;";
$rstPelanggan = mysqli_query($koneksi, $sqlPelanggan);

// ngambil id penjualan abis milih pelanggan
$sqlGetPenjualan = "SELECT max(penjualanID) AS penjualanID FROM penjualan;";
$rstPenjualan = mysqli_query($koneksi, $sqlGetPenjualan);
$dataProdukPenjualan = $rstPenjualan->fetch_assoc();

if(@$dataProdukPenjualan['penjualanID']){
    $penjualanID = $dataProdukPenjualan['penjualanID'] + 1;
} else {
    $penjualanID = 1;
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
    <br><br>
    <form action="tambah-produk-penjualan.php" method="post">
        <select name="pelanggan">
            <option value="null"> --- pilih pelanggan --- </option>
                <?php while($data = $rstPelanggan->fetch_assoc()): ?>
                <option value="<?= $data['pelangganID'] ?>"><?= $data['namaPel'] ?></option>
                <?php endwhile; ?>
        </select>
        <input type="hidden" name="next" value="true">
        <input type="hidden" name="addPenjualID" value="true">
        <input type="hidden" name="penjualanID" value="<?= $penjualanID ?>">
        <button name="tambahProduk">lanjut</button>
    </form>
    <a href="daftar-penjualan.php">kembali</a>
</body>
</html>