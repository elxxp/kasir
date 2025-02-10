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

function jumJenisProdukTerjual($idPenjualan){
    global $koneksi;
    $sqlGetJumJenProduk = "SELECT COUNT(DISTINCT produkID) AS produk FROM detailpenjualan WHERE penjualanID = $idPenjualan";
    $rstGetJumJenProduk = mysqli_query($koneksi, $sqlGetJumJenProduk);
    $dataJumJenProduk = $rstGetJumJenProduk->fetch_assoc();
    $jumJenProduk = $dataJumJenProduk['produk'];
    return $jumJenProduk;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php require '../_partials/head.html'; ?>
    <link rel="stylesheet" href="../lib/css/styleDafPenjualan.css">
</head>
<body>
    <?= @$notif ?>
    <img src="../lib/images/back.jpg">
    <div class="container">
        <?php require '../_partials/header.html'; ?>

        <div class="title">
            <i class="fa-regular fa-file-invoice-dollar page-icon"></i>
            <h3 class="title">Daftar penjualan</h3>
        </div>
        <p><?= @$_COOKIE['statusUpdate'] ? 'Berhasil memperbarui pelanggan' : ''?></p>

        <div class="content-table">
            <div class="table-header">
                <div class="tab-nomer">No</div>
                <div class="tab-pelanggan">Pelanggan</div>
                <div class="tab-pembelian">Pembelian</div>
            </div>

            <?php $nomer = 0; while($data = $hasil->fetch_assoc()): $nomer++;?>
            <div class="overlay" id="overlay<?= $nomer ?>" onclick=closeDetail<?= $nomer ?>()></div>
            <div class="table-data">
                <div class="tab-nomer-data"><?= $nomer ?></div>
                <div class="tab-pelanggan-data"><div class="subdata"><h1><?= namaPelanggan($data['pelangganID']) ?></h1><p>#PG-<?= $data['pelangganID'] ?></p></div></div>
                <div class="tab-pembelian-data"><div class="subdata"><h1>Rp. <?= number_format($data['totalHarga']) ?></h1><p><?= jumJenisProdukTerjual($data['penjualanID']) ?> Produk tercatat</p></div><span class="detail" onclick=detailPelanggan<?= $nomer ?>()>detail</span></div>

                <div class="popup-detail-pelanggan idle" id="contentPopup<?= $nomer ?>">
                    <i class="fa-solid fa-receipt"></i>
                    <h4 style="margin: 5px 0 20px 0;">Riwayat pembelian</h4>

                    <div class="content-trx">
                        <div class="header">
                            <div class="brand"><i class="fa-solid fa-box-open-full logo"></i><p>Aplikasi Kasir v2.1</p></div>
                            <div class="detail"><h1>2025-01-25 18:05:22</h1><p>#TRX-00</p></div>
                        </div>
                    </div>

                    <div class="buttons">
                        <form action="../process/hapus-pelanggan.php" method="post"><button class="delete" name="hapus" value="<?= $data['pelangganID'] ?>">hapus pelanggan</button></form>
                        <button onclick=closeDetail<?= $nomer ?>()>tutup</button>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>

        <!-- <table border=1>
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
        </table> -->

        <div class="content-buttons">
            <button onclick=addPenjualan()>tambah penjualan</button>
            <button onclick=home()>kembali</button>
        </div>
    </div>
</body>
<?php require '../_partials/footer.html'; ?>
<script>
    <?php $order = 0; while($order < mysqli_num_rows($hasil)): $order++;?>
    function detailPelanggan<?= $order ?>(){
        document.getElementById('contentPopup<?= $order ?>').classList.replace("idle","showDetail")
        document.getElementById('contentPopup<?= $order ?>').classList.replace("hideDetail","showDetail")
        document.getElementById('overlay<?= $order ?>').classList.add("showOverlay")
    }
    function closeDetail<?= $order ?>(){
        document.getElementById('contentPopup<?= $order ?>').classList.replace("showDetail", "hideDetail")
        document.getElementById('overlay<?= $order ?>').classList.remove("showOverlay")
    }
    <?php endwhile; ?>
</script>
</html>