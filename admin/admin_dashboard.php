<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

// Hitung Data
$chars = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM characters"))['t'];
$story = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM timeline"))['t'];
$realms = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM realms"))['t'];
$weapons = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM weapons"))['t'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - God of War</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>

    <div class="sidebar">
        <h2>GOD <span>OF</span> WAR<br><small style="font-size:0.8rem; color:#888;">ADMIN PANEL</small></h2>
        <a href="admin_dashboard.php" class="menu-link active">DASHBOARD</a>
        <a href="manage_characters.php" class="menu-link">MANAGE CHARACTERS</a>
        <a href="manage_story.php" class="menu-link">MANAGE STORY</a>
        <a href="manage_realms.php" class="menu-link">MANAGE REALMS</a>
        <a href="manage_weapons.php" class="menu-link">MANAGE WEAPONS</a>
        <a href="../logout.php" class="menu-link" style="background:#333; margin-top:auto;">LOGOUT</a>
    </div>

    <div class="content">
        <h1 style="text-align:center; font-size: 3rem; color: var(--red); margin-bottom: 50px; text-shadow: 2px 2px 0px #ccc;">DASHBOARD</h1>
        
        <div class="stats-grid">
            <div class="stat-card">
                <span class="stat-number"><?= $chars; ?></span>
                <span class="stat-label">TOTAL CHARACTERS</span>
            </div>
            <div class="stat-card">
                <span class="stat-number"><?= $story; ?></span>
                <span class="stat-label">TOTAL STORY</span>
            </div>
            <div class="stat-card">
                <span class="stat-number"><?= $realms; ?></span>
                <span class="stat-label">TOTAL REALMS</span>
            </div>
            <div class="stat-card">
                <span class="stat-number"><?= $weapons; ?></span>
                <span class="stat-label">TOTAL WEAPONS</span>
            </div>
        </div>
    </div>

</body>
</html>