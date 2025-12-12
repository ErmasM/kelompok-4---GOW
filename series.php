<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php");
    exit;
}

$query = mysqli_query($conn, "SELECT * FROM series");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pilih Series - God of War</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .series-grid {
            display: flex; gap: 30px; overflow-x: auto; 
            padding: 50px; width: 100%; scroll-behavior: smooth;
        }
        .card {
            min-width: 250px; background: rgba(20,20,20,0.9);
            border: 1px solid #444; padding: 15px;
            transition: 0.3s; flex-shrink: 0;
            display: flex; flex-direction: column; align-items: center;
        }
        .card:hover { border-color: var(--primary-red); transform: translateY(-10px); }
        .card img { width: 100%; height: 300px; object-fit: cover; margin-bottom: 15px; }
        .card h3 { font-size: 16px; margin-bottom: 5px; text-align: center; color: var(--gold); }
        .card p { font-size: 12px; color: #aaa; margin-bottom: 15px; font-family: 'Lato'; }
    </style>
</head>
<body>
    <div style="position:fixed; top:0; left:0; width:100%; height:100%; z-index:-1; background: url('asset/GOWRG_Wallpaper_Desktop_Boat_4k.jpg') no-repeat center/cover; filter: brightness(0.5);"></div>

    <nav class="navbar">
        <a href="home.php" class="nav-logo"><img src="asset/logo.png" alt="Logo"></a>
        <div class="nav-links">
            <a href="home.php">HOME</a>
            <a href="series.php" class="active">SERIES</a>
            <a href="about.php">ABOUT</a>
        </div>
        <a href="logout.php" class="btn-primary" style="padding: 5px 15px; font-size:12px;">LOGOUT</a>
    </nav>

    <div style="padding: 50px;">
        <h1 style="text-shadow: 0 4px 10px black; font-size: 3rem; text-align: center; margin-bottom: 20px;">CHOOSE YOUR SAGA</h1>
        
        <div class="series-grid">
            <?php while($data = mysqli_fetch_assoc($query)) : ?>
            <div class="card">
                <img src="asset/<?= $data['gambar']; ?>" alt="<?= $data['judul']; ?>">
                <h3><?= $data['judul']; ?></h3>
                <p><?= $data['tahun']; ?> - <?= $data['platform']; ?></p>
                <a href="<?= $data['link_teleport']; ?>" class="btn-primary" style="width:100%; text-align:center;">TELEPORT</a>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>