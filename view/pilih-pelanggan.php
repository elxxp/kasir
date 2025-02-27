<?php
session_start();
require '../process/cek.php';
require '../process/koneksi.php';

$sqlPelanggan = "SELECT * FROM pelanggan ORDER BY namaPel ASC";
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
    <?php require '../_partials/head.html'; ?>
    <link rel="stylesheet" href="../lib/css/stylePilPelanggan.css">
</head>
<body>
    <?= @$_COOKIE['statusPilPelanggan'] ? "<div class='show notif red' id='notif'><i class='fa-solid fa-circle-xmark icon'></i><p>pilih pelanggan terlebih dahulu</p></div>" : ''?>
    <img src="../lib/images/back.jpg">
    <div class="container">
        <?php require '../_partials/header.php'; ?>

        <div class="title">
            <h3 class="title">Pilih pelanggan</h3>
        </div>

        <form action="tambah-produk-penjualan.php" method="post">
            <select name="pelanggan" class="searchable-dropdown">
                <option value="null"> --- pilih pelanggan --- </option>
                <?php while($data = $rstPelanggan->fetch_assoc()): ?>
                <option value="<?= $data['pelangganID'] ?>">#PG-<?= formatIdPelanggan($data['pelangganID']) ?> - <?= $data['namaPel'] ?></option>
                <?php endwhile; ?>
            </select>
            <input type="hidden" name="next" value="true">
            <input type="hidden" name="addPenjualID" value="true">
            <input type="hidden" name="penjualanID" value="<?= $penjualanID ?>">
            <button class="inForm" name="tambahProduk">lanjut</button>
        </form>

        <div class="content-buttons">
            <button onclick=dafPenjualan()>kembali</button>
        </div>
    </div>
</body>
<?php require '../_partials/footer.html'; ?>
<script>
    $(document).on("focus", ".select2-search__field", function() {
        $(this).attr("placeholder", "cari nama atau id pelanggan" );
    });
</script>
</html>