<?php
session_start();
include '../koneksi.php'; // Path diperbaiki

if (!isset($_SESSION['status']) || $_SESSION['role'] != 'admin') { header("Location: ../login.php"); exit; }

if (isset($_POST['add'])) {
    $series_id = $_POST['series_id'];
    $nama = htmlspecialchars($_POST['nama']);
    $peran = htmlspecialchars($_POST['peran']);
    
    $gambar = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];
    // Simpan gambar ke folder asset di root (mundur satu folder)
    $path = "../asset/" . $gambar;

    if (move_uploaded_file($tmp, $path)) {
        $query = "INSERT INTO characters (series_id, nama, peran, gambar) VALUES ('$series_id', '$nama', '$peran', '$gambar')";
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Berhasil Menambahkan!'); window.location='admin_characters.php';</script>";
        }
    } else {
        echo "<script>alert('Gagal Upload Gambar!');</script>";
    }
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM characters WHERE id='$id'");
    header("Location: admin_characters.php");
}

$data_char = mysqli_query($conn, "SELECT characters.*, series.judul FROM characters JOIN series ON characters.series_id = series.id");
$data_series = mysqli_query($conn, "SELECT * FROM series");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manage Characters</title>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing:border-box; font-family: 'Cinzel', serif; }
        body { display: flex; min-height: 100vh; background: #f4f4f4; margin:0; }
        .sidebar { width: 250px; background: #000; color: white; display: flex; flex-direction: column; padding: 20px; }
        .menu-item { padding: 15px; margin-bottom: 10px; background: #cfa35e; color: white; text-decoration: none; text-align: center; display:block; border: 1px solid #b8860b; }
        .content { flex: 1; padding: 40px; }
        .form-box { background: white; padding: 20px; border: 1px solid #ccc; margin-bottom: 40px; }
        input, select { width: 100%; padding: 10px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; background: white; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background: #333; color: white; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2 style="color:#cfa35e; text-align:center; margin-bottom:30px;">ADMIN PANEL</h2>
        <a href="admin_dashboard.php" class="menu-item">DASHBOARD</a>
        <a href="admin_characters.php" class="menu-item" style="background:#b30000;">CHARACTERS</a>
        <a href="../logout.php" class="menu-item" style="background:#333; margin-top:auto;">LOGOUT</a>
    </div>

    <div class="content">
        <h1>MANAGE CHARACTERS</h1>
        <div class="form-box">
            <h3>ADD NEW CHARACTER</h3>
            <form method="POST" enctype="multipart/form-data">
                <select name="series_id" required>
                    <option value="">-- Pilih Series --</option>
                    <?php while($s = mysqli_fetch_assoc($data_series)): ?>
                        <option value="<?= $s['id']; ?>"><?= $s['judul']; ?></option>
                    <?php endwhile; ?>
                </select>
                <input type="text" name="nama" placeholder="Nama Karakter" required>
                <input type="text" name="peran" placeholder="Peran" required>
                <input type="file" name="gambar" required>
                <button type="submit" name="add" style="padding:10px 20px; background:#b30000; color:white; border:none; cursor:pointer;">SIMPAN</button>
            </form>
        </div>

        <table>
            <thead><tr><th>No</th><th>Gambar</th><th>Nama</th><th>Peran</th><th>Series</th><th>Aksi</th></tr></thead>
            <tbody>
                <?php $no=1; while($row = mysqli_fetch_assoc($data_char)): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><img src="../asset/<?= $row['gambar']; ?>" width="50"></td>
                    <td><?= $row['nama']; ?></td>
                    <td><?= $row['peran']; ?></td>
                    <td><?= $row['judul']; ?></td>
                    <td><a href="admin_characters.php?hapus=<?= $row['id']; ?>" style="color:red;" onclick="return confirm('Hapus?');">Delete</a></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>