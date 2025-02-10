<?php
session_start();
require '../process/cek.php';
require '../process/koneksi.php';

if(isset($_POST['tambahAkun'])){
    @$username = $_POST['usernameNew'];
    @$name = $_POST['nameNew'];
    @$password = $_POST['passwordNew'];
    @$level = $_POST['levelNew'];

    if($username != '' && $password != '' && $level != 'null'){

        $sqlAddAkun = "INSERT INTO user (username, name, password, level) VALUES ('$username', '$name', '$password', '$level')";
        mysqli_query($koneksi, $sqlAddAkun);

        header( "Refresh:1; url=daftar-akun.php");
        echo "berhasil menambahkan akun";
    } else {
        echo "data tidak lengkap";
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
    <form method="post">
        Username : <input type="text" name="usernameNew" placeholder="Buat username" autocomplete="off" value="<?= @$username ?>"><br><br>
        Nama : <input type="text" name="nameNew" placeholder="Buat nama" autocomplete="off" value="<?= @$name ?>"><br><br>
        Level : <select name="levelNew">
            <option value="null"> --- pilih level --- </option>
            <option value="admin"> admin </option>
            <option value="kasir"> kasir </option>
            <option value="restocker"> restocker </option>
        </select><br><br>
        Password : <input type="password" name="passwordNew" id="password" placeholder="Buat password" autocomplete="off">
<input type="checkbox" onclick="showPassword()"><br><br>
        <button name="tambahAkun">buat</button>
    </form>
    <a href="daftar-akun.php">kembali</a> 
</body>
<script>
    function showPassword(){
        let show = document.getElementById('password');
        if (show.type== 'password'){
            show.type='text';
        }
        else{
            show.type='password';
        }
    }
</script>
</html>