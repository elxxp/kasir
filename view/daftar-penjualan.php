<?php
session_start();
require '../process/cek.php';
require '../process/koneksi.php';
require '../process/functions.php';

$sql = "SELECT * FROM penjualan ORDER BY tanggalPenjualan DESC";
$hasil = mysqli_query($koneksi, $sql);

if(isset($_COOKIE['addPenjualanDone'])){
    $notif = "<div class='show notif green' id='notif'><i class='fa-solid fa-circle-check icon'></i><p>struk berhasil tersimpan</p></div>";
}
if(isset($_COOKIE['statusRemove'])){
    $notif = "<div class='show notif green' id='notif'><i class='fa-solid fa-circle-check icon'></i><p>berhasil menghapus penjualan</p></div>";
}
if(isset($_COOKIE['statusRemoveFalse'])){
    $notif = "<div class='show notif red' id='notif'><i class='fa-solid fa-circle-xmark icon'></i><p>tidak dapat menghapus penjualan</p></div>";
}
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

        <div class="title" style="animation: contentIn 0.2s cubic-bezier(0.175, 0.885, 0.32, 1);">
            <i class="fa-regular fa-file-invoice-dollar page-icon"></i>
            <h3 class="title">Daftar penjualan</h3>
        </div>

        <div class="content-table" style="animation: contentIn 0.4s cubic-bezier(0.175, 0.885, 0.32, 1);">
            <div class="search-box">
                <input type="text" class="search" name="findData" autocomplete="off" placeholder='cari id penjualan (contoh, "TRX-00035")'>
            </div>

            <div class="table-header">
                <div class="tab-nomer">No</div>
                <div class="tab-pelanggan">Pelanggan</div>
                <div class="tab-pembelian">Pembelian</div>
            </div>

            <div class="box-table-data" style="height: <?= ($_SESSION['level'] != 'restocker') ? "129px" : "167px" ?>;">
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
                    <div class="tab-pelanggan-data"><div class="subdata"><h1><?= namaPelanggan($data['pelangganID']) ?></h1><p>#TRX-<?= formatIdPenjualan($data['penjualanID']) ?></p></div></div>
                    <div class="tab-pembelian-data"><div class="subdata"><h1>Rp. <?= number_format($data['totalHarga']) ?></h1><p><?= (jumJenisProdukTerjual($data['penjualanID']) != 0 ) ? jumJenisProdukTerjual($data['penjualanID']) . ' Produk tercatat' : 'Struk kosong' ?></p></div><span class="detail" onclick=detailPelanggan<?= $nomer ?>()>detail</span></div>
    
                    <div class="popup-detail-pelanggan idle" id="contentPopup<?= $nomer ?>">
                        <i class="fa-solid fa-receipt"></i>
                        <h4 style="margin: 5px 0 30px 0;">Riwayat pembelian</h4>
    
                        <div class="content-trx">
                            <div class="header">
                            <div class="brand"><i class="fa-solid fa-box-open-full logo"></i><div class="brand-det"><h1>Aplikasi Kasir</h1><p>#PG-<?= formatIdPelanggan($data['pelangganID']) ?> <?= namaPelanggan($data['pelangganID']) ?></p></div></div>                            
                            <div class="detail"><h1><?= $data['tanggalPenjualan'] ?></h1><p>#TRX-<?= formatIdPenjualan($data['penjualanID']) ?></p></div>
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
                                <div class="tab-total"></div>
                                <div class="tab-total-data">Rp. <?= $dataTunggalDaftarPenjualan['totalHarga'] ? number_format($dataTunggalDaftarPenjualan['totalHarga']) : "----" ?></div>
                            </div>
                        </div>
    
                        <div class="buttons">
                            <?php if($_SESSION['level'] != 'restocker' && mysqli_num_rows($rstDaftarProduk) == 0){ ?>
                            <form action="../process/hapus-penjualan.php" method="post"><button class="delete" name="hapus" value="<?= $data['penjualanID'] ?>">hapus penjualan</button></form>
                            <?php } else { ?>
                            <button class="cetak" onclick=print()>cetak penjualan</button>
                            <?php  } ?>
                            <button onclick=closeDetail<?= $nomer ?>()>tutup</button>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>

        <div class="content-buttons">
            <?php if($_SESSION['level'] != 'restocker'): ?>
            <button onclick=addPenjualan() style="animation: contentIn 0.6s cubic-bezier(0.175, 0.885, 0.32, 1);">tambah penjualan</button>
            <?php endif; ?>
            <button onclick=home() style="animation: contentIn 0.8s cubic-bezier(0.175, 0.885, 0.32, 1);">kembali</button>
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

    document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.querySelector(".search");
    const resultsContainer = document.querySelector(".box-table-data");

    searchInput.addEventListener("keyup", function() {
        let query = searchInput.value;
        
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "../process/find-penjualan.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                resultsContainer.innerHTML = xhr.responseText;
            }
        };
        xhr.send("query=" + encodeURIComponent(query));
        });
    });
</script>
</html>