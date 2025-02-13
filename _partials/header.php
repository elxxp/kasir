<div class="content-header">
    <div class="brand"><i class="fa-solid fa-box-open-full logo"></i><div class="detail"><h1>Aplikasi Kasir v1.2.4</h1><p>Halaman <?= $_SESSION['level'] ?></p></div></div>
    <div class="profile" onclick=openPopup()><i class="fa-solid fa-circle-user profile-pfp"></i></div>
</div>

<div class="content-profile" onclick="logout()" id="popupContent">
    <p>Keluar</p><i class="fa-solid fa-person-to-door"></i>
</div>

<?php 
function formatIdPelanggan($number) {
    return str_pad($number, 4, '0', STR_PAD_LEFT);
}
function formatIdPenjualan($number) {
    return str_pad($number, 6, '0', STR_PAD_LEFT);
}
function formatIdProduk($number) {
    return str_pad($number, 5, '0', STR_PAD_LEFT);
}
?>