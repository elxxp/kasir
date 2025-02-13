<?php
session_start();
require '../process/cek.php';

if(isset($_POST['tambahPel'])){
    $nama = $_POST['namaPel'];
    $alamat = $_POST['alamat'];
    $telp = $_POST['telp'];
    
    if($nama != '' && $alamat != '' && $telp != ''){
        if(strlen($telp) >= 12 && strlen($telp) < 16){
            require '../process/koneksi.php';
    
            $sql = "INSERT INTO pelanggan (namaPel, alamat, telp) VALUES ('$nama', '$alamat', '$telp')";
            mysqli_query($koneksi, $sql);
    
            setcookie('statusAdd', 'ok', time() + 1, "/");
            header('location: daftar-pelanggan.php');
        } else {
            $notif = "<div class='show notif yellow' id='notif'><i class='fa-solid fa-circle-exclamation icon'></i><p>masukan nomor telepon yang valid</p></div>";
        }
    } else {
        $notif = "<div class='show notif red' id='notif'><i class='fa-solid fa-circle-xmark icon'></i><p>data tidak lengkap</p></div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php require '../_partials/head.html'; ?>
    <link rel="stylesheet" href="../lib/css/styleAddPelanggan.css">
</head>
<body>
    <?= @$notif ?>
    <img src="../lib/images/back.jpg">
    <div class="container">
        <?php require '../_partials/header.php'; ?>

        <div class="title">
            <h3 class="title">Tambah pelanggan</h3>
        </div>
        
        <form method="post">
            <div class="input"><input type="text" placeholder="Nama pelanggan" name="namaPel" autocomplete="off" value="<?= @$nama ?>"><i class="fa-solid fa-face-laugh-wink"></i></div>
            <div class="input"><input type="text" placeholder="Alamat pelanggan" name="alamat" autocomplete="off" value="<?= @$alamat ?>"><i class="fa-solid fa-location-dot"></i></div>
            <div class="input"><input type="number" placeholder="Nomor telepon pelanggan" name="telp" autocomplete="off" value="<?= @$telp ?>"><i class="fa-solid fa-phone"></i></div>
            <button class="inForm" name="tambahPel">tambah</button>
        </form>
        <div class="content-buttons">
            <button onclick=dafPelanggan()>kembali</button>
        </div>
    </div>
</body>
<?php require '../_partials/footer.html'; ?>
</html>