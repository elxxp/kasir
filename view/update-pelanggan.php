<?php
session_start();
require '../process/cek.php';
require '../process/koneksi.php';
require '../process/functions.php';
pageExcept('restocker');

if(isset($_POST['updatePelanggan']) || isset($_POST['deleteAkun'])){
    $idPelanggan = $_POST['updatePelanggan'];

    // if(isset($_POST['deleteAkun'])){
    //     $idAkun = $_POST['idAkun'];

    //     $sqlRemoveAkun = "DELETE FROM user WHERE id = $idAkun";
    //     mysqli_query($koneksi, $sqlRemoveAkun);

    //     setcookie('statusRemove', 'ok', time() + 1, "/");
    //     header('location: ../view/daftar-akun.php');
    //     exit;
    // }
    
    if(isset($_POST['idPelanggan'])){
        $idPelanggan = $_POST['idPelanggan'];
        @$pelanggan = $_POST['updPelanggan'];
        @$alamat = $_POST['updAlamat'];
        @$telp = $_POST['updTelp'];
    
        if($pelanggan != '' && $alamat != '' && $telp != ''){
            $sqlUpdAkun = "UPDATE pelanggan SET namaPel = '$pelanggan', alamat = '$alamat', telp = '$telp' WHERE pelangganID = $idPelanggan";
            mysqli_query($koneksi, $sqlUpdAkun);

            setcookie('statusUpdSuccess', 'ok', time() + 1, "/");
            header('location: ../view/daftar-pelanggan.php');
        } else {
            $notif = "<div class='show notif red' id='notif'><i class='fa-solid fa-circle-xmark icon'></i><p>data tidak lengkap</p></div>";
        }
    }

    $sqlGetPelanggan = "SELECT * FROM pelanggan WHERE pelangganID = $idPelanggan";
    $rstPelanggan = mysqli_query($koneksi, $sqlGetPelanggan);
    $dataPelanggan = $rstPelanggan->fetch_assoc();

} else {
    setcookie('statusUpdError', 'ok', time() + 1, "/");
    header('location: daftar-pelanggan.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php require '../_partials/head.html'; ?>
    <link rel="stylesheet" href="../lib/css/styleUpdPelanggan.css"> 
</head>
<body>
    <?= @$notif ?>
    <img src="../lib/images/back.jpg">
    <div class="container">
        <?php require '../_partials/header.php'; ?>
        <?= confirmDialog('hapus-pelanggan.php', 'Data penjualan yang terkait pada pelanggan ini akan ikut terhapus dan <strong>tidak dapat dikembalikan.</strong>', 'hapusPelanggan', $idPelanggan, '100px') ?>
        
        <div class="title">
            <h3 class="title">Edit pelanggan</h3>
        </div>
        
        <form method="post">            
            <div class="input"><input type="text" placeholder="Nama pelanggan" name="updPelanggan" autocomplete="off" value="<?= $dataPelanggan['namaPel'] ?>"><i class="fa-solid fa-face-laugh-wink"></i></div>
            <div class="input"><input type="text" placeholder="Alamat" name="updAlamat" autocomplete="off" value="<?= $dataPelanggan['alamat'] ?>"><i class="fa-solid fa-location-dot"></i></div>
            <div class="input"><input type="text" placeholder="Nomor telepon" name="updTelp" autocomplete="off" value="<?= $dataPelanggan['telp'] ?>"><i class="fa-solid fa-phone"></i></div>
            <input type="hidden" name="idPelanggan" value="<?= $dataPelanggan['pelangganID'] ?>">
            <button class="inForm stack top update" name="updatePelanggan">update</button>
        </form>
        
        <div class="content-buttons">
            <button class="delete" name="deleteAkun" onclick=openDialog()>delete</button>
            <button onclick=dafPelanggan()>kembali</button>
        </div>
    </div>
</body>
<?php require '../_partials/footer.html'; ?>
</html>