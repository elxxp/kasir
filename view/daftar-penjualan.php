<?php
session_start();
require '../process/cek.php';
require '../process/koneksi.php';

function namaProduk($id){
    global $koneksi;
    $sqlGetNamaProduk = "SELECT namaProduk FROM produk WHERE produkID = $id";
    $rstGetNamaProduk = mysqli_query($koneksi, $sqlGetNamaProduk);
    $dataProdukGetNamaProduk = $rstGetNamaProduk->fetch_assoc();
    $produk = $dataProdukGetNamaProduk['namaProduk'];
    return $produk;
}

function namaPelanggan($id){
    global $koneksi;
    $sqlGetNamaPelanggan = "SELECT namaPel FROM pelanggan WHERE pelangganID = $id";
    $rstGetNamaPelanggan = mysqli_query($koneksi, $sqlGetNamaPelanggan);
    $dataProdukGetNamaPelanggan = $rstGetNamaPelanggan->fetch_assoc();
    $pelanggan = $dataProdukGetNamaPelanggan['namaPel'];
    return $pelanggan;
}

function hargaBarang($id){
    global $koneksi;
    $sqlGetHargaProduk = "SELECT harga FROM produk WHERE produkID = $id";
    $rstGetHargaProduk = mysqli_query($koneksi, $sqlGetHargaProduk);
    $dataProdukGetHargaProduk = $rstGetHargaProduk->fetch_assoc();
    $hargaProduk = $dataProdukGetHargaProduk['harga'];
    return $hargaProduk;
}

function jumJenisProdukTerjual($idPenjualan){
    global $koneksi;
    $sqlGetJumJenProduk = "SELECT COUNT(DISTINCT produkID) AS produk FROM detailpenjualan WHERE penjualanID = $idPenjualan";
    $rstGetJumJenProduk = mysqli_query($koneksi, $sqlGetJumJenProduk);
    $dataJumJenProduk = $rstGetJumJenProduk->fetch_assoc();
    $jumJenProduk = $dataJumJenProduk['produk'];
    return $jumJenProduk;
}

$sql = "SELECT * FROM penjualan";
$hasil = mysqli_query($koneksi, $sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php require '../_partials/head.html'; ?>
    <link rel="stylesheet" href="../lib/css/styleDafPenjualan.css">
</head>
<body>
    <?= @$notif ?>
    <img src="../lib/images/back.jpg">
    <div class="container">
        <?php require '../_partials/header.php'; ?>

        <div class="title">
            <i class="fa-regular fa-file-invoice-dollar page-icon"></i>
            <h3 class="title">Daftar penjualan</h3>
        </div>
        <p><?= @$_COOKIE['statusUpdate'] ? 'Berhasil memperbarui pelanggan' : ''?></p>

        <div class="content-table">
            <div class="table-header">
                <div class="tab-nomer">No</div>
                <div class="tab-pelanggan">Pelanggan</div>
                <div class="tab-pembelian">Pembelian</div>
            </div>

            <?php $nomer = 0; while($data = $hasil->fetch_assoc()): $nomer++;
                $id = $data['penjualanID']; 

                $sqlGetDaftarProduk = "SELECT * FROM detailpenjualan WHERE penjualanID = $id";
                $rstDaftarProduk = mysqli_query($koneksi, $sqlGetDaftarProduk);

                $sqlGetDaftarPenjualan = "SELECT totalHarga FROM penjualan WHERE penjualanID = $id";
                $rstDaftarPenjualan = mysqli_query($koneksi, $sqlGetDaftarPenjualan);
                $dataTunggalDaftarPenjualan = $rstDaftarPenjualan->fetch_assoc();
            ?>
            <div class="overlay" id="overlay<?= $nomer ?>" onclick=closeDetail<?= $nomer ?>()></div>
            <div class="table-data">
                <div class="tab-nomer-data"><?= $nomer ?></div>
                <div class="tab-pelanggan-data"><div class="subdata"><h1><?= namaPelanggan($data['pelangganID']) ?></h1><p>#PG-<?= $data['pelangganID'] ?></p></div></div>
                <div class="tab-pembelian-data"><div class="subdata"><h1>Rp. <?= number_format($data['totalHarga']) ?></h1><p><?= (jumJenisProdukTerjual($data['penjualanID']) != 0 ) ? jumJenisProdukTerjual($data['penjualanID']) . ' Produk tercatat' : 'Struk kosong' ?></p></div><span class="detail" onclick=detailPelanggan<?= $nomer ?>()>detail</span></div>

                <div class="popup-detail-pelanggan idle" id="contentPopup<?= $nomer ?>">
                    <i class="fa-solid fa-receipt"></i>
                    <h4 style="margin: 5px 0 30px 0;">Riwayat pembelian</h4>

                    <div class="content-trx">
                        <div class="header">
                        <div class="brand"><i class="fa-solid fa-box-open-full logo"></i><div class="brand-det"><h1>Aplikasi Kasir v2.1</h1><p>#P-<?= $data['pelangganID']?> <?= namaPelanggan($data['pelangganID']) ?></p></div></div>                            
                        <div class="detail"><h1><?= $data['tanggalPenjualan'] ?></h1><p>#TRX-<?= $data['penjualanID'] ?></p></div>
                        </div>

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
                                <div class="tab-nomer-data"><?= $nomerTrx ?></div>
                                <div class="tab-produk-data"><?= namaProduk($dataProdukDaftarProduk['produkID']) ?></div>
                                <div class="tab-harga-data">Rp. <?= number_format(hargaBarang($dataProdukDaftarProduk['produkID'])) ?></div>
                                <div class="tab-qty-data"><?= $dataProdukDaftarProduk['jumlahProduk'] ?></div>
                                <div class="subtotal-data">Rp. <?= number_format($dataProdukDaftarProduk['subtotal']) ?></div>
                            </div>
                        <?php endwhile; ?>
                        </div>

                        <div class="tab-trx-footer">
                            <div class="tab-total">Total -</div>
                            <div class="tab-total-data">Rp. <?= $dataTunggalDaftarPenjualan['totalHarga'] ? number_format($dataTunggalDaftarPenjualan['totalHarga']) : "----" ?></div>
                        </div>
                    </div>

                    <div class="buttons">
                        <?php if($_SESSION['level'] != 'restocker'): ?>
                        <form action="../process/hapus-pelanggan.php" method="post"><button class="delete" name="hapus" value="<?= $data['pelangganID'] ?>">hapus pelanggan</button></form>
                        <?php endif; ?>
                        <button onclick=closeDetail<?= $nomer ?>()>tutup</button>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>

        <div class="content-buttons">
            <button onclick=addPenjualan()>tambah penjualan</button>
            <button onclick=home()>kembali</button>
        </div>
    </div>
</body>
<?php require '../_partials/footer.html'; ?>
<script>
    <?php $order = 0; while($order < mysqli_num_rows($hasil)): $order++;?>
    function detailPelanggan<?= $order ?>(){
        document.getElementById('contentPopup<?= $order ?>').classList.replace("idle","showDetail")
        document.getElementById('contentPopup<?= $order ?>').classList.replace("hideDetail","showDetail")
        document.getElementById('overlay<?= $order ?>').classList.add("showOverlay")
    }
    function closeDetail<?= $order ?>(){
        document.getElementById('contentPopup<?= $order ?>').classList.replace("showDetail", "hideDetail")
        document.getElementById('overlay<?= $order ?>').classList.remove("showOverlay")
    }
    <?php endwhile; ?>
</script>
</html>