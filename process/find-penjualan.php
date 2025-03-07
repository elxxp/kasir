<?php
session_start();
require 'koneksi.php';
require 'functions.php';

if (isset($_POST['query']) && !empty($_POST['query'])) {
    $search = mysqli_real_escape_string($koneksi, $_POST['query']);
    $sql = "SELECT * FROM penjualan WHERE penjualanID LIKE '" . findPenjualanByID($search). "' ORDER BY tanggalPenjualan DESC";
} else {
    $sql = "SELECT * FROM penjualan ORDER BY tanggalPenjualan DESC";
}

$hasil = mysqli_query($koneksi, $sql);
$nomer = 0;
while ($data = mysqli_fetch_assoc($hasil)) {
    $nomer++;

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
            <div class="brand"><i class="fa-solid fa-box-open-full logo"></i><div class="brand-det"><h1>Aplikasi Kasir v2.1</h1><p>#PG-<?= formatIdPelanggan($data['pelangganID']) ?> <?= namaPelanggan($data['pelangganID']) ?></p></div></div>                            
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
            <form action="../process/print.php" method="post">
                <input type="hidden" name="trx" value="<?= $id ?>">
                <input type="hidden" name="cust" value="<?= $data['pelangganID'] ?>">
                <button class="cetak">cetak penjualan</button>
            </form>
            <?php  } ?>
            <button onclick=closeDetail<?= $nomer ?>()>tutup</button>
        </div>
    </div>
</div>

<?php
}

if(mysqli_num_rows($hasil) == 0){
?>
<div class="data-not-found">
    <i class="fa-regular fa-circle-question"></i>
    <p>data tidak tersedia</p>
</div>
<?php
}
