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
                $_SESSION['level'] = $data['level'];
                
                header('location: home.php');
                exit();
            }
        }else{
            echo 'password atau username salah';
        }
    } else {
        echo 'data tidak lengkap';
    }
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
    <h2>login</h2>
    <form method="post">
        <input type="text" name="username" autocomplete="off" value="<?= @$username ?>" placeholder="masukan ID kasir">
        <input type="password" name="password" autocomplete="off" placeholder="masukan password">
        <button name="masuk">masuk</button>
    </form>
</body>
</html>