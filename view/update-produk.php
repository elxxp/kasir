<?php
session_start();
require '../process/cek.php';
require '../process/koneksi.php';

if(isset($_POST['updateProduk']) || isset($_POST['deleteProduk'])){
    $idProduk = $_POST['updateProduk'];

    if(isset($_POST['deleteProduk'])){
        $idProduk = $_POST['idProduk'];

        $sqlRemoveProduk = "DELETE FROM produk WHERE produkID = $idProduk";
        mysqli_query($koneksi, $sqlRemoveProduk);

        setcookie('statusRemove', 'ok', time() + 1, "/");
        header('location: ../view/daftar-produk.php');
    }
    
    if(isset($_POST['idProduk'])){
        $idProduk = $_POST['idProduk'];
        @$produk = $_POST['updProduk'];
        @$harga = $_POST['updHarga'];
        @$stok = $_POST['updStok'];
    
        if($produk != '' && $harga != '' && $stok != ''){
            if($harga > 0){
                if($stok >= 0){
                    if($_SESSION['level'] == 'admin'){
                        $sqlUpdProduk = "UPDATE produk SET namaProduk = '$produk', harga = '$harga', stok = '$stok' WHERE produkID = $idProduk";
                        mysqli_query($koneksi, $sqlUpdProduk);

                        setcookie('statusUpdSuccess', 'ok', time() + 1, "/");
                        header('location: ../view/daftar-produk.php');
                    }
                    if($_SESSION['level'] == 'restocker'){                        
                        $sqlUpdProduk = "UPDATE produk SET stok = '$stok' WHERE produkID = $idProduk";
                        mysqli_query($koneksi, $sqlUpdProduk);
    
                        setcookie('statusUpdSuccess', 'ok', time() + 1, "/");
                        header('location: ../view/daftar-produk.php');
                    }
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

    $sqlGetProduk = "SELECT * FROM produk WHERE produkID = $idProduk";
    $rstProduk = mysqli_query($koneksi, $sqlGetProduk);
    $produk = $rstProduk->fetch_assoc();

    function namaProduk($id){
        global $koneksi;
        $sqlGetNamaProduk = "SELECT namaProduk FROM produk WHERE produkID = $id";
        $rstGetNamaProduk = mysqli_query($koneksi, $sqlGetNamaProduk);
        $dataProdukGetNamaProduk = $rstGetNamaProduk->fetch_assoc();
        $produk = $dataProdukGetNamaProduk['namaProduk'];
        return $produk;
    }
    function hargaBarang($id){
        global $koneksi;
        $sqlGetHargaProduk = "SELECT harga FROM produk WHERE produkID = $id";
        $rstGetHargaProduk = mysqli_query($koneksi, $sqlGetHargaProduk);
        $dataProdukGetHargaProduk = $rstGetHargaProduk->fetch_assoc();
        $hargaProduk = $dataProdukGetHargaProduk['harga'];
        return $hargaProduk;
    }
} else {
    setcookie('statusUpdError', 'ok', time() + 1, "/");
    header('location: daftar-produk.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php require '../_partials/head.html'; ?>
    <link rel="stylesheet" href="../lib/css/styleUpdProduk.css"> 
</head>
<body>
    <?= @$notif ?>
    <img src="../lib/images/back.jpg">
    <div class="container">
        <?php require '../_partials/header.php'; ?>

        <div class="title">
            <h3 class="title">Edit produk</h3>
        </div>

        <form method="post">
            <?php if($_SESSION['level'] == 'admin'): ?>
            <div class="input"><input type="text" placeholder="Nama produk" name="updProduk" autocomplete="off" value="<?= namaProduk($idProduk) ?>"><i class="fa-solid fa-box"></i></div>
            <div class="input harga"><span>Rp.</span><input type="number" placeholder="0" name="updHarga" autocomplete="off" value="<?= hargaBarang($idProduk) ?>"><i class="fa-solid fa-badge-dollar"></i></div>
            <?php endif; ?>
            
            <?php if($_SESSION['level'] == 'restocker'): ?>
            <div class="input"><input type="text" value="<?= namaProduk($idProduk) ?>" disabled><i class="fa-solid fa-box"></i></div>
            <div class="input harga"><span>Rp.</span><input type="number" value="<?= hargaBarang($idProduk) ?>" disabled><i class="fa-solid fa-badge-dollar"></i></div>
            <input type="hidden" name="updProduk" value="<?= namaProduk($idProduk) ?>">
            <input type="hidden" name="updHarga" value="<?= hargaBarang($idProduk) ?>">
            <?php endif; ?>

            <div class="input"><input type="number" placeholder="Stok produk" name="updStok" autocomplete="off" value="<?= $produk['stok'] ?>"><i class="fa-solid fa-cubes"></i></div>
            <input type="hidden" name="idProduk" value="<?= $idProduk ?>">
            <button class="inForm stack top update" name="updateProduk">update</button>
            <?php if($_SESSION['level'] == 'admin'): ?>
            <button class="inForm stack delete" name="deleteProduk">delete</button>
            <?php endif; ?>
        </form>
    
        <div class="content-buttons">
            <button onclick=dafProduk()>kembali</button>
        </div>
    </div>
</body>
<?php require '../_partials/footer.html'; ?>
</html>