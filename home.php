<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>God of War Home</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .home-bg {
            background: url('asset/GOW RAGNAROK.jpg') no-repeat center center/cover;
            height: 100vh; width: 100%; position: fixed; top: 0; left: 0; z-index: -1;
            filter: brightness(0.6);
        }
        .hero-section { height: 80vh; display: flex; flex-direction: column; justify-content: center; padding-left: 100px; }
        .hero-logo-img { width: 500px; max-width: 90%; margin-bottom: 30px; filter: drop-shadow(0 10px 20px rgba(0,0,0,0.5)); }
        .welcome-text { font-size: 1.2rem; color: #cfa35e; letter-spacing: 5px; margin-bottom: 20px; text-transform: uppercase; }
        .description { font-family: 'Lato', sans-serif; max-width: 500px; font-size: 1.1rem; line-height: 1.6; color: #ddd; margin-bottom: 40px; text-shadow: 0 2px 4px rgba(0,0,0,0.8); }
    </style>
</head>
<body>
    <div class="home-bg"></div>

    <nav class="navbar">
        <a href="#" class="nav-logo"><img src="asset/logo.png" alt="God of War Logo"></a>
        <div class="nav-links">
            <a href="#" class="active">HOME</a>
            <a href="series.php">SERIES</a>
            <a href="about.php">ABOUT</a>
        </div>
        <div style="font-family: 'Cinzel'; color: #cfa35e; font-weight: bold;">WELCOME, <?php echo $_SESSION['nama']; ?>!</div>
    </nav>

    <div class="hero-section">
        <p class="welcome-text">The Journey Begins</p>
        <img src="asset/logo.png" alt="God of War Title" class="hero-logo-img">
        <p class="description">Masuki dunia para Dewa dan Monster. Telusuri kisah epik Kratos dari masa lalunya di Yunani yang penuh darah hingga perjalanan barunya di tanah Nordik yang dingin.</p>
        <a href="series.php" class="btn-primary">EXPLORE THE SERIES</a>
    </div>
</body>
</html>