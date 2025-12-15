<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php"); 
    exit;
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manage Series - Admin</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>
    <div class="sidebar">
        <div class="brand"><img src="../asset/logo.png"><h2>ADMIN PANEL</h2></div>
        <div class="menu">
            <a href="admin_dashboard.php" class="menu-link">DASHBOARD</a>
            <a href="manage_series.php" class="menu-link active">MANAGE SERIES</a>
            <a href="manage_characters.php" class="menu-link">MANAGE CHARACTERS</a>
            <a href="manage_story.php" class="menu-link">MANAGE STORY</a>
            <a href="manage_realms.php" class="menu-link">MANAGE REALMS</a>
            <a href="manage_weapons.php" class="menu-link">MANAGE WEAPONS</a>
            <a href="../logout.php" class="menu-link logout">LOGOUT</a>
        </div>
    </div>

    <div class="content">
        <div class="page-header">
            <h1 class="page-title">MANAGE SERIES (THE BEGINNING)</h1>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>COVER</th>
                    <th>JUDUL</th>
                    <th>THE BEGINNING (DESKRIPSI)</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $q = mysqli_query($conn, "SELECT * FROM series ORDER BY id ASC");
                $no = 1;
                while($row = mysqli_fetch_assoc($q)): 
                ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><img src="../asset/<?= $row['gambar']; ?>" width="60" style="border:1px solid #ccc;"></td>
                    <td style="font-weight:bold; color:#b30000;"><?= $row['judul']; ?></td>
                    <td style="font-size:0.9rem; color:#555;">
                        <?= substr($row['deskripsi'], 0, 100) . '...'; ?>
                    </td>
                    <td>
                        <a href="form_series.php?edit=<?= $row['id']; ?>" class="btn-action btn-edit">EDIT TEXT</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
