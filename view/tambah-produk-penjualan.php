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

if(isset($_POST['addPenjualanDone'])){
    setcookie('addPenjualanDone', 'ok', time() + 1, "/");
    header('location: ../view/daftar-penjualan.php');
    exit();
}

if(isset($_POST['tambahProduk'])){
    
    $cekAddPenjualan = 0;
    @$idPelanggan = $_POST['pelanggan'];

    if(isset($_POST['next']) && $idPelanggan != 'null'){
        $idPenjualan = $_POST['penjualanID'];
        $sqlAddPenjualan = "INSERT INTO penjualan (penjualanID, totalHarga, pelangganID) VALUES ('$idPenjualan', 0, $idPelanggan)";
        mysqli_query($koneksi, $sqlAddPenjualan);
        $notif = "<div class='show notif green' id='notif'><i class='fa-solid fa-circle-check icon'></i><p>berhasil membuat penjualan baru</p></div>";
    }

    @$idPenjualanAdd = $_POST['idPenjualanAdd'];
    $id = $idPenjualanAdd ? $idPenjualanAdd : $idPenjualan;

    $sqlProduk = "SELECT * FROM produk;";
    $rstProduk = mysqli_query($koneksi, $sqlProduk);

    if(isset($_POST['tambahPenjualan'])){
        $pelangganID = $_POST['pelanggan'];
        @$produkID = $_POST['produk'];
        @$qty = $_POST['qty'];
        
        if($produkID != 'null'){
            if($qty > 0 && $qty != ''){
                $sqlGetStok = "SELECT stok FROM produk WHERE produkID = $produkID";
                $rstStok = mysqli_query($koneksi, $sqlGetStok);
                $dataProdukStok = $rstStok->fetch_assoc();
                
                $stokSaatIni = $dataProdukStok['stok'];
                $stok = ($stokSaatIni - $qty);
                
                if($stok >= 0){
                    $subtotal = hargaBarang($produkID) * $qty;
                    $sqlAddDetailPenjualan = "INSERT INTO detailpenjualan (penjualanID, produkID, jumlahProduk, subtotal) VALUES ('$id', '$produkID', '$qty', '$subtotal')";
                    mysqli_query($koneksi, $sqlAddDetailPenjualan);

                    $sqlCountTotalSubtotal = "SELECT SUM(subtotal) AS totalPembelian FROM detailpenjualan WHERE penjualanID = $id;";
                    $rstCountTotalSubtotal = mysqli_query($koneksi, $sqlCountTotalSubtotal);
                    $dataTotal = $rstCountTotalSubtotal->fetch_assoc();
                    $total = $dataTotal['totalPembelian'];

                    $sqlUpdTotalPembelian = "UPDATE penjualan SET totalHarga = $total WHERE penjualanID = $id";
                    mysqli_query($koneksi, $sqlUpdTotalPembelian);

                    $sqlUpdStok = "UPDATE produk SET stok = stok - $qty WHERE produkID = $produkID";
                    mysqli_query($koneksi, $sqlUpdStok);

                    $notif = "<div class='show notif green' id='notif'><i class='fa-solid fa-circle-check icon'></i><p>berhasil menambahkan produk</p></div>";
                } else {
                    $notif = "<div class='show notif yellow' id='notif'><i class='fa-solid fa-circle-exclamation icon'></i><p>stok tidak mencukupi</p></div>";
                }
            } else {
                $notif = "<div class='show notif yellow' id='notif'><i class='fa-solid fa-circle-exclamation icon'></i><p>masukan jumlah quantity yang valid</p></div>";
            }
        } else {
            $notif = "<div class='show notif yellow' id='notif'><i class='fa-solid fa-circle-exclamation icon'></i><p>pilih produk yang tersedia</p></div>";
        }
    }

    if($idPelanggan != 'null'){ ?>

        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Document</title>
            <?php require '../_partials/head.html'; ?>
            <link rel="stylesheet" href="../lib/css/styleAddProdukPenjualan.css">
        </head>
        <body>
            <?= @$notif ?>
            <img src="../lib/images/back.jpg">
            <div class="container">
                <?php require '../_partials/header.php'; ?>

                <div class="title">
                    <h3 class="title" style="margin: 0;">Pembuatan struk penjualan</h3>
                    <p>#TRX-<?= $id ? formatIdPenjualan($id) : '--' ?> | #PG-<?= formatIdPelanggan($idPelanggan) ?> <?= namaPelanggan($idPelanggan) ?></p>
                </div>

        <form method="post">
            <select name="produk">
                <option value="null"> --- pilih produk --- </option>
                <?php while($dataProduk = $rstProduk->fetch_assoc()): ?>
                <option value="<?= $dataProduk['produkID'] ?>"><?= $dataProduk['namaProduk'] ?> - Rp. <?= number_format($dataProduk['harga']) ?> <!-- (<?= $dataProduk['stok'] ?>) --></option>
                <?php endwhile; ?>
            </select>
            <div class="produk-input"><p class="detail-stok">Stok : <strong>--  </strong></p><input type="number" name="qty" placeholder="masukan jumlah produk"></div>

            <input type="hidden" name="pelanggan" value="<?= $idPelanggan ?>"> <!-- biar id pelanggan e ketangkep terus -->
            <input type="hidden" name="idPenjualanAdd" value="<?= $id ?>"> <!-- biar id penjualan e ketangkep terus -->
            <input type="hidden" name="tambahPenjualan"> <!-- buat nambah penjualan -->
            
            <button class="inForm" name="tambahProduk">tambah produk</button> <!-- looping form sama kayak abis milih pelanggan -->
        </form>


    <?php } else {
        setcookie('statusPilPelanggan', 'ok', time() + 1, "/");
        header('location: pilih-pelanggan.php');
    }
} else {
    setcookie('statusPilPelanggan', 'ok', time() + 1, "/");
    header('location: pilih-pelanggan.php');
}

$sqlGetDaftarProduk = "SELECT * FROM detailpenjualan WHERE penjualanID = $id";
$rstDaftarProduk = mysqli_query($koneksi, $sqlGetDaftarProduk);

$sqlGetDaftarPenjualan = "SELECT totalHarga FROM penjualan WHERE penjualanID = $id";
$rstDaftarPenjualan = mysqli_query($koneksi, $sqlGetDaftarPenjualan);
$dataTunggalDaftarPenjualan = $rstDaftarPenjualan->fetch_assoc();
?>

        <div class="content-table">
            <div class="tab-header">
                <div class="tab-produk head">Produk</div>
                <div class="tab-harga head">Harga</div>
                <div class="tab-qty head">QTY</div>
                <div class="subtotal head">Subtotal</div>
            </div>
            
            <div class="box-tab-data">

            <?php if(mysqli_num_rows($rstDaftarProduk) != 0){ ?>
                <?php while($dataProdukDaftarProduk = $rstDaftarProduk->fetch_assoc()): ?>
        
                    <div class="tab-data">
                        <div class="tab-produk data"><?= namaProduk($dataProdukDaftarProduk['produkID']) ?></div>
                        <div class="tab-harga data">Rp. <?= number_format(hargaBarang($dataProdukDaftarProduk['produkID'])) ?></div>
                        <div class="tab-qty data"><?= $dataProdukDaftarProduk['jumlahProduk'] ?></div>
                        <div class="subtotal data">Rp. <?= number_format($dataProdukDaftarProduk['subtotal']) ?></div>
                    </div>
                    
                <?php endwhile; ?>
            <?php } else { ?>

                    <div class="tab-data">
                        <div class="tab-full data">belum menambahkan produk</div>
                    </div>

            <?php } ?>

            </div>

            <div class="tab-footer">
                <div class="tab-total"></div>
                <div class="tab-total-data">Rp. <?= @$dataTunggalDaftarPenjualan['totalHarga'] ? number_format($dataTunggalDaftarPenjualan['totalHarga']) : "----" ?></div>
            </div>
        </div>

        <?php if(mysqli_num_rows($rstDaftarProduk) != 0): ?>
        
        <div class="content-buttons">
            <form method="post" style="margin: 0;"><button name="addPenjualanDone">selesai</button></form>
        </div>
        <?php endif; ?>
    </div> <!-- end container -->
</body>
<?php require '../_partials/footer.html'; ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $("select[name='produk']").change(function() {
            var produkID = $(this).val();
            if (produkID !== "null") {
                $.ajax({
                    url: "../process/get_stok.php",
                    type: "POST",
                    data: { produkID: produkID },
                    dataType: "json",
                    success: function(response) {
                        $(".detail-stok strong").text(response.stok);
                    }
                });
            } else {
                $(".detail-stok strong").text("0");
            }
        });
    });
</script>
</html>
