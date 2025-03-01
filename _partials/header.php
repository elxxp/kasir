<div class="content-header">
    <div class="brand"><i class="fa-solid fa-box-open-full logo"></i><div class="detail"><h1>Aplikasi Kasir</h1><p>Halaman <?= $_SESSION['level'] ?></p></div></div>
    <div class="profile" onclick=openPopup()><i class="fa-solid fa-circle-user profile-pfp"></i></div>
</div>

<div class="content-profile" onclick="logout()" id="popupContent">
    <p>Keluar</p><i class="fa-solid fa-person-to-door"></i>
</div>

<?php
require '../process/koneksi.php';
$currID = $_SESSION['id_user'];

$sqlUpdateUser = "SELECT * FROM user WHERE id = $currID ";
$hasilUpdateUser = mysqli_query($koneksi, $sqlUpdateUser);
$updateUser = $hasilUpdateUser->fetch_assoc();

$_SESSION['username'] = $updateUser['username'];
$_SESSION['name'] = $updateUser['name'];
$_SESSION['level'] = $updateUser['level'];