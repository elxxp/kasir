<?php
session_start();
require '../process/cek.php';
require '../process/koneksi.php';

$sql = "SELECT * FROM produk";
$hasil = mysqli_query($koneksi, $sql);

if(isset($_POST['updateProduk'])){
    $id = $_POST['id'];
    $nama = $_POST['updNamaProduk'];
    $harga = $_POST['updHargaProduk'];
    $stok = $_POST['updStokProduk'];

    $sql = "UPDATE produk SET namaProduk='$nama', harga='$harga', stok='$stok' WHERE produkID='$id'";
    $cek = mysqli_query($koneksi, $sql);

    if($cek){
        setcookie('statusUpdate', 'ok', time() + 1);
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
    <h3>Daftar produk</h3>
    <p><?= @$_COOKIE['statusUpdate'] ? 'Berhasil memperbarui produk' : ''?></p>
    <p><?= @$_COOKIE['statusRemove'] ? 'Berhasil menghapus produk' : ''?></p>
    <table border=1>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Harga</th>
            <th>Stok</th>
            <th colspan="2">Aksi</th>
        </tr>

        <?php
        if(mysqli_num_rows($hasil) == 0): ?>
            <td colspan=5>tidak ada data</td>
        <?php endif; ?>

        <?php $nomer = 0; while($data = $hasil->fetch_assoc()):  $nomer++; ?>
            <tr>
                <td><?= $nomer ?></td>
                <td><?= $data['namaProduk'] ?></td>
                <td>Rp. <?= number_format($data['harga']) ?></td>
                <td><?= $data['stok'] ?></td>
                <form action="update-produk-stok.php" method="post">
                    <input type="hidden" name="id" value="<?= $data['produkID'] ?>">
                <td><button name="updateProduk">update</button></td>
                </form>
            </tr>
        <?php endwhile; ?>
    </table>

    <a href="home.php">kembali</a> - <a href="tambah-produk.php">tambah produk</a>
</body>
<script>
</script>
</html>
