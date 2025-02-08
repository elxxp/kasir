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

if(isset($_POST['removePelanggan'])){
    $id = $_POST['id'];

    $sql = "DELETE FROM pelanggan WHERE pelangganID=$id";
    $cek = mysqli_query($koneksi, $sql);

    if($cek){
        setcookie('statusRemove', 'ok', time() + 1);
        header('refresh: 0');
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
    <h3>Daftar pelanggan</h3>
    <p><?= @$_COOKIE['statusUpdate'] ? 'Berhasil memperbarui pelanggan' : ''?></p>
    <p><?= @$_COOKIE['statusRemove'] ? 'Berhasil menghapus pelanggan' : ''?></p>
    <table border=1>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Alamat</th>
            <th>No. telepon</th>
            <th colspan="2">Aksi</th>
        </tr>

        <?php
        if(mysqli_num_rows($hasil) == 0): ?>
            <td colspan=5>tidak ada data</td>
        <?php endif; ?>

        <?php $nomer = 0; while($data = $hasil->fetch_assoc()): $nomer++;?>
            <tr>
                <td><?= $nomer ?></td>
                <td><?= $data['namaPel'] ?></td>
                <td><?= $data['alamat'] ?></td>
                <td><?= $data['telp'] ?></td>
                <form action="update-pelanggan.php" method="post">
                    <input type="hidden" name="id" value="<?= $data['pelangganID'] ?>">
                    <input type="hidden" name="updNamaPelanggan" placeholder="Nama pelanggan" value="<?= $data['namaPel'] ?>">
                    <input type="hidden" name="updAlamatPelanggan" placeholder="Alamat pelanggan" value="<?= $data['alamat'] ?>">
                    <input type="hidden" name="updTelpPelanggan" placeholder="Telepon pelanggan" value="<?= $data['telp'] ?>">
                <td><button name="updatePelanggan">update</button></td>
                </form>
            </tr>
        <?php endwhile; ?>
    </table>

    <a href="home.php">kembali</a> - <a href="tambah-pelanggan.php">tambah pelanggan</a>
</body>
</html>