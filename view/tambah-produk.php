<?php
session_start();
require '../process/cek.php';

if(isset($_POST['tambahProduk'])){
    $nama = $_POST['namaProduk'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    
    if($nama != '' && $harga != '' && $stok != ''){
        if($harga > 0){
            if($stok > 0){
                require '../process/koneksi.php';
        
                $sql = "INSERT INTO produk (namaProduk, harga, stok) VALUES ('$nama', '$harga', '$stok')";
                mysqli_query($koneksi, $sql);
                
                setcookie('statusAdd', 'ok', time() + 1, "/");
                header('location: daftar-produk.php');
            } else {
                $notif = "<div class='show notif yellow' id='notif'><i class='fa-solid fa-circle-exclamation icon'></i><p>masukan stok produk yang valid</p></div>";
            }
        } else {
            $notif = "<div class='show notif yellow' id='notif'><i class='fa-solid fa-circle-exclamation icon'></i><p>masukan harga produk yang valid</p></div>";
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
    <link rel="stylesheet" href="../lib/css/styleAddProduk.css">
</head>
<body>
    <?= @$notif ?>
    <img src="../lib/images/back.jpg">
    <div class="container">
        <?php require '../_partials/header.php'; ?>

        <div class="title">
            <h3 class="title">Tambah produk</h3>
        </div>

        <form method="post">
            <div class="input"><input type="text" placeholder="Nama produk" name="namaProduk" autocomplete="off" value="<?= @$nama ?>"><i class="fa-solid fa-box"></i></div>
            <div class="input harga"><span>Rp.</span><input type="number" placeholder="0" name="harga" autocomplete="off" value="<?= @$harga ?>"><i class="fa-solid fa-badge-dollar"></i></div>
            <div class="input"><input type="number" placeholder="Stok produk" name="stok" autocomplete="off" value="<?= @$stok ?>"><i class="fa-solid fa-cubes"></i></div>
            <button class="inForm" name="tambahProduk">tambah</button>
        </form>
        
        <div class="content-buttons">
            <button onclick=dafProduk()>kembali</button>
        </div>
    </div>
</body>
<?php require '../_partials/footer.html'; ?>
</html>