<?php
session_start();
require '../process/cek.php';
require '../process/koneksi.php';
$idUser = $_SESSION['id_user'];

$sql = "SELECT * FROM user WHERE id != $idUser";
$hasil = mysqli_query($koneksi, $sql);

if(isset($_POST['hapusAkun'])){
    $id = $_POST['id'];
    $sqlRemoveAkun = "DELETE FROM user WHERE id=$id";
    mysqli_query($koneksi, $sqlRemoveAkun);

    header('refresh: 1');
    echo "berhasil menghapus akun";
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
    <h3>Daftar akun</h3>
    <table border=1>
        <tr>
            <th>No</th>
            <th>Username</th>
            <th>Level</th>
            <th>Aksi</th>
        </tr>

        <?php
        if(mysqli_num_rows($hasil) == 0): ?>
            <td colspan=4>tidak ada data</td>
        <?php endif; ?>

        <?php $nomer = 0; while($data = $hasil->fetch_assoc()): $nomer++;?>
            <tr>
                <td><?= $nomer ?></td>
                <td><?= $data['username'] ?></td>
                <td><?= $data['level'] ?></td>
                <form method="post">
                    <input type="hidden" name="id" value="<?= $data['id'] ?>">
                <td><button name="hapusAkun">hapus</button></td>
                </form>
            </tr>
        <?php endwhile; ?>
    </table>

    <a href="home.php">kembali</a> - <a href="tambah-akun.php">tambah akun</a>
</body>
</html>