<?php
$host = 'localhost';
$user = 'root'; 
$pass = ''; 
$db   = 'project';

$koneksi = mysqli_connect($host, $user, $pass, $db);

if(!$koneksi){
    echo "gagal";
    exit;
}
?>
