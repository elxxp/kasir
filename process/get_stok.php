<?php
require 'koneksi.php';

if (isset($_POST['produkID'])) {
    $produkID = $_POST['produkID'];
    $sql = "SELECT stok FROM produk WHERE produkID = $produkID";
    $result = mysqli_query($koneksi, $sql);
    $data = $result->fetch_assoc();
    
    echo json_encode($data);
}
?>
