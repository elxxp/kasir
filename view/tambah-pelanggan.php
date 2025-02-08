<?php
session_start();
require '../process/cek.php';

if(isset($_POST['tambahPel'])){
    $nama = $_POST['namaPel'];
    $alamat = $_POST['alamat'];
    $telp = $_POST['telp'];
    
    if($nama != '' && $alamat != '' && $telp != ''){
        require '../process/koneksi.php';

        $sql = "INSERT INTO pelanggan (namaPel, alamat, telp) VALUES ('$nama', '$alamat', '$telp')";
        mysqli_query($koneksi, $sql);

        header('location: daftar-pelanggan.php');
    } else {
        echo 'data tidak lengkap';
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
    <h3>Tambah pelanggan</h3>
    <form method="post">
        <span>Nama : </span><input type="text" placeholder="Nama pelanggan" name="namaPel" autocomplete="off"><br><br>
        <span>Alamat : </span><input type="text" placeholder="Alamat pelanggan" name="alamat" autocomplete="off"><br><br>
        <span>No. Telepon : </span><input type="number" placeholder="Nomor telepon pelanggan" name="telp" autocomplete="off"><br><br>
        <button name="tambahPel">tambah</button>
    </form>
    <a href="daftar-pelanggan.php">kembali</a>
</body>
</html>