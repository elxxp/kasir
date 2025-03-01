<?php
function pageOnly($level){
    if($_SESSION['level'] != "$level"){
        header('location: login.php');

        setcookie('notAllowed', 'ok', time() + 1, "/");
        header('location: ../view/home.php');
        exit;
    }
}
function pageExcept($level){
    if($_SESSION['level'] == "$level"){
        header('location: login.php');  

        setcookie('notAllowed', 'ok', time() + 1, "/");
        header('location: ../view/home.php');
        exit;
    }
}

function formatIdAkun($number) {
    return str_pad($number, 3, '0', STR_PAD_LEFT);
}
function formatIdPelanggan($number) {
    return str_pad($number, 4, '0', STR_PAD_LEFT);
}
function formatIdProduk($number) {
    return str_pad($number, 5, '0', STR_PAD_LEFT);
}
function formatIdPenjualan($number) {
    return str_pad($number, 6, '0', STR_PAD_LEFT);
}
function findAkunByID($data) {
    if (preg_match('/^U-0*(\d{1,4})$/', $data, $matches)) {
        $filteredVal = $matches[1];
        
        if (strlen($data) === 5) { // "U-" 2 digit ditambah id digit (3)
            return $filteredVal;
        }
    }
    return null;
}
function findPelangganByID($data) {
    if (preg_match('/^PG-0*(\d{1,4})$/', $data, $matches)) {
        $filteredVal = $matches[1];
        
        if (strlen($data) === 7) { // "PG-" 3 digit ditambah id digit (4)
            return $filteredVal;
        }
    }
    return null;
}
function findProdukByID($data) {
    if (preg_match('/^PDX-0*(\d{1,5})$/', $data, $matches)) {
        $filteredVal = $matches[1];
        
        if (strlen($data) === 9) { // "PDX-" 4 digit ditambah id digit (5)
            return $filteredVal;
        }
    }
    return null;
}
function findPenjualanByID($data) {
    if (preg_match('/^TRX-0*(\d{1,6})$/', $data, $matches)) {
        $filteredVal = $matches[1];
        
        if (strlen($data) === 10) { // "TRX-" 4 digit ditambah id digit (6)
            return $filteredVal;
        }
    }
    return null;
}

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

function jumJenisProdukTerjual($idPenjualan){
    global $koneksi;
    $sqlGetJumJenProduk = "SELECT COUNT(DISTINCT produkID) AS produk FROM detailpenjualan WHERE penjualanID = $idPenjualan";
    $rstGetJumJenProduk = mysqli_query($koneksi, $sqlGetJumJenProduk);
    $dataJumJenProduk = $rstGetJumJenProduk->fetch_assoc();
    $jumJenProduk = $dataJumJenProduk['produk'];
    return $jumJenProduk;
}

function statusStok($value) {
    if ($value == 0) {
        return 'rgb(221, 0, 0)';
    } elseif ($value < 15) {
        return 'rgb(228, 149, 0)';
    } else {
        return 'rgb(0 201 0)';
    }
}
?>