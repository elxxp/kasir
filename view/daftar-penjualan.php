<?php
session_start();
require '../process/cek.php';
require '../process/koneksi.php';

$sql = "SELECT * FROM penjualan";
$hasil = mysqli_query($koneksi, $sql);

function namaPelanggan($id){
    global $koneksi;
    $sqlGetNamaPelanggan = "SELECT namaPel FROM pelanggan WHERE pelangganID = $id";
    $rstGetNamaPelanggan = mysqli_query($koneksi, $sqlGetNamaPelanggan);
    $dataProdukGetNamaPelanggan = $rstGetNamaPelanggan->fetch_assoc();
    $pelanggan = $dataProdukGetNamaPelanggan['namaPel'];
    return $pelanggan;
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
    <h3>Daftar penjualan</h3>
    <p><?= @$_COOKIE['statusUpdate'] ? 'Berhasil memperbarui pelanggan' : ''?></p>
    <p><?= @$_COOKIE['statusRemove'] ? 'Berhasil menghapus pelanggan' : ''?></p>
    <table border=1>
        <tr>
            <th>No</th>
            <th>ID Pelanggan</th>
            <th>Nama Pelanggan</th>
            <th>Total Pembelian</th>
            <th>Aksi</th>
        </tr>

        <?php
        if(mysqli_num_rows($hasil) == 0): ?>
            <td colspan=5>tidak ada data</td>
        <?php endif; ?>

        <?php 
        $nomer = 0; 
        while($data = $hasil->fetch_assoc()): 
            $nomer++;
        ?>
            <tr>
                <td><?= $nomer ?></td>
                <td><?= $data['pelangganID'] ?></td>
                <td><?= namaPelanggan($data['pelangganID']) ?></td>
                <td>Rp. <?= number_format($data['totalHarga']) ?></td>
                <form action="struk.php" method="post">
                    <input type="hidden" name="penjualanID" value="<?= $data['penjualanID'] ?>">
                <td><button name="struk">detail</td>
                </form>
            </tr>
        <?php endwhile; ?>
    </table>

    <a href="home.php">kembali</a> - <a href="pilih-pelanggan.php">tambah penjualan</a>
</body>
</html>