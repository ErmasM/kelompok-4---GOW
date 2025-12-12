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
    <title>About Us</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .bg-container { 
            position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
            background-image: url('asset/GOW3.jpg'); /* Pakai aset lokal */
            background-size: cover; background-position: center; z-index: -1; filter: brightness(0.4); 
        }
        .main-content { padding: 50px 20px; text-align: center; }
        .team-container { display: flex; gap: 50px; flex-wrap: wrap; justify-content: center; margin-top: 50px; }
        .member-card { width: 300px; height: 450px; background: rgba(20, 20, 20, 0.8); border: 1px solid #444; position: relative; transition: 0.4s; overflow: hidden; }
        .member-card:hover { transform: translateY(-10px); border-color: #cfa35e; }
        .member-img { width: 100%; height: 70%; object-fit: cover; filter: grayscale(80%); transition: 0.5s; }
        .member-card:hover .member-img { filter: grayscale(0%); transform: scale(1.1); }
        .member-info { position: absolute; bottom: 0; width: 100%; height: 35%; background: linear-gradient(to top, #000 90%, transparent); display: flex; flex-direction: column; justify-content: center; color: white; }
    </style>
</head>
<body>
    <div class="bg-container"></div>
    <nav class="navbar">
        <a href="home.php" class="nav-logo"><img src="asset/logo.png"></a>
        <div class="nav-links">
            <a href="home.php">HOME</a>
            <a href="series.php">SERIES</a>
            <a href="about.php" class="active">ABOUT</a>
        </div>
        <a href="logout.php" class="btn-primary" style="font-size: 12px;">LOGOUT</a>
    </nav>

    <div class="main-content">
        <h1 class="hero-title" style="font-size: 3rem;">THE CREATORS</h1>
        <div style="border-top: 1px solid #cfa35e; border-bottom: 1px solid #cfa35e; padding: 10px 20px; display: inline-block; color: #cfa35e;">PRAKTIKUM PEMROGRAMAN WEB SHIFT A</div>
        
        <div class="team-container">
            <div class="member-card">
                <img src="asset/GOW.jpg" alt="Ermas" class="member-img">
                <div class="member-info">
                    <h2 style="margin-bottom: 5px;">ERMAS MUHAMMAD <br> SYATAFA</h2>
                    <p style="color: #cfa35e;">H1D024030</p>
                </div>
            </div>
            <div class="member-card">
                <img src="asset/GOW2.jpg" alt="Ezra" class="member-img">
                <div class="member-info">
                    <h2 style="margin-bottom: 5px;">EZRA ZACKYSA <br> YENDI PUTRA</h2>
                    <p style="color: #cfa35e;">H1D024111</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>