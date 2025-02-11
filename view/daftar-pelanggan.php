<?php
session_start();
require '../process/cek.php';
require '../process/koneksi.php';

$sql = "SELECT * FROM pelanggan";
$hasil = mysqli_query($koneksi, $sql);

if(isset($_POST['updatePelanggan'])){
    $id = $_POST['id'];
    $nama = $_POST['updNamaPelanggan'];
    $alamat = $_POST['updAlamatPelanggan'];
    $telp = $_POST['updTelpPelanggan'];

    $sql = "UPDATE pelanggan SET namaPel='$nama', alamat='$alamat', telp='$telp' WHERE pelangganID='$id'";
    $cek = mysqli_query($koneksi, $sql);

    if($cek){
        setcookie('statusUpdate', 'ok', time() + 1);
        header('refresh: 0');
    } 
}

if(isset($_COOKIE['statusRemove'])){
    $notif = "<div class='show notif green' id='notif'><i class='fa-solid fa-circle-check icon'></i><p>berhasil menghapus pelanggan</p></div>";
}
if(isset($_COOKIE['statusAdd'])){
    $notif = "<div class='show notif green' id='notif'><i class='fa-solid fa-circle-check icon'></i><p>berhasil menambahkan pelanggan</p></div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php require '../_partials/head.html'; ?>
    <link rel="stylesheet" href="../lib/css/styleDafPelanggan.css">
</head>
<body>
    <?= @$notif ?>
    <img src="../lib/images/back.jpg">
     <div class="container">
        <?php require '../_partials/header.php'; ?>

        <div class="title">
            <i class="fa-regular fa-address-book page-icon"></i>
            <h3 class="title">Daftar pelanggan</h3>
        </div>
        <p><?= @$_COOKIE['statusUpdate'] ? 'Berhasil memperbarui pelanggan' : ''?></p>

        <div class="content-table">
            <div class="table-header">
                <div class="tab-nomer">No</div>
                <div class="tab-nama">Nama</div>
            </div>

            <?php $nomer = 0; while($data = $hasil->fetch_assoc()): $nomer++;?>
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
                            <p>#PG-<?= $data['pelangganID'] ?></p>
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
            <?php endwhile; ?>
        </div>
     
        <div class="content-buttons">
            <?php if($_SESSION['level'] != 'restocker'): ?>
            <button onclick=addPelanggan()>tambah pelanggan</button>
            <?php endif; ?>
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