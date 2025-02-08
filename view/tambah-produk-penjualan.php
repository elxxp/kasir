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

if(isset($_POST['tambahProduk'])){
    
    $cekAddPenjualan = 0;
    @$idPelanggan = $_POST['pelanggan'];

    if(isset($_POST['next']) && $idPelanggan != 'null'){
        $idPenjualan = $_POST['penjualanID'];
        $sqlAddPenjualan = "INSERT INTO penjualan (penjualanID, totalHarga, pelangganID) VALUES ('$idPenjualan', 0, $idPelanggan)";
        mysqli_query($koneksi, $sqlAddPenjualan);
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

                    $sqlUpdTotalPembelian = "UPDATE penjualan SET totalHarga = totalHarga + $total WHERE penjualanID = $id";
                    mysqli_query($koneksi, $sqlUpdTotalPembelian);

                    $sqlUpdStok = "UPDATE produk SET stok = stok - $qty WHERE produkID = $produkID";
                    mysqli_query($koneksi, $sqlUpdStok);
                } else {
                    echo "Stok tidak mencuckupi";
                }
            } else {
                echo "Masukan quantity yang valid!";
            }
        } else {
            echo "pilih produk!";
        }
    }

    if($idPelanggan != 'null'){ ?>

        <br><br>
        <p>Struk pembelian : #<?= $id ?> - <?=  namaPelanggan($idPelanggan) ?></p>
        <form method="post">
            <select name="produk">
                <option value="null"> --- pilih produk --- </option>
                <?php while($dataProduk = $rstProduk->fetch_assoc()): ?>
                <option value="<?= $dataProduk['produkID'] ?>"><?= $dataProduk['namaProduk'] ?> | Rp. <?= number_format($dataProduk['harga']) ?> (<?= $dataProduk['stok'] ?>)</option>
                <?php endwhile; ?>
            </select>
            <input type="number" name="qty" placeholder="masukan jumlah produk">

            <input type="hidden" name="pelanggan" value="<?= $idPelanggan ?>"> <!-- biar id pelanggan e ketangkep terus -->
            <input type="hidden" name="idPenjualanAdd" value="<?= $id ?>"> <!-- biar id penjualan e ketangkep terus -->
            <input type="hidden" name="tambahPenjualan"> <!-- buat nambah penjualan -->
            
            <button name="tambahProduk">tambah produk</button> <!-- looping form sama kayak abis milih pelanggan -->
        </form>

    <?php } else {
        echo "<script>alert('oon'); location.href = 'pilih-pelanggan.php'</script>";
    }
} else {
    header('location: pilih-pelanggan.php');
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
                <td colspan=5><h6>belum menambah produk</h6></td>
            </tr>
    <?php } ?>

    <tr>
        <td colspan=4>Total pembelian</td>
        <td>Rp. <?= $dataTunggalDaftarPenjualan['totalHarga'] ? number_format($dataTunggalDaftarPenjualan['totalHarga']) : "----" ?></td>
    </tr>
</table>
<br>

<?php if(mysqli_num_rows($rstDaftarProduk) != 0): ?>
<button onclick="kembali()">selesai</button>
<?php endif; ?>

<script>
    function kembali(){
        location.href="daftar-penjualan.php"
    }
</script>