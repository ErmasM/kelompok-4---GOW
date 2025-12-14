<?php
session_start();
include 'koneksi.php'; // Jika ada include koneksi

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: index.php"); // Redirect ke index.php
    exit;
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>God of War - Home</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700;900&family=Lato:wght@400;700&display=swap" rel="stylesheet">
    
    <style>
        /* --- RESET --- */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Cinzel', serif;
            color: white;
            overflow: hidden;
            height: 100vh;
            background-color: #000; 
        }

        /* --- BACKGROUND VIDEO --- */
        #bg-video {
            position: fixed;
            top: 0; left: 0;
            width: 100vw; height: 100vh;
            object-fit: fill;
            z-index: -100;
            filter: brightness(0.6);
            transform: scaleX(-1); 
        }

        /* --- SOUND BUTTON --- */
        #sound-btn {
            position: fixed; bottom: 20px; right: 20px;
            padding: 8px 14px;
            background: rgba(0,0,0,0.55);
            color: #cfa35e;
            border: 1px solid #cfa35e;
            font-family: 'Cinzel', serif; font-size: 11px; letter-spacing: 1px;
            border-radius: 8px; cursor: pointer; z-index: 9999;
            transition: 0.3s; text-transform: uppercase;
        }

        #sound-btn:hover {
            background: rgba(40,0,0,0.75);
            box-shadow: 0 0 15px rgba(207,163,94,0.5);
        }

        /* --- NAVBAR --- */
        .navbar {
            display: flex; justify-content: space-between; align-items: center;
            padding: 20px 50px; position: relative; z-index: 10;
        }

        .nav-logo img {
            height: 50px; filter: drop-shadow(0 0 5px rgba(0,0,0,0.8));
        }

        .nav-links a {
            text-decoration: none; color: #ccc; margin: 0 8px; 
            letter-spacing: 2px; font-size: 14px;
            padding: 8px 18px; background: rgba(0, 0, 0, 0.5);
            border-radius: 25px; border: 1px solid transparent; transition: 0.3s;
        }

        .nav-links a.active, .nav-links a:hover {
            color: white; text-shadow: 0 0 10px white;
            background: rgba(50, 0, 0, 0.7);
            border-color: #cfa35e;
            box-shadow: 0 0 15px rgba(207, 163, 94, 0.4);
        }

        .user-profile {
            font-family: 'Cinzel', serif; font-size: 14px;
            color: #cfa35e; letter-spacing: 1px;
            text-transform: uppercase; font-weight: bold;
            padding: 10px 22px; background: rgba(0, 0, 0, 0.8);
            border: 2px solid #cfa35e; border-radius: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.5);
            display: flex; align-items: center;
        }

        /* --- HERO SECTION --- */
        .hero-section {
            height: 80vh; display: flex; flex-direction: column;
            justify-content: center; align-items: flex-start;
            padding-left: 100px; position: relative; z-index: 10;
        }

        .welcome-text {
            font-size: 1.2rem; color: #cfa35e; letter-spacing: 5px;
            margin-bottom: 20px; text-transform: uppercase;
            animation: slideInLeft 1s ease-out;
        }

        .hero-logo-img {
            width: 500px; max-width: 90%; margin-bottom: 30px;
            filter: drop-shadow(0 10px 20px rgba(0,0,0,0.5));
            animation: slideInLeft 1.2s ease-out;
        }

        .description {
            font-family: 'Lato', sans-serif; max-width: 500px;
            font-size: 1.1rem; line-height: 1.6; color: #ddd;
            margin-bottom: 40px; text-shadow: 0 2px 4px rgba(0,0,0,0.8);
            animation: slideInLeft 1.4s ease-out;
        }

        .btn-explore {
            padding: 15px 40px; background: #b30000; color: white;
            font-family: 'Cinzel', serif; font-size: 1.2rem; border: none;
            cursor: pointer; letter-spacing: 3px; text-decoration: none;
            clip-path: polygon(0 0, 100% 0, 95% 100%, 0% 100%);
            transition: 0.3s; animation: fadeInUp 2s ease-out;
            display: inline-block;
        }

        .btn-explore:hover {
            background: #ff0000; padding-right: 50px;
            box-shadow: 0 0 30px rgba(255, 0, 0, 0.5);
        }

        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-50px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 768px) {
            .hero-section { padding-left: 20px; padding-right: 20px; align-items: center; text-align: center; }
            .hero-logo-img { width: 300px; }
            .navbar { padding: 20px; flex-direction: column; gap: 15px; }
            .nav-links { display: none; }
            #bg-video { object-position: center center; }
        }
    </style>
</head>

<body>

    <video autoplay muted loop id="bg-video">
        <source src="assET/Untitled video - Made with Clipchamp (3).mp4" type="video/mp4">
    </video>

    <button id="sound-btn">🔊 ENABLE SOUND</button>

    <script>
        const video = document.getElementById("bg-video");
        const btn = document.getElementById("sound-btn");

        // Fitur Sound Toggle
        btn.addEventListener("click", () => {
            video.muted = !video.muted;
            if (video.muted) {
                btn.innerHTML = "🔊 ENABLE SOUND";
            } else {
                btn.innerHTML = "🔇 MUTE SOUND";
                video.volume = 1;
            }
        });
    </script>

    <nav class="navbar">
        <a href="#" class="nav-logo">
            <img src="asset/logo.png" alt="God of War Logo">
        </a>

        <div class="nav-links">
            <a href="#" class="active">HOME</a>
            <a href="series.php">SERIES</a>
            <a href="about.php">ABOUT</a>
        </div>

        <div class="user-profile">
            WELCOME, <?php echo isset($_SESSION['nama']) ? $_SESSION['nama'] : 'GUEST'; ?>!
        </div>
    </nav>

    <div class="hero-section">
        <p class="welcome-text">The Journey Begins</p>

        <img src="asset/logo.png" alt="God of War Title" class="hero-logo-img">

        <p class="description">
            Masuki dunia para Dewa dan Monster. Telusuri kisah epik Kratos dari masa lalunya di Yunani yang penuh darah hingga perjalanan barunya di tanah Nordik yang dingin.
        </p>

        <a href="series.php" class="btn-explore">EXPLORE THE SERIES</a>
    </div>

</body>
</html>