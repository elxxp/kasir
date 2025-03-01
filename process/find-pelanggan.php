<?php
session_start();
require 'koneksi.php';
require 'functions.php';

if (isset($_POST['query'])) {
    $search = mysqli_real_escape_string($koneksi, $_POST['query']);
    $sql = "SELECT * FROM pelanggan WHERE namaPel LIKE '%$search%' OR alamat LIKE '%$search%' OR telp LIKE '%$search%' OR pelangganID LIKE '" . findPelangganByID($search) . "' ORDER BY namaPel ASC";
} else {
    $sql = "SELECT * FROM pelanggan ORDER BY namaPel ASC";
}

$hasil = mysqli_query($koneksi, $sql);
$nomer = 0;
while ($data = mysqli_fetch_assoc($hasil)) {
    $nomer++;
?>

<div class="overlay" id="overlay<?= $nomer ?>" onclick=closeDetail<?= $nomer ?>()></div>
<div class="table-data">
    <div class="tab-nomer-data"><?= $nomer ?></div>
    <div class="tab-nama-data"><div class="subdata"><h1><?= $data['namaPel'] ?></h1><p><?= $data['alamat'] ?></p></div><span class="detail" onclick=detailPelanggan<?= $nomer ?>()>detail</span></div>

    <div class="popup-detail-pelanggan idle" id="contentPopup<?= $nomer ?>">
        <i class="fa-solid fa-user-vneck"></i>
        <h4 style="margin: 5px 0 20px 0;">Detail Pelanggan</h4>
        <div class="detail-data">
            <i class="fa-solid fa-image-polaroid-user"></i>
            <div class="information">
                <h6>Customor ID</h6>
                <p>#PG-<?= formatIdPelanggan($data['pelangganID']) ?></p>
            </div>
        </div>
        
        <div class="detail-data">
            <i class="fa-solid fa-face-laugh-wink"></i>
            <div class="information">
                <h6>Nama lengkap</h6>
                <p><?= $data['namaPel'] ?></p>
            </div>
        </div>

        <div class="detail-data">
            <i class="fa-solid fa-location-dot"></i>
            <div class="information">
                <h6>Alamat</h6>
                <p><?= $data['alamat'] ?></p>
            </div>
        </div>

        <div class="detail-data">
            <i class="fa-solid fa-phone"></i>
            <div class="information">
                <h6>Nomor telepon</h6>
                <p><?= $data['telp'] ?></p>
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
    
