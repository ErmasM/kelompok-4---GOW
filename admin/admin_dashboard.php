<?php
session_start();
// Mundur satu folder untuk mengambil koneksi
include '../koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php"); // Mundur ke login
    exit;
}

// Gunakan IFNULL untuk menghindari error jika tabel kosong
$count_chars = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM characters"))['total'];
$count_story = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM timeline"))['total'];
$count_realms = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM realms"))['total'];
$count_weapons = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM weapons"))['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family: 'Cinzel', serif; }
        body { display: flex; height: 100vh; background: #f4f4f4; }
        .sidebar { width: 250px; background: #000; color: white; display: flex; flex-direction: column; padding: 20px; }
        .menu-item { padding: 15px; margin-bottom: 10px; background: #cfa35e; color: white; text-decoration: none; text-align: center; display:block; border: 1px solid #b8860b; }
        .menu-item:hover { background: #b30000; }
        .content { flex: 1; padding: 40px; overflow-y: auto; background: white; }
        .stats-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 30px; max-width: 800px; margin: 0 auto; }
        .stat-card { border: 2px solid #cfa35e; padding: 40px; text-align: center; box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        .stat-number { font-size: 3rem; font-weight: bold; display: block; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2 style="color:#cfa35e; text-align:center; margin-bottom:30px;">ADMIN PANEL</h2>
        <a href="admin_dashboard.php" class="menu-item" style="background:#b30000;">DASHBOARD</a>
        <a href="admin_characters.php" class="menu-item">MANAGE CHARACTERS</a>
        <a href="../logout.php" class="menu-item" style="background:#333; margin-top:auto;">LOGOUT</a>
    </div>

    <div class="content">
        <h1 style="text-align:center; margin-bottom:50px;">DASHBOARD STATISTIK</h1>
        <div class="stats-grid">
            <div class="stat-card"><span class="stat-number"><?= $count_chars; ?></span>CHARACTERS</div>
            <div class="stat-card"><span class="stat-number"><?= $count_story; ?></span>STORY</div>
            <div class="stat-card"><span class="stat-number"><?= $count_realms; ?></span>REALMS</div>
            <div class="stat-card"><span class="stat-number"><?= $count_weapons; ?></span>WEAPONS</div>
        </div>
    </div>
</body>
</html>