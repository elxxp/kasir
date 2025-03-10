<?php
session_start();
require '../process/cek.php';
require '../process/koneksi.php';
require '../process/functions.php';
pageExcept('restocker');

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

    $sqlProduk = "SELECT * FROM produk ORDER BY namaProduk ASC";
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

    if(isset($_POST['hapusProdukPenjualan'])){
        $idDetail = $_POST['hapusProdukPenjualan'];
        $produkID = $_POST['hapusProdukPenjualan-ProdukID'];
        $qty = $_POST['hapusProdukPenjualan-qty'];

        $sqlRemoveProdukPenjualan = "DELETE FROM detailpenjualan WHERE detailID = $idDetail";
        mysqli_query($koneksi, $sqlRemoveProdukPenjualan); // hapus produk

        $sqlCountTotalSubtotal = "SELECT SUM(subtotal) AS totalPembelian FROM detailpenjualan WHERE penjualanID = $id;";
        $rstCountTotalSubtotal = mysqli_query($koneksi, $sqlCountTotalSubtotal);
        $dataTotal = $rstCountTotalSubtotal->fetch_assoc(); // itung ulang total
        $dataTotal['totalPembelian'] != 0? $total = $dataTotal['totalPembelian']: $total = 0;

        $sqlUpdTotalPembelian = "UPDATE penjualan SET totalHarga = $total WHERE penjualanID = $id";
        mysqli_query($koneksi, $sqlUpdTotalPembelian); // update total penjjualan

        $sqlUpdStok = "UPDATE produk SET stok = stok + $qty WHERE produkID = $produkID";
        mysqli_query($koneksi, $sqlUpdStok); // balikin stok produk

        $notif = "<div class='show notif green' id='notif'><i class='fa-solid fa-circle-check icon'></i><p>berhasil menghapus produk</p></div>";
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

                <div class="overlayDial" id="overlayDial"></div>
                <div class="contentDial" id="contentDial" style="height: 100px;">
                    <form class="formDial" method="post">
                        <p class="title"><i class="fa-solid fa-triangle-exclamation"></i> Selesaikan penjualan</p>
                        <p class="sub"><strong>Penjualan tidak dapat diedit atau dihapus</strong>, setelah data penjualan selesai tercatat.</p>
                            <div class="buttons">
                                <button class="noDial" type="button" onclick=closeDialog()>kembali</button>
                                <button class="doneDial" name="addPenjualanDone">selesai</button>
                            </div>
                    </form>
                </div>

                <div class="title">
                    <h3 class="title" style="margin: 0;">Pembuatan struk penjualan</h3>
                    <p>#TRX-<?= $id ? formatIdPenjualan($id) : '--' ?> | #PG-<?= formatIdPelanggan($idPelanggan) ?> <?= namaPelanggan($idPelanggan) ?></p>
                </div>

        <form method="post">
            <select class="searchable-dropdown" name="produk">
                <option value="null"> --- pilih produk --- </option>
                <?php while($dataProduk = $rstProduk->fetch_assoc()): ?>
                <option value="<?= $dataProduk['produkID'] ?>"><?= $dataProduk['namaProduk'] ?> - Rp. <?= number_format($dataProduk['harga']) ?></option>
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

$sqlGetDaftarProduk = "SELECT * FROM detailpenjualan WHERE penjualanID = $id ORDER BY detailID DESC";
$rstDaftarProduk = mysqli_query($koneksi, $sqlGetDaftarProduk);

$sqlGetDaftarPenjualan = "SELECT totalHarga FROM penjualan WHERE penjualanID = $id";
$rstDaftarPenjualan = mysqli_query($koneksi, $sqlGetDaftarPenjualan);
$dataTunggalDaftarPenjualan = $rstDaftarPenjualan->fetch_assoc();
?>

        <div class="content-table">
            <div class="tab-header">
                <div class="tab-aksi head"></div>
                <div class="tab-produk head">Produk</div>
                <div class="tab-harga head">Harga</div>
                <div class="tab-qty head">QTY</div>
                <div class="subtotal head">Subtotal</div>
            </div>
            
            <div class="box-tab-data">

            <?php if(mysqli_num_rows($rstDaftarProduk) != 0){ ?>
                <?php $nomer = 0; while($dataProdukDaftarProduk = $rstDaftarProduk->fetch_assoc()): $nomer++;?>
        
                    <div class="tab-data">
                        <div class="tab-aksi data" onclick=openDialog<?= $nomer ?>()><i class="fa-solid fa-circle-minus"></i></div>
                        <div class="tab-produk data"><?= namaProduk($dataProdukDaftarProduk['produkID']) ?></div>
                        <div class="tab-harga data">Rp. <?= number_format(hargaBarang($dataProdukDaftarProduk['produkID'])) ?></div>
                        <div class="tab-qty data"><?= $dataProdukDaftarProduk['jumlahProduk'] ?></div>
                        <div class="subtotal data">Rp. <?= number_format($dataProdukDaftarProduk['subtotal']) ?></div>
                    </div>

                    <!-- confirm dialog -->
                    <div class="overlayDial" id="overlayDial<?= $nomer ?>"></div>
                    <div class="contentDial" id="contentDial<?= $nomer ?>" style="height: 110px;">
                        <form class="formDial" method="post">
                            <p class="title"><i class="fa-solid fa-triangle-exclamation"></i> Konfirmasi aksi</p>
                            <p class="sub">Hapus <strong><?= namaProduk($dataProdukDaftarProduk['produkID']) ?></strong> dari penjualan ini? <br><br> <strong>stok produk akan dikembalikan.</strong></p>
                                <div class="buttons">
                                    <button class="noDial" type="button" onclick=closeDialog<?= $nomer ?>()>batal</button>
                                    <input type="hidden" name="pelanggan" value="<?= $idPelanggan ?>"> <!-- biar id pelanggan e ketangkep terus -->
                                    <input type="hidden" name="idPenjualanAdd" value="<?= $id ?>"> <!-- biar id penjualan e ketangkep terus -->
                                    <input type="hidden" name="hapusProdukPenjualan" value="<?= $dataProdukDaftarProduk['detailID'] ?>"> <!-- buat nambah produk penjualan -->
                                    <input type="hidden" name="hapusProdukPenjualan-ProdukID" value="<?= $dataProdukDaftarProduk['produkID'] ?>"> <!-- buat ambil produk -->
                                    <input type="hidden" name="hapusProdukPenjualan-qty" value="<?= $dataProdukDaftarProduk['jumlahProduk'] ?>"> <!-- buat ambil qty -->
                                    <button class="yesDial" name="tambahProduk">hapus</button>
                                </div>
                        </form>
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
            <form style="margin: 0;"><button type="button" onclick=openDialog()>selesai</button></form>
        </div>

        <?php endif; ?>
    </div>
</body>
<?php require '../_partials/footer.html'; ?>
<script>
    <?php $orderProPen = 0; while($orderProPen < mysqli_num_rows($rstDaftarProduk)): $orderProPen++; ?>
        function openDialog<?= $orderProPen ?>(){
            document.getElementById('overlayDial<?= $orderProPen ?>').classList.add('showDial')
            document.getElementById('contentDial<?= $orderProPen ?>').classList.add('showDial')
        }
        function closeDialog<?= $orderProPen ?>(){
            document.getElementById('overlayDial<?= $orderProPen ?>').classList.remove('showDial')
            document.getElementById('contentDial<?= $orderProPen ?>').classList.remove('showDial')
        }
    <?php endwhile; ?> 

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
    $(document).on("focus", ".select2-search__field", function() {
        $(this).attr("placeholder", "cari nama produk" );
    });
</script>
</html>
