<?php
session_start();
include '../koneksi.php';
if (!isset($_SESSION['status']) || $_SESSION['role'] != 'admin') { header("Location: ../login.php"); exit; }

if (isset($_GET['hapus'])) {
    mysqli_query($conn, "DELETE FROM weapons WHERE id='$_GET[hapus]'");
    header("Location: manage_weapons.php");
}
?>
<!DOCTYPE html>
<html lang="id">
<head><title>Manage Weapons</title><link rel="stylesheet" href="admin_style.css"></head>
<body>
    <div class="sidebar">
        <div class="brand"><img src="../asset/logo.png"><h2>ADMIN PANEL</h2></div>
        <div class="menu">
            <a href="admin_dashboard.php" class="menu-link">DASHBOARD</a>
            <a href="manage_series.php" class="menu-link">MANAGE SERIES</a>

            <a href="manage_characters.php" class="menu-link">MANAGE CHARACTERS</a>
            <a href="manage_story.php" class="menu-link">MANAGE STORY</a>
            <a href="manage_realms.php" class="menu-link">MANAGE REALMS</a>
            <a href="manage_weapons.php" class="menu-link active">MANAGE WEAPONS</a>
            <a href="../logout.php" class="menu-link logout">LOGOUT</a>
        </div>
    </div>
    <div class="content">
        <div class="page-header">
            <h1 class="page-title">MANAGE WEAPONS</h1>
            <a href="form_weapon.php" class="btn-add">+ ADD NEW</a>
        </div>
        <table>
            <thead><tr><th>ID</th><th>IMAGE</th><th>NAME</th><th>DESC</th><th>SERIES</th><th>ACTION</th></tr></thead>
            <tbody>
                <?php $q = mysqli_query($conn, "SELECT weapons.*, series.judul FROM weapons JOIN series ON weapons.series_id = series.id ORDER BY weapons.id DESC"); $no=1; while($row = mysqli_fetch_assoc($q)): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><img src="../asset/<?= $row['gambar']; ?>" width="50" style="border:1px solid #ccc;"></td>
                    <td><?= $row['nama_senjata']; ?></td>
                    <td><?= substr($row['deskripsi'], 0, 50).'...'; ?></td>
                    <td><?= $row['judul']; ?></td>
                    <td><a href="form_weapon.php?edit=<?= $row['id']; ?>" class="btn-action btn-edit">EDIT</a><a href="manage_weapons.php?hapus=<?= $row['id']; ?>" class="btn-action btn-delete" onclick="return confirm('Delete?')">DELETE</a></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>