<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

// Hitung Data untuk Statistik
$chars = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM characters"))['t'];
$story = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM timeline"))['t'];
$realms = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM realms"))['t'];
$weapons = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM weapons"))['t'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Admin Panel</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>

    <div class="sidebar">
        <div class="brand">
            <img src="../asset/logo.png" alt="Logo">
            <h2>ADMIN PANEL</h2>
        </div>
        <div class="menu">
            <a href="admin_dashboard.php" class="menu-link active">DASHBOARD</a>
            <a href="manage_series.php" class="menu-link">MANAGE SERIES</a>
            
            <a href="manage_characters.php" class="menu-link">MANAGE CHARACTERS</a>
            <a href="manage_story.php" class="menu-link">MANAGE STORY</a>
            <a href="manage_realms.php" class="menu-link">MANAGE REALMS</a>
            <a href="manage_weapons.php" class="menu-link">MANAGE WEAPONS</a>
            <a href="../logout.php" class="menu-link logout">LOGOUT</a>
        </div>
    </div>

    <div class="content">
        <div class="page-header" style="justify-content: center;">
            <h1 class="page-title" style="font-size: 3rem; margin-bottom: 40px; text-shadow: 2px 2px 4px #ccc;">DASHBOARD</h1>
        </div>
        
        <div class="dashboard-grid">
            <div class="card-stat">
                <span class="stat-num"><?= $chars; ?></span>
                <span class="stat-label">TOTAL CHARACTERS</span>
            </div>
            <div class="card-stat">
                <span class="stat-num"><?= $story; ?></span>
                <span class="stat-label">TOTAL STORY</span>
            </div>
            <div class="card-stat">
                <span class="stat-num"><?= $realms; ?></span>
                <span class="stat-label">TOTAL REALMS</span>
            </div>
            <div class="card-stat">
                <span class="stat-num"><?= $weapons; ?></span>
                <span class="stat-label">TOTAL WEAPONS</span>
            </div>
        </div>
    </div>

</body>
</html>