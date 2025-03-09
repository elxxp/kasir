<?php
session_start();
require '../process/cek.php';
require '../process/koneksi.php';
require '../process/functions.php';
pageOnly('admin');

if(isset($_POST['updateAkun']) || isset($_POST['deleteAkun'])){
    $idAkun = $_POST['updateAkun'];
    
    if(isset($_POST['idAkun'])){
        $idAkun = $_POST['idAkun'];
        @$username = $_POST['updUsername'];
        @$name = $_POST['updNama'];
        @$level = $_POST['updLevel'];
    
        if($username != '' && $name != ''){
            if($level == 'admin' || $level == 'kasir' || $level == 'restocker'){
                $sqlUpdAkun = "UPDATE user SET username = '$username', name = '$name', level = '$level' WHERE id = $idAkun";
                mysqli_query($koneksi, $sqlUpdAkun);
    
                setcookie('statusUpdSuccess', 'ok', time() + 1, "/");
                header('location: ../view/daftar-akun.php');
            } else {
                $notif = "<div class='show notif red' id='notif'><i class='fa-solid fa-circle-xmark icon'></i><p>terjadi kesalahan sistem</p></div>";
            }
        } else {
            $notif = "<div class='show notif red' id='notif'><i class='fa-solid fa-circle-xmark icon'></i><p>data tidak lengkap</p></div>";
        }
    }

    $sqlGetAkun = "SELECT * FROM user WHERE id = $idAkun";
    $rstAkun = mysqli_query($koneksi, $sqlGetAkun);
    $akun = $rstAkun->fetch_assoc();

    if($akun['level'] == 'admin'){
        $akunAdmin = 'selected';
    } elseif ($akun['level'] == 'kasir'){
        $akunKasir = 'selected';
    } elseif ($akun['level'] == 'restocker'){
        $akunRestocker = 'selected';
    }   

} else {
    setcookie('statusUpdError', 'ok', time() + 1, "/");
    header('location: daftar-akun.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php require '../_partials/head.html'; ?>
    <link rel="stylesheet" href="../lib/css/styleUpdAkun.css"> 
</head>
<body>
    <?= @$notif ?>
    <img src="../lib/images/back.jpg">
    <div class="container">
        <?php require '../_partials/header.php'; ?>
        <?= confirmDialog('hapus-akun.php', 'Akun yang terhapus <strong>tidak dapat dikembalikan.</strong>', 'hapusAkun', $akun['id'], '80px') ?>

        <div class="title">
            <h3 class="title">Edit akun</h3>
        </div>

        <form method="post">            
            <div class="input"><input type="text" placeholder="ID Kasir" name="updUsername" autocomplete="off" value="<?= $akun['username'] ?>"><i class="fa-solid fa-at"></i></div>
            <div class="input"><input type="text" placeholder="Nama lengkap" name="updNama" autocomplete="off" value="<?= $akun['name'] ?>"><i class="fa-solid fa-user"></i></div>
            <div class="input select">
                <select name="updLevel">
                    <option value="admin" <?= @$akunAdmin ?>> admin </option>
                    <option value="kasir" <?= @$akunKasir ?>> kasir </option>
                    <option value="restocker" <?= @$akunRestocker ?>> restocker </option>
                </select>
                <i class="fa-solid fa-universal-access"></i>
            </div>
            <input type="hidden" name="idAkun" value="<?= $akun['id'] ?>">
            <button class="inForm stack top update" name="updateAkun">update</button>
            <button class="inForm stack delete" type="button" onclick=openDialog()>delete</button>
        </form>
    
        <div class="content-buttons">
            <button onclick=dafAkun()>kembali</button>
        </div>
    </div>
</body>
<?php require '../_partials/footer.html'; ?>
</html>