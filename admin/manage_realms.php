<?php
session_start();
include '../koneksi.php';
if (!isset($_SESSION['status']) || $_SESSION['role'] != 'admin') { header("Location: ../login.php"); exit; }

if (isset($_GET['hapus'])) {
    mysqli_query($conn, "DELETE FROM realms WHERE id='$_GET[hapus]'");
    header("Location: manage_realms.php");
}
?>
<!DOCTYPE html>
<html lang="id">
<head><title>Manage Realms</title><link rel="stylesheet" href="admin_style.css"></head>
<body>
    <div class="sidebar">
        <div class="brand"><img src="../asset/logo.png"><h2>ADMIN PANEL</h2></div>
        <div class="menu">
            <a href="admin_dashboard.php" class="menu-link">DASHBOARD</a>
            <a href="manage_characters.php" class="menu-link">MANAGE CHARACTERS</a>
            <a href="manage_story.php" class="menu-link">MANAGE STORY</a>
            <a href="manage_realms.php" class="menu-link active">MANAGE REALMS</a>
            <a href="manage_weapons.php" class="menu-link">MANAGE WEAPONS</a>
            <a href="../logout.php" class="menu-link logout">LOGOUT</a>
        </div>
    </div>
    <div class="content">
        <div class="page-header">
            <h1 class="page-title">MANAGE REALMS</h1>
            <a href="form_realm.php" class="btn-add">+ ADD NEW</a>
        </div>
        <table>
            <thead><tr><th>ID</th><th>IMAGE</th><th>NAME</th><th>REGION (TOP/LEFT)</th><th>ACTION</th></tr></thead>
            <tbody>
                <?php $q = mysqli_query($conn, "SELECT * FROM realms ORDER BY id DESC"); $no=1; while($row = mysqli_fetch_assoc($q)): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><img src="../asset/<?= $row['gambar']; ?>" width="50" style="border:1px solid #ccc;"></td>
                    <td><?= $row['nama_realm']; ?></td>
                    <td><?= $row['posisi_top']; ?> / <?= $row['posisi_left']; ?></td>
                    <td><a href="form_realm.php?edit=<?= $row['id']; ?>" class="btn-action btn-edit">EDIT</a><a href="manage_realms.php?hapus=<?= $row['id']; ?>" class="btn-action btn-delete" onclick="return confirm('Delete?')">DELETE</a></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>