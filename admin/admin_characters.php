<?php
session_start();
include 'koneksi.php';

// Cek Admin
if (!isset($_SESSION['status']) || $_SESSION['role'] != 'admin') { header("Location: login.php"); exit; }

// --- LOGIKA CREATE (TAMBAH DATA) ---
if (isset($_POST['add'])) {
    $series_id = $_POST['series_id'];
    $nama = htmlspecialchars($_POST['nama']);
    $peran = htmlspecialchars($_POST['peran']);
    
    // Upload Gambar
    $gambar = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];
    $path = "asset/" . $gambar;

    if (move_uploaded_file($tmp, $path)) {
        $query = "INSERT INTO characters (series_id, nama, peran, gambar) VALUES ('$series_id', '$nama', '$peran', '$gambar')";
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Berhasil Menambahkan!'); window.location='admin_characters.php';</script>";
        }
    } else {
        echo "<script>alert('Gagal Upload Gambar!');</script>";
    }
}

// --- LOGIKA DELETE (HAPUS DATA) ---
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM characters WHERE id='$id'");
    header("Location: admin_characters.php");
}

// Ambil Data untuk Tabel
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
        /* Style Admin Sederhana (Sama seperti Dashboard) */
        * { box-sizing:border-box; font-family: 'Cinzel', serif; }
        body { display: flex; min-height: 100vh; background: #f4f4f4; margin:0; }
        .sidebar { width: 250px; background: #000; color: white; display: flex; flex-direction: column; padding: 20px; flex-shrink: 0; }
        .menu-item { padding: 15px; margin-bottom: 10px; background: #cfa35e; color: white; text-decoration: none; text-align: center; font-weight: bold; display:block; border: 1px solid #b8860b; }
        .menu-item:hover { background: #b30000; }
        
        /* Content Area */
        .content { flex: 1; padding: 40px; overflow-y: auto; }
        h1 { color: #900; margin-bottom: 30px; border-bottom: 2px solid #cfa35e; padding-bottom: 10px; }
        
        /* Form Style */
        .form-box { background: white; padding: 20px; border: 1px solid #ccc; margin-bottom: 40px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .input-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select { width: 100%; padding: 10px; border: 1px solid #ccc; font-family: sans-serif; }
        .btn-save { background: #cfa35e; color: white; border: none; padding: 10px 20px; cursor: pointer; font-size: 16px; }
        .btn-save:hover { background: #b30000; }

        /* Table Style */
        table { width: 100%; border-collapse: collapse; background: white; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background: #333; color: white; }
        img.thumb { width: 50px; height: 50px; object-fit: cover; border-radius: 50%; }
        .btn-del { background: red; color: white; padding: 5px 10px; text-decoration: none; font-size: 12px; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2 style="color:#cfa35e; text-align:center; margin-bottom:30px;">ADMIN PANEL</h2>
        <a href="admin_dashboard.php" class="menu-item">DASHBOARD</a>
        <a href="admin_characters.php" class="menu-item" style="background:#b30000;">CHARACTERS</a>
        <a href="admin_story.php" class="menu-item">STORY</a>
        <a href="admin_realms.php" class="menu-item">REALMS</a>
        <a href="admin_weapons.php" class="menu-item">WEAPONS</a>
        <a href="logout.php" class="menu-item" style="background:#333; margin-top:auto;">LOGOUT</a>
    </div>

    <div class="content">
        <h1>MANAGE CHARACTERS</h1>

        <div class="form-box">
            <h3>ADD NEW CHARACTER</h3>
            <form method="POST" enctype="multipart/form-data">
                <div class="input-group">
                    <label>Pilih Series Game</label>
                    <select name="series_id" required>
                        <?php while($s = mysqli_fetch_assoc($data_series)): ?>
                            <option value="<?= $s['id']; ?>"><?= $s['judul']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="input-group">
                    <label>Nama Karakter</label>
                    <input type="text" name="nama" required>
                </div>
                <div class="input-group">
                    <label>Peran (Contoh: Protagonist, God of Thunder)</label>
                    <input type="text" name="peran" required>
                </div>
                <div class="input-group">
                    <label>Gambar</label>
                    <input type="file" name="gambar" required>
                </div>
                <button type="submit" name="add" class="btn-save">SIMPAN DATA</button>
            </form>
        </div>

        <h3>CHARACTER LIST</h3>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Gambar</th>
                    <th>Nama</th>
                    <th>Peran</th>
                    <th>Series</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no=1; while($row = mysqli_fetch_assoc($data_char)): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><img src="asset/<?= $row['gambar']; ?>" class="thumb"></td>
                    <td><?= $row['nama']; ?></td>
                    <td><?= $row['peran']; ?></td>
                    <td><?= $row['judul']; ?></td>
                    <td>
                        <a href="admin_characters.php?hapus=<?= $row['id']; ?>" class="btn-del" onclick="return confirm('Yakin hapus?');">DELETE</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</body>
</html>