<?php
session_start();
require '../process/cek.php';
require '../process/koneksi.php';

if(isset($_POST['update'])){
    $id = $_POST['id'];
    $nama = $_POST['namaNew'];
    $alamat = $_POST['alamatNew'];
    $telp = $_POST['telpNew'];

    $sql = "UPDATE pelanggan SET namaPel='$nama', alamat='$alamat', telp='$telp' WHERE pelangganID='$id'";
    $cek = mysqli_query($koneksi, $sql);
    
    echo "Berhasil memperbarui informasi pelanggan, redirecting back...";

    echo 
        "<script>
            window.setTimeout(function() {
                window.location = 'daftar-pelanggan.php';
            }, 1000);
        </script>";
}

if(isset($_POST['updatePelanggan'])):
    $idPelanggan = $_POST['id'];
    $namaPelanggan = $_POST['updNamaPelanggan'];
    $alamatPelanggan = $_POST['updAlamatPelanggan'];
    $telpPelanggan = $_POST['updTelpPelanggan'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h3>Update pelanggan</h3>
    <form method="post">
        <span>ID : #<?= $idPelanggan ?></span><input type="hidden" name="id" value="<?= $idPelanggan ?>"><br><br>
        <span>Nama : </span><input type="text" placeholder="Nama pelanggan" name="namaNew" value="<?= $namaPelanggan ?>" autocomplete="off"><br><br>
        <span>Alamat : </span><input type="text" placeholder="Alamat pelanggan" name="alamatNew" value="<?= $alamatPelanggan ?>" autocomplete="off"><br><br>
        <span>No. Telepon : </span><input type="number" placeholder="Nomor telepon pelanggan" name="telpNew" value="<?= $telpPelanggan ?>" autocomplete="off"><br><br>
        <button name="update">update</button>
    </form>
    <a href="daftar-pelanggan.php">kembali</a>
</body>
</html>

<?php endif; ?>