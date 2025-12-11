<?php
session_start();
include 'koneksi.php';


if (!isset($_SESSION['status']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}


$count_chars = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM characters"))['total'];
$count_story = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM timeline"))['total'];
$count_realms = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM realms"))['total'];
$count_weapons = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM weapons"))['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - God of War</title>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family: 'Cinzel', serif; }
        body { display: flex; height: 100vh; background: #f4f4f4; }
        
        /* Sidebar */
        .sidebar {
            width: 250px; background: #000; color: white;
            display: flex; flex-direction: column; padding: 20px;
        }
        .sidebar h2 { margin-bottom: 40px; color: #cfa35e; text-align: center; border-bottom: 1px solid #333; padding-bottom: 20px; }
        .menu-item {
            padding: 15px; margin-bottom: 10px; background: #cfa35e; color: white;
            text-decoration: none; text-align: center; font-weight: bold;
            transition: 0.3s; border: 1px solid #b8860b;
        }
        .menu-item:hover, .menu-item.active { background: #b30000; border-color: red; }
        .logout { margin-top: auto; background: #333; color: #ccc; }

        /* Content */
        .content { flex: 1; padding: 40px; overflow-y: auto; background: white; }
        .dashboard-title { font-size: 3rem; color: #900; text-align: center; margin-bottom: 50px; text-shadow: 1px 1px 5px rgba(0,0,0,0.2); }
        
        .stats-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 30px; max-width: 800px; margin: 0 auto; }
        .stat-card {
            border: 2px solid #cfa35e; padding: 40px; text-align: center;
            background: white; box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            transition: 0.3s;
        }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.2); }
        .stat-number { font-size: 3rem; font-weight: bold; color: #000; display: block; margin-bottom: 10px; }
        .stat-label { color: #900; font-size: 1.2rem; letter-spacing: 2px; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>GOD OF WAR<br><span style="font-size:12px; color:white;">ADMIN PANEL</span></h2>
        <a href="admin_dashboard.php" class="menu-item active">DASHBOARD</a>
        <a href="admin_characters.php" class="menu-item">MANAGE CHARACTERS</a>
        <a href="admin_story.php" class="menu-item">MANAGE STORY</a>
        <a href="admin_realms.php" class="menu-item">MANAGE REALMS</a>
        <a href="admin_weapons.php" class="menu-item">MANAGE WEAPONS</a>
        <a href="logout.php" class="menu-item logout">LOGOUT</a>
    </div>

    <div class="content">
        <h1 class="dashboard-title">DASHBOARD</h1>
        
        <div class="stats-grid">
            <div class="stat-card">
                <span class="stat-number"><?= $count_chars; ?></span>
                <span class="stat-label">TOTAL CHARACTERS</span>
            </div>
            <div class="stat-card">
                <span class="stat-number"><?= $count_story; ?></span>
                <span class="stat-label">TOTAL STORY</span>
            </div>
            <div class="stat-card">
                <span class="stat-number"><?= $count_realms; ?></span>
                <span class="stat-label">TOTAL REALMS</span>
            </div>
            <div class="stat-card">
                <span class="stat-number"><?= $count_weapons; ?></span>
                <span class="stat-label">TOTAL WEAPONS</span>
            </div>
        </div>
    </div>

</body>
</html>