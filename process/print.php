<?php
session_start();
require '../process/cek.php';
require '../process/koneksi.php';
require '../process/functions.php';

$trxID = $_POST['trx'];
$custID = $_POST['cust'];

// penjualan
$sqlPenjualan = "SELECT * FROM penjualan WHERE penjualanID = $trxID";
$rstPenjualan = mysqli_query($koneksi, $sqlPenjualan);
$data = $rstPenjualan->fetch_assoc();

// detail penjualan
$sqlGetDaftarProduk = "SELECT * FROM detailpenjualan WHERE penjualanID = $trxID";
$rstDaftarProduk = mysqli_query($koneksi, $sqlGetDaftarProduk);

$sqlGetDaftarPenjualan = "SELECT totalHarga FROM penjualan WHERE penjualanID = $trxID";
$rstDaftarPenjualan = mysqli_query($koneksi, $sqlGetDaftarPenjualan);
$dataTunggalDaftarPenjualan = $rstDaftarPenjualan->fetch_assoc();

if(mysqli_num_rows($rstPenjualan) == 0){
    echo "struk kosong";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TRX-<?= formatIdPenjualan($trxID) ?> <?= namaPelanggan($custID) ?></title>
    <link rel="icon" href="../lib/images/fav.png">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.6.0/css/all.css">
    <link rel="stylesheet" href="../lib/css/font.css">
    <link rel="stylesheet" href="../lib/css/print.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jsbarcode/3.11.0/JsBarcode.all.min.js"></script>
</head>
<body <?= mysqli_num_rows($rstPenjualan) > 0? "onload=print()" : "" ?>>
    <div class="view">
        <h1>Cetak penjualan</h1>
        <?php if(mysqli_num_rows($rstPenjualan) > 0){ ?>
        <p>#TRX-<?= formatIdPenjualan($trxID) ?> | #PG-<?= formatIdPelanggan($data['pelangganID']) ?> <?= namaPelanggan($custID) ?> </p>
        <?php } else { ?>
        <p>data penjualan tidak tersedia</p>
        <?php } ?>
        <button back onclick=print()>print</button>
        <button back onclick="location.href = '../view/daftar-penjualan.php'">kembali</button>
    </div>

    <div class="content-trx">
        <div class="header">
            <i class="fa-solid fa-box-open-full logo"></i>
            <div class="brand"><h1>Aplikasi Kasir</h1><p>Jl. Terusan Ikan Piranha Atas No.50, Tunjungsekar<br>Kec. Lowokwaru, Kota Malang, Jawa Timur 65142</div>                         
        </div>

        <hr>

        <div class="trx-detail">
            <div class="detail-data">
                <h3><?= namaPelanggan($custID) ?></h3>
                <h3>#TRX-<?= formatIdPenjualan($trxID) ?></h3>
            </div>
            <h3 style="text-align: right;"><?= $data['tanggalPenjualan'] ?></h3>
        </div>

        <hr>

        <div class="tab-trx-head">
            <div class="tab-nomer">No</div>
            <div class="tab-produk">Produk</div>
            <div class="tab-harga">Harga</div>
            <div class="tab-qty">QTY</div>
            <div class="subtotal">Subtotal</div>
        </div>
        
        <div class="box-trx-data">
        <?php $nomerTrx = 0; while($dataProdukDaftarProduk = $rstDaftarProduk->fetch_assoc()): $nomerTrx++; ?>
            <div class="tab-trx-data">
                <div class="tab-nomer"><?= $nomerTrx ?>.</div>
                <div class="tab-produk"><?= namaProduk($dataProdukDaftarProduk['produkID']) ?></div>
                <div class="tab-harga">Rp. <?= number_format(hargaBarang($dataProdukDaftarProduk['produkID'])) ?></div>
                <div class="tab-qty"><?= $dataProdukDaftarProduk['jumlahProduk'] ?></div>
                <div class="subtotal">Rp. <?= number_format($dataProdukDaftarProduk['subtotal']) ?></div>
            </div>
        <?php endwhile; ?>
        </div>

        <div class="tab-trx-footer">
            <div class="tab-total">Total</div>
            <div class="tab-total-data">Rp. <?= $dataTunggalDaftarPenjualan['totalHarga'] ? number_format($dataTunggalDaftarPenjualan['totalHarga']) : "----" ?></div>
        </div>

        
        <hr>
        <div class="footer">
            <center>
                <h2>- Terimakasih telah berbelanja - </h2>
                <svg id="code"></svg>
            </center>
        </div>
    </div>
</body>
<script>
    JsBarcode("#code", "<?= $data['tanggalPenjualan'] ?> TRX-<?= formatIdPenjualan($trxID) ?>", {
    format: "CODE128",
    width: 2,
    height: 100,
    displayValue: false
    });
</script>
</html>

