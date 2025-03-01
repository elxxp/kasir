<?php
session_start();
if(isset($_SESSION['username'])){
    header('location: home.php');
}

if(isset($_POST['masuk'])){
    require '../process/koneksi.php'; 

    @$username = htmlspecialchars($_POST['username']);
    @$password = htmlspecialchars($_POST['password']);

    if($username != '' && $password != ''){
        $sql = "SELECT * FROM user WHERE username='$username' AND password='$password'";
        $hasil = mysqli_query($koneksi, $sql);

        if(mysqli_num_rows($hasil) > 0){
            while($data = $hasil->fetch_assoc()){

                $_SESSION['id_user'] = $data['id'];
                $_SESSION['username'] = $data['username'];
                $_SESSION['name'] = $data['name'];
                $_SESSION['level'] = $data['level'];
                
                header( "Refresh:1; url=home.php");
                $notif = "<div class='show notif green' id='notif'><i class='fa-solid fa-circle-check icon'></i><p>berhasil masuk</p></div>";
            }
        }else{
            $notif = "<div class='show notif yellow' id='notif'><i class='fa-solid fa-circle-exclamation icon'></i><p>username atau password salah</p></div>";
        }
    } else {
        $notif = "<div class='show notif red' id='notif'><i class='fa-solid fa-circle-xmark icon'></i><p>data tidak lengkap</p></div>";
    }
}

if(isset($_COOKIE['notAllowed'])){
    $notif = "<div class='show notif yellow' id='notif'><i class='fa-solid fa-circle-exclamation icon'></i><p>sihlakan login terlebih dahulu</p></div>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" href="../lib/images/fav.png">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.6.0/css/all.css">
    <link rel="stylesheet" href="../lib/css/font.css">
    <link rel="stylesheet" href="../lib/css/styleLogin.css">
    <link rel="stylesheet" href="../lib/css/notif.css">
</head>
<body>
    <?= @$notif ?>
    <img src="../lib/images/back-login.jpg">
    <div class="container">
        <i class="fa-solid fa-box-open-full logo"></i>
        <h2 class="title">Aplikasi Kasir</h2>
        <form method="post">
            <div class="input"><input type="text" name="username" autocomplete="off" value="<?= @$username ?>" placeholder="ID kasir"><i class="fa-solid fa-user"></i></div>
            <div class="input"><input type="password" name="password" autocomplete="off" placeholder="password"><i class="fa-solid fa-key"></i></div>
            
            <div class="continue"  onclick="loadingClick()"><button name="masuk" id="buttonLoading">masuk</button></div>
        </form>
    </div>
</body>
<script src="../lib/js/scriptLogin.js"></script>
<script src="../lib/js/notif.js"></script>
</html>
