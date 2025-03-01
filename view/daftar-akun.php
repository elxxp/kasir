<?php
session_start();
require '../process/cek.php';
require '../process/koneksi.php';
require '../process/functions.php';
pageOnly('admin');

$getIdUser = $_SESSION['id_user'];

$sql = "SELECT * FROM user WHERE id != $getIdUser";
$hasil = mysqli_query($koneksi, $sql);

if(isset($_POST['hapusAkun'])){
    $id = $_POST['id'];
    $sqlRemoveAkun = "DELETE FROM user WHERE id=$id";
    mysqli_query($koneksi, $sqlRemoveAkun);

    header('refresh: 1');
    echo "berhasil menghapus akun";
}

if(isset($_COOKIE['statusAdd'])){
    $notif = "<div class='show notif green' id='notif'><i class='fa-solid fa-circle-check icon'></i><p>berhasil menambahkan akun</p></div>";
}
if(isset($_COOKIE['statusRemove'])){
    $notif = "<div class='show notif green' id='notif'><i class='fa-solid fa-circle-check icon'></i><p>berhasil menghapus akun</p></div>";
}
if(isset($_COOKIE['statusUpdError'])){
    $notif = "<div class='show notif yellow' id='notif'><i class='fa-solid fa-circle-exclamation icon'></i><p>pilih akun terlebih dahulu</p></div>";
}
if(isset($_COOKIE['statusUpdSuccess'])){
    $notif = "<div class='show notif green' id='notif'><i class='fa-solid fa-circle-check icon'></i><p>perubahan akun berhasil disimpan</p></div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php require '../_partials/head.html'; ?>
    <link rel="stylesheet" href="../lib/css/styleDafAkun.css">
</head>
<body>
    <?= @$notif ?>
    <img src="../lib/images/back.jpg">
    <div class="container">
        <?php require '../_partials/header.php'; ?>

        <div class="title" style="animation: contentIn 0.2s cubic-bezier(0.175, 0.885, 0.32, 1);">
            <i class="fa-regular fa-user-tie page-icon"></i>
            <h3 class="title">Daftar akun</h3>
        </div>

        <div class="content-table" style="animation: contentIn 0.4s cubic-bezier(0.175, 0.885, 0.32, 1);">
            <div class="search-box">
                <input type="text" class="search" name="findData" autocomplete="off" placeholder="cari akun">
            </div>

            <div class="table-header">
                <div class="tab-nomer">No</div>
                <div class="tab-akun">Akun</div>
                <div class="tab-status">Status</div>
            </div>

            <div class="box-table-data">
                <!-- <div class="table-data">
                    <div class="tab-nomer-data">1</div>
                    <div class="tab-akun-data"><div class="subdata"><h1><?= $_SESSION['name'] ?> - me</h1><p>@<?= $_SESSION['username'] ?></p></div></div>
                    <div class="tab-status-data"><div class="subdata"><h1><?= $_SESSION['level'] ?></h1><p>#U-<?= formatIdAkun($_SESSION['id_user']) ?></p></div></div>
                </div> -->

                <?php $nomer = 0; while($data = $hasil->fetch_assoc()):  $nomer++; ?>
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
                <?php endwhile; ?>
            </div>

            </div>
    
        <div class="content-buttons">
            <button onclick=addAkun() style="animation: contentIn 0.6s cubic-bezier(0.175, 0.885, 0.32, 1);">tambah akun</button>
            <button onclick=home() style="animation: contentIn 0.8s cubic-bezier(0.175, 0.885, 0.32, 1);">kembali</button>
        </div>
    </div>
</body>
<?php require '../_partials/footer.html'; $order = 1; ?>
<script>
    <?php $order = 0; while($order < mysqli_num_rows($hasil)): $order++;?>
    function detailAkun<?= $order ?>(){
        document.getElementById('contentPopup<?= $order ?>').classList.replace("idle","showDetail")
        document.getElementById('contentPopup<?= $order ?>').classList.replace("hideDetail","showDetail")
        document.getElementById('overlay<?= $order ?>').classList.add("showOverlay")
    }
    function closeAkun<?= $order ?>(){
        document.getElementById('contentPopup<?= $order ?>').classList.replace("showDetail", "hideDetail")
        document.getElementById('overlay<?= $order ?>').classList.remove("showOverlay")
    }
    <?php endwhile; ?>

    document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.querySelector(".search");
    const resultsContainer = document.querySelector(".box-table-data");

    searchInput.addEventListener("keyup", function() {
        let query = searchInput.value;
        
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "../process/find-akun.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                resultsContainer.innerHTML = xhr.responseText;
            }
        };
        xhr.send("query=" + encodeURIComponent(query));
        });
    });
</script>
</html>