<?php
session_start();
require '../process/cek.php';
require '../process/koneksi.php';
require '../process/functions.php';
pageOnly('admin');

if(isset($_POST['tambahAkun'])){
    @$username = $_POST['usernameNew'];
    @$name = $_POST['nameNew'];
    @$password = $_POST['passwordNew'];
    @$level = $_POST['levelNew'];

    if($username != '' && $name != '' && $password != ''){
        if($level != 'null'){
            $sqlAddAkun = "INSERT INTO user (username, name, password, level) VALUES ('$username', '$name', '$password', '$level')";
            mysqli_query($koneksi, $sqlAddAkun);
    
            setcookie('statusAdd', 'ok', time() + 1, "/");
            header('location: daftar-akun.php');
        } else {
            $notif = "<div class='show notif yellow' id='notif'><i class='fa-solid fa-circle-exclamation icon'></i><p>pilih level akun</p></div>";
        }
    } else {
        $notif = "<div class='show notif red' id='notif'><i class='fa-solid fa-circle-xmark icon'></i><p>data tidak lengkap</p></div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php require '../_partials/head.html'; ?>
    <link rel="stylesheet" href="../lib/css/styleAddAkun.css">
</head>
<body>
    <?= @$notif ?>
    <img src="../lib/images/back.jpg">
    <div class="container">
        <?php require '../_partials/header.php'; ?>
        
        <div class="title">
            <h3 class="title">Tambah akun</h3>
        </div>

        <form method="post">
            <div class="input"><input type="text" name="usernameNew" placeholder="ID Kasir" autocomplete="off" value="<?= @$username ?>"><i class="fa-solid fa-at"></i></div>
            <div class="input"><input type="text" name="nameNew" placeholder="Nama lengkap" autocomplete="off" value="<?= @$name ?>"><i class="fa-solid fa-user"></i></div>
            <div class="input select">
                <select name="levelNew">
                    <option value="null"> Pilih level </option>
                    <option value="admin"> admin </option>
                    <option value="kasir"> kasir </option>
                    <option value="restocker"> restocker </option>
                </select>
                <i class="fa-solid fa-universal-access"></i>
            </div>
            
            <div class="input"><input type="password" name="passwordNew" id="password" placeholder="Password" autocomplete="off"><i class="fa-solid fa-key"></i></div>    
            <button class="inForm" name="tambahAkun">tambah</button>
        </form>
        
        <div class="content-buttons">
            <button onclick=dafAkun()>kembali</button>
        </div>
    </div>
</body>
<?php require '../_partials/footer.html'; ?>
<script>
    
</script>
</html>