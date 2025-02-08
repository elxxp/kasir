<?php
session_start();
require '../process/cek.php';
require '../process/koneksi.php';

function namaProduk($id){
    global $koneksi;
    $sqlGetNamaProduk = "SELECT namaProduk FROM produk WHERE produkID = $id";
    $rstGetNamaProduk = mysqli_query($koneksi, $sqlGetNamaProduk);
    $dataProdukGetNamaProduk = $rstGetNamaProduk->fetch_assoc();
    $produk = $dataProdukGetNamaProduk['namaProduk'];
    return $produk;
}

function namaPelanggan($id){
    global $koneksi;
    $sqlGetNamaPelanggan = "SELECT namaPel FROM pelanggan WHERE pelangganID = $id";
    $rstGetNamaPelanggan = mysqli_query($koneksi, $sqlGetNamaPelanggan);
    $dataProdukGetNamaPelanggan = $rstGetNamaPelanggan->fetch_assoc();
    $pelanggan = $dataProdukGetNamaPelanggan['namaPel'];
    return $pelanggan;
}

function hargaBarang($id){
    global $koneksi;
    $sqlGetHargaProduk = "SELECT harga FROM produk WHERE produkID = $id";
    $rstGetHargaProduk = mysqli_query($koneksi, $sqlGetHargaProduk);
    $dataProdukGetHargaProduk = $rstGetHargaProduk->fetch_assoc();
    $hargaProduk = $dataProdukGetHargaProduk['harga'];
    return $hargaProduk;
}

if(isset($_POST['struk'])){
    $id = $_POST['penjualanID'];
}

$sqlGetDaftarProduk = "SELECT * FROM detailpenjualan WHERE penjualanID = $id";
$rstDaftarProduk = mysqli_query($koneksi, $sqlGetDaftarProduk);

$sqlGetDaftarPenjualan = "SELECT totalHarga FROM penjualan WHERE penjualanID = $id";
$rstDaftarPenjualan = mysqli_query($koneksi, $sqlGetDaftarPenjualan);
$dataTunggalDaftarPenjualan = $rstDaftarPenjualan->fetch_assoc();
?>

<table border=1>
    <tr>
        <th>No</th>
        <th>Produk</th>
        <th>Harga</th>
        <th>QTY</th>
        <th>Subtotal</th>
    </tr>
    
    <?php if(mysqli_num_rows($rstDaftarProduk) != 0){ ?>
        <?php $nomer = 0; while($dataProdukDaftarProduk = $rstDaftarProduk->fetch_assoc()): $nomer++; ?>

            <tr>
                <td><?= $nomer ?></td>
                <td><?= namaProduk($dataProdukDaftarProduk['produkID']) ?></td>
                <td>Rp. <?= number_format(hargaBarang($dataProdukDaftarProduk['produkID'])) ?></td>
                <td><?= $dataProdukDaftarProduk['jumlahProduk'] ?></td>
                <td>Rp. <?= number_format($dataProdukDaftarProduk['subtotal']) ?></td>
            </tr>
            
        <?php endwhile; ?>
        <?php } else { ?>
            <tr>
                <td colspan=5><h6>struk kosong</h6></td>
            </tr>
    <?php } ?>

    <tr>
        <td colspan=4>Total pembelian</td>
        <td>Rp. <?= $dataTunggalDaftarPenjualan['totalHarga'] ? number_format($dataTunggalDaftarPenjualan['totalHarga']) : "----" ?></td>
    </tr>
</table>
<a href="daftar-penjualan.php">kembali</a> 