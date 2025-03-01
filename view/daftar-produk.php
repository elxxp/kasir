<?php
session_start();
require '../process/cek.php';
require '../process/koneksi.php';
require '../process/functions.php';

$sql = "SELECT * FROM produk ORDER BY namaProduk ASC";
$hasil = mysqli_query($koneksi, $sql);

if(isset($_POST['updateProduk'])){
    $id = $_POST['id'];
    $nama = $_POST['updNamaProduk'];
    $harga = $_POST['updHargaProduk'];
    $stok = $_POST['updStokProduk'];

    $sql = "UPDATE produk SET namaProduk='$nama', harga='$harga', stok='$stok' WHERE produkID='$id'";
    $cek = mysqli_query($koneksi, $sql);

    if($cek){
        setcookie('statusUpdate', 'ok', time() + 1);
        header('refresh: 0');
    } 
    
}

if(isset($_COOKIE['statusAdd'])){
    $notif = "<div class='show notif green' id='notif'><i class='fa-solid fa-circle-check icon'></i><p>berhasil menambahkan produk</p></div>";
}
if(isset($_COOKIE['statusRemove'])){
    $notif = "<div class='show notif green' id='notif'><i class='fa-solid fa-circle-check icon'></i><p>berhasil menghapus produk</p></div>";
}
if(isset($_COOKIE['statusUpdError'])){
    $notif = "<div class='show notif yellow' id='notif'><i class='fa-solid fa-circle-exclamation icon'></i><p>pilih produk terlebih dahulu</p></div>";
}
if(isset($_COOKIE['statusUpdSuccess'])){
    $notif = "<div class='show notif green' id='notif'><i class='fa-solid fa-circle-check icon'></i><p>perubahan produk berhasil disimpan</p></div>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php require '../_partials/head.html'; ?>
    <link rel="stylesheet" href="../lib/css/styleDafProduk.css">
</head>
<body>
    <?= @$notif ?>
    <img src="../lib/images/back.jpg">
    <div class="container">
        <?php require '../_partials/header.php'; ?>

        <div class="title" style="animation: contentIn 0.2s cubic-bezier(0.175, 0.885, 0.32, 1);">
            <i class="fa-regular fa-boxes-stacked page-icon"></i>
            <h3 class="title">Daftar produk</h3>
        </div>
        
        <div class="content-table" style="animation: contentIn 0.4s cubic-bezier(0.175, 0.885, 0.32, 1);">
            <div class="search-box">
                <input type="text" class="search" name="findData" autocomplete="off" placeholder="cari produk">
            </div>

            <div class="table-header">
                <div class="tab-nomer">No</div>
                <div class="tab-produk">Produk</div>
                <div class="tab-harga">Harga</div>
                <div class="tab-stok">Stok</div>
            </div>

            <div class="box-table-data" style="height: <?= ($_SESSION['level'] != 'admin') ? "170px" : "130px" ?>;">
                <?php $nomer = 0; while($data = $hasil->fetch_assoc()):  $nomer++; ?>
                <div class="overlay" id="overlay<?= $nomer ?>" onclick=closeProduk<?= $nomer ?>()></div>
                <div class="table-data">
                    <div class="tab-nomer"><?= $nomer ?></div>
                    <div class="tab-produk"><?= $data['namaProduk'] ?></div>
                    <div class="tab-harga">Rp. <?= number_format($data['harga']) ?></div>
                    <div class="tab-stok data" style="color: <?= statusStok($data['stok']) ?>;"><?= $data['stok'] ?></div>
                    <div class="tab-detail" onclick="detailProduk<?= $nomer ?>()">detail</div>

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
                <?php endwhile; ?>
            </div>
        </div>
        
        <div class="content-buttons">
            <?php if($_SESSION['level'] == 'admin'): ?>
            <button name="hapus" onclick=addProduk() style="animation: contentIn 0.6s cubic-bezier(0.175, 0.885, 0.32, 1);">tambah produk</button>
            <?php endif; ?>
            <button onclick=home() style="animation: contentIn 0.8s cubic-bezier(0.175, 0.885, 0.32, 1);">kembali</button>
        </div>        
    </div>
</body>
<?php require '../_partials/footer.html'; ?>
<script>
    <?php $order = 0; while($order < mysqli_num_rows($hasil)): $order++;?>
    function detailProduk<?= $order ?>(){
        document.getElementById('contentPopup<?= $order ?>').classList.replace("idle","showDetail")
        document.getElementById('contentPopup<?= $order ?>').classList.replace("hideDetail","showDetail")
        document.getElementById('overlay<?= $order ?>').classList.add("showOverlay")
    }
    function closeProduk<?= $order ?>(){
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
        xhr.open("POST", "../process/find-produk.php", true);
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
