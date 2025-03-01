<?php
session_start();
require 'koneksi.php';
require 'functions.php';

if (isset($_POST['query'])) {
    $search = mysqli_real_escape_string($koneksi, $_POST['query']);
    $sql = "SELECT * FROM produk WHERE namaProduk LIKE '%$search%' OR harga LIKE '%$search%' OR stok LIKE '%$search%' OR produkID LIKE '" . findProdukByID($search) . "' ORDER BY namaProduk ASC";
} else {
    $sql = "SELECT * FROM produk ORDER BY namaProduk ASC";
}

$hasil = mysqli_query($koneksi, $sql);
$nomer = 0;
while ($data = mysqli_fetch_assoc($hasil)) {
    $nomer++;
?>

<div class='overlay' id='overlay<?= $nomer ?>' onclick=closeProduk<?= $nomer ?>()></div>
<div class='table-data'>
    <div class='tab-nomer'><?= $nomer ?></div>
    <div class='tab-produk'><?= $data['namaProduk'] ?></div>
    <div class='tab-harga'>Rp. <?= number_format($data['harga']) ?></div>
    <div class='tab-stok data' style='color:<?= statusStok($data['stok']) ?>'><?= $data['stok'] ?></div>
    <div class='tab-detail' onclick='detailProduk<?= $nomer ?>()'>detail</div>

    <div class="popup-detail-pelanggan idle" id="contentPopup<?= $nomer ?>">
        <i class="fa-solid fa-boxes-packing"></i>
        <h4 style="margin: 5px 0 20px 0;">Detail Produk</h4>
        <div class="detail-data">
            <i class="fa-solid fa-tags"></i>
            <div class="information">
                <h6>Produk ID</h6>
                <p>#PDX-<?= formatIdProduk($data['produkID']) ?></p>
            </div>
        </div>
    
        <div class="detail-data">
            <i class="fa-solid fa-box"></i>
            <div class="information">
                <h6>Nama produk</h6>
                <p><?= $data['namaProduk'] ?></p>
            </div>
        </div>
    
        <div class="detail-data">
            <i class="fa-solid fa-badge-dollar"></i>
            <div class="information">
                <h6>Harga/pcs</h6>
                <p>Rp. <?= number_format($data['harga']) ?></p>
            </div>
        </div>
    
        <div class="detail-data">
            <i class="fa-solid fa-cubes"></i>
            <div class="information">
                <h6>Stok tersedia</h6>
                <p style="color: <?= statusStok($data['stok']) ?>;"><?= $data['stok'] ?></p>
            </div>
        </div>
    
        <div class="buttons">
            <?php if($_SESSION['level'] != 'kasir'): ?>
            <form action="../view/update-produk.php" method="post"><button class="edit" name="updateProduk" value="<?= $data['produkID'] ?>">edit produk</button></form>
            <?php endif; ?>                        
            <button onclick=closeProduk<?= $nomer ?>()>tutup</button>
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
    
