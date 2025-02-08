<?php
session_start();
require '../process/koneksi.php';
require '../process/cek.php';

$sqlPelanggan = "SELECT * FROM pelanggan;";
$rstPelanggan = mysqli_query($koneksi, $sqlPelanggan);

$sqlProduk = "SELECT * FROM produk;";
$rstProduk = mysqli_query($koneksi, $sqlProduk);

if(isset($_POST['tambahPenjualan'])){
    $idPelanggan = $_POST['pelanggan'];
    $idProduk = $_POST['produk'];
    $qty = $_POST['qty'];

    if($idPelanggan != 'null' && $idProduk != 'null' && $qty != ''){
        if($qty > 0){
            $sqlSelcProduk = "SELECT * FROM produk WHERE produkID='$idProduk'";
            $dataSelc = mysqli_query($koneksi, $sqlSelcProduk);

            while($data = $dataSelc->fetch_assoc()){
                $total = $data['harga'] * $qty;

                // ngambil id penjual trus di masukin ke detail penjualan
                $sqlSelcPenjualan = "SELECT penjualanID FROM penjualan ORDER BY tanggalPenjualan DESC LIMIT 1;";
                $rstPenjualan = mysqli_query($koneksi, $sqlSelcPenjualan);
                $dataPenjualan = $rstPenjualan->fetch_assoc();

                // nambah ke detail penjualan
                $idPenjualan =  $dataPenjualan['penjualanID'] + 1;

                // ngurangi stok
                $sqlSelcStok = "SELECT stok FROM produk WHERE produkID = $idProduk";
                $rstStok = mysqli_query($koneksi, $sqlSelcStok);
                $dataStok = $rstStok->fetch_assoc();

                $stokSaatIni = $dataStok['stok'];
                $stok = ($stokSaatIni - $qty);

                if($stok >= 0 ){
                    // nambah ke penjualan
                    $sqlAddPenjualan = "INSERT INTO penjualan (totalHarga, pelangganID) VALUES ('$total', '$idPelanggan')";
                    mysqli_query($koneksi, $sqlAddPenjualan);

                    // nambah ke detal penjualan    
                    $sqlAddDetPenjualan = "INSERT INTO detailpenjualan (penjualanID, produkID, jumlahProduk) VALUES ('$idPenjualan', '$idProduk', '$qty')";
                    mysqli_query($koneksi, $sqlAddDetPenjualan);

                    // stok
                    $sqlUpdStok = "UPDATE produk SET stok=$stok WHERE produkID = $idProduk";
                    mysqli_query($koneksi, $sqlUpdStok);

                    header("location: daftar-penjualan.php");
                } else {
                    echo "stok  tidak mencukupi";
                }
            }
        } else {
            echo "masukan quantity yang valid!";
        }
    } else {
        echo "data tidak lengkap";
    }

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h3>Tambah penjualan</h3>
    <form method="post">
        <span>Pelanggan : </span>
            <select name="pelanggan">
                <option value="null">-- pilih pelanggan --</option>
                <?php while($data = $rstPelanggan->fetch_assoc()): ?>
                <option value="<?= $data['pelangganID'] ?>"><?= $data['namaPel'] ?></option>
                <?php endwhile; ?>
            </select><br><br>
            <span>Produk : </span>
            <select name="produk">
                <option value="null">-- pilih produk --</option>
                <?php while($data = $rstProduk->fetch_assoc()): ?>
                <option value="<?= $data['produkID'] ?>"><?= $data['namaProduk'] ?> - <?= $data['harga'] ?> (<?= $data['stok'] ?>)</option>
                <?php endwhile; ?>
            </select><br><br>
        <span>QTY : </span><input type="number" placeholder="Jumlah produk" name="qty" autocomplete="off"><br><br>
        <button name="tambahPenjualan">tambah</button>
    </form>
    <a href="daftar-penjualan.php">kembali</a>
</body>
</html>