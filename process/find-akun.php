<?php
session_start();
require 'koneksi.php';
require 'functions.php';
$getIdUser = $_SESSION['id_user'];
$sql = "SELECT * FROM user WHERE id != '$getIdUser';";

if (isset($_POST['query'])) {
    $search = mysqli_real_escape_string($koneksi, $_POST['query']);
    $sql = "SELECT * FROM user WHERE id != $getIdUser AND (username LIKE '%$search%' OR name LIKE '%$search%' OR level LIKE '%$search%' OR id LIKE '" . findAkunByID($search) . "')";
} else {
    $sql = "SELECT * FROM user WHERE id != '$getIdUser';";
}

$hasil = mysqli_query($koneksi, $sql);
$nomer = 0;
while ($data = mysqli_fetch_assoc($hasil)) {
    $nomer++;
?>

<div class="overlay" id="overlay<?= $nomer ?>" onclick=closeAkun<?= $nomer ?>()></div>
<div class="table-data">
    <div class="tab-nomer-data"><?= $nomer ?></div>
    <div class="tab-akun-data"><div class="subdata"><h1><?= $data['name'] ?></h1><p>@<?= $data['username'] ?></p></div></div>
    <div class="tab-status-data"><div class="subdata"><h1><?= $data['level'] ?></h1><p>#U-<?= formatIdAkun($data['id']) ?></p></div><span class="detail" onclick=detailAkun<?= $nomer ?>()>detail</span></div>

    <div class="popup-detail-pelanggan idle" id="contentPopup<?= $nomer ?>">
        <i class="fa-solid fa-folder-user"></i>
        <h4 style="margin: 5px 0 20px 0;">Detail Akun</h4>
        <div class="detail-data">
            <i class="fa-solid fa-id-badge"></i>
            <div class="information">
                <h6>User ID</h6>
                <p>#U-<?= formatIdAkun($data['id']) ?></p>
            </div>
        </div>
    
        <div class="detail-data">
            <i class="fa-solid fa-user"></i>
            <div class="information">
                <h6>Nama lengkap</h6>
                <p><?= $data['name'] ?></p>
            </div>
        </div>

        <div class="detail-data">
            <i class="fa-solid fa-at"></i>
            <div class="information">
                <h6>ID Kasir</h6>
                <p><?= $data['username'] ?></p>
            </div>
        </div>

        <div class="detail-data">
            <i class="fa-solid fa-universal-access"></i>
            <div class="information">
                <h6>Level</h6>
                <p><?= $data['level'] ?></p>
            </div>
        </div>

        <div class="buttons">
            <form action="../view/update-akun.php" method="post"><button class="edit" name="updateAkun" value="<?= $data['id'] ?>">edit akun</button></form>                    
            <button onclick=closeAkun<?= $nomer ?>()>tutup</button>
        </div>
    </div>
</div>

<?php
}

if(mysqli_num_rows($hasil) == 0){
    ?>
    <div class="data-not-found">
        <i class="fa-regular fa-circle-question"></i>
        <p>data tidak tersedia</p>
    </div>
<?php
}
    
