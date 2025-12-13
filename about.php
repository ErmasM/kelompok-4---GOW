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
    <title>The Creators - God of War Project</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700;900&family=Lato:wght@400;700&display=swap" rel="stylesheet">
    
    <style>
        /* --- CORE SETTINGS --- */
        * { margin: 0; padding: 0; box-sizing: border-box; user-select: none; }

        body {
            font-family: 'Cinzel', serif;
            color: white;
            background-color: #050505;
            min-height: 100vh;
            display: flex; flex-direction: column;
            overflow-x: hidden;
        }

        /* --- BACKGROUND --- */
        .bg-container {
            position: fixed; top: 0; left: 0;
            width: 100vw; height: 100vh;
            z-index: -10;
        }
        .bg-image {
            width: 100%; height: 100%;
            object-fit: cover;
            background-image: url('asset/background_about.jpg'); 
            background-position: center; background-size: cover;
            filter: brightness(0.25) contrast(1.2);
            animation: breathe 20s infinite alternate ease-in-out;
        }
        .bg-overlay {
            position: absolute; inset: 0;
            background: radial-gradient(circle, transparent 40%, #000 100%);
        }
        @keyframes breathe {
            0% { transform: scale(1); }
            100% { transform: scale(1.1); }
        }

        /* --- NAVBAR FIXED --- */
        .navbar {
            display: flex; justify-content: space-between; align-items: center;
            padding: 15px 50px; position: fixed; width: 100%; top: 0; z-index: 1000;
            background: rgba(0,0,0,0.85); backdrop-filter: blur(8px);
            border-bottom: 1px solid rgba(255,255,255,0.1);
            height: 90px; /* Tinggi navbar ditetapkan */
        }
        .nav-logo img { height: 50px; filter: drop-shadow(0 0 5px rgba(0,0,0,0.8)); }
        .nav-links a {
            text-decoration: none; color: #ccc; margin: 0 8px; letter-spacing: 2px; font-size: 14px;
            padding: 8px 18px; background: rgba(0, 0, 0, 0.5); border-radius: 25px; 
            border: 1px solid transparent; transition: .3s;
        }
        .nav-links a.active, .nav-links a:hover {
            color: white; background: rgba(50, 0, 0, 0.6);
            border-color: #cfa35e; box-shadow: 0 0 15px rgba(207,163,94,.4);
        }
        .logout-btn {
            font-size: 12px; color: #ff3b3b; border: 1px solid #ff3b3b;
            padding: 8px 20px; border-radius: 30px; text-decoration: none;
            background: rgba(0,0,0,0.5); transition: .3s; font-weight: bold;
        }
        .logout-btn:hover { background: #ff3b3b; color: white; box-shadow: 0 0 15px #ff3b3b; }

        /* --- MAIN LAYOUT --- */
        .main-content {
            margin-top: 90px; /* Sama dengan tinggi navbar */
            width: 100%;
            display: flex; flex-direction: column; align-items: center;
        }

        /* --- SECTION 1: HERO (FULL SCREEN) --- */
        .section-hero {
            width: 100%; 
            /* Trik agar tinggi pas 1 layar dikurangi navbar */
            height: calc(100vh - 90px); 
            min-height: 600px; /* Mencegah gepeng di layar pendek */
            display: flex; flex-direction: column; 
            justify-content: center; align-items: center;
            position: relative;
        }

        /* --- SECTION 2 & 3: CONTENT --- */
        .section-content {
            width: 100%; max-width: 1200px;
            padding: 100px 20px; /* Spacing atas bawah lega */
            display: flex; flex-direction: column; 
            align-items: center; justify-content: center;
        }

        /* --- TYPOGRAPHY --- */
        .page-title {
            font-size: 3.5rem; color: #cfa35e; letter-spacing: 12px;
            text-transform: uppercase; margin-bottom: 10px;
            text-shadow: 0 0 30px rgba(207,163,94,0.3); text-align: center;
        }
        .page-subtitle {
            font-family: 'Lato', sans-serif; color: #888; letter-spacing: 4px;
            font-size: 0.9rem; text-transform: uppercase; margin-bottom: 40px;
            border-bottom: 2px solid #b30000; padding-bottom: 10px; display: inline-block;
        }

        /* --- CREATOR CARDS --- */
        .creators-container {
            display: flex; gap: 60px; justify-content: center; flex-wrap: wrap;
            perspective: 1200px; margin-bottom: 20px;
        }
        .god-card {
            width: 280px; height: 450px; position: relative;
            transform-style: preserve-3d; transition: transform 0.1s;
            cursor: pointer; opacity: 0; animation: floatUp 1s ease-out forwards;
        }
        .god-card-inner {
            width: 100%; height: 100%;
            background: linear-gradient(145deg, rgba(20,20,20,0.85), rgba(10,10,10,0.95));
            backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1);
            clip-path: polygon(20px 0, 100% 0, 100% calc(100% - 30px), calc(100% - 30px) 100%, 0 100%, 0 20px);
            display: flex; flex-direction: column; overflow: hidden;
            box-shadow: 0 20px 50px rgba(0,0,0,0.6); transition: 0.3s;
        }
        .god-card:hover .god-card-inner { border-color: #cfa35e; box-shadow: 0 0 30px rgba(207, 163, 94, 0.2); }
        .god-img-box { height: 65%; width: 100%; overflow: hidden; border-bottom: 2px solid rgba(255,255,255,0.05); }
        .god-img { width: 100%; height: 100%; object-fit: cover; filter: grayscale(100%) contrast(1.1); transition: 0.6s; }
        .god-card:hover .god-img { filter: grayscale(0%) contrast(1.05); transform: scale(1.1); }
        .god-info { flex: 1; display: flex; flex-direction: column; justify-content: center; align-items: center; position: relative; }
        .rune-bg { position: absolute; font-size: 70px; opacity: 0.05; color: white; top: 50%; left: 50%; transform: translate(-50%, -50%); pointer-events: none; }
        .god-name { font-size: 1.2rem; color: #fff; font-weight: 700; text-align: center; z-index: 2; transition: .3s; }
        .god-card:hover .god-name { color: #cfa35e; text-shadow: 0 0 15px rgba(207,163,94,0.6); }
        .god-divider { width: 40px; height: 2px; background: #b30000; margin: 10px 0; transition: 0.4s; }
        .god-card:hover .god-divider { width: 80px; box-shadow: 0 0 5px #b30000; }
        .god-nim { font-family: 'Lato', sans-serif; color: #aaa; letter-spacing: 2px; font-size: 0.85rem; z-index: 2; }
        .god-card:nth-child(1) { animation-delay: 0.2s; }
        .god-card:nth-child(2) { animation-delay: 0.4s; }

        /* --- SCROLL INDICATOR (UPDATED) --- */
        .scroll-indicator {
            position: absolute; 
            bottom: 30px; /* Jarak dari bawah layar */
            left: 50%; transform: translateX(-50%);
            display: flex; flex-direction: column; align-items: center;
            opacity: 0.7; animation: bounce 2s infinite; cursor: pointer;
        }
        .scroll-text { font-size: 10px; letter-spacing: 2px; color: #cfa35e; margin-bottom: 5px; }
        .scroll-arrow { font-size: 20px; color: #cfa35e; }
        
        @keyframes bounce { 
            0%, 20%, 50%, 80%, 100% {transform: translateY(0) translateX(-50%);} 
            40% {transform: translateY(-10px) translateX(-50%);} 
            60% {transform: translateY(-5px) translateX(-50%);} 
        }

        /* --- VISION BOX --- */
        .vision-box {
            width: 80%; max-width: 800px; padding: 40px;
            background: rgba(0,0,0,0.6); border: 1px solid #cfa35e; border-left: 5px solid #cfa35e;
            transform: translateY(50px); opacity: 0; transition: 1s ease-out;
        }
        .vision-box.active { transform: translateY(0); opacity: 1; }
        .vision-text { font-family: 'Lato', sans-serif; font-size: 1.1rem; line-height: 1.8; color: #ddd; text-align: justify; }
        .vision-quote { margin-top: 20px; font-size: 1.5rem; color: #cfa35e; text-align: center; font-style: italic; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px; }

        /* --- TECH STACK --- */
        .tech-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 30px; margin-top: 30px; }
        .tech-item {
            width: 120px; height: 120px; background: rgba(20,20,20,0.8); border: 2px solid #333;
            transform: rotate(45deg); display: flex; justify-content: center; align-items: center;
            transition: 0.4s; opacity: 0; transform: translateY(30px) rotate(45deg);
        }
        .tech-content { transform: rotate(-45deg); text-align: center; }
        .tech-icon { font-size: 1.5rem; font-weight: bold; color: #888; display: block; }
        .tech-name { font-size: 0.7rem; color: #555; font-family: 'Lato', sans-serif; margin-top: 5px; }
        .tech-item:hover { border-color: #cfa35e; background: #111; box-shadow: 0 0 20px rgba(207,163,94,0.3); transform: rotate(45deg) scale(1.1); }
        .tech-item:hover .tech-icon { color: #cfa35e; text-shadow: 0 0 10px #cfa35e; }
        .tech-item:hover .tech-name { color: white; }
        .reveal.active { opacity: 1; transform: translateY(0) rotate(45deg); }
        .reveal { transition: 1s all ease; }

        /* --- FOOTER (UPDATED) --- */
        .footer-final {
            padding: 40px 0; color: #888; letter-spacing: 3px; 
            font-size: 0.9rem; font-weight: bold; border-top: 1px solid rgba(255,255,255,0.1);
            width: 80%; text-align: center; margin-top: 50px;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .section-hero { height: auto; padding: 50px 0; min-height: auto; }
            .scroll-indicator { display: none; } /* Hilangkan scroll indicator di HP karena layar pendek */
            .tech-grid { grid-template-columns: repeat(2, 1fr); }
            .navbar { position: relative; flex-direction: column; gap: 10px; height: auto; }
            .main-content { margin-top: 0; }
            .page-title { font-size: 2.2rem; }
        }

        @keyframes floatUp { from { transform: translateY(60px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

    </style>
</head>
<body>

    <div class="bg-container">
        <div class="bg-overlay"></div>
        <div class="bg-image"></div>
    </div>

    <nav class="navbar">
        <a href="home.php" class="nav-logo"><img src="asset/logo.png" alt="God of War"></a>
        <div class="nav-links">
            <a href="home.php">HOME</a>
            <a href="series.php">SERIES</a>
            <a href="about.php" class="active">ABOUT</a>
        </div>
        <a href="logout.php" class="logout-btn">LOGOUT</a>
    </nav>

    <div class="main-content">
        
        <div class="section-hero">
            <h1 class="page-title">THE CREATORS</h1>
            <div class="page-subtitle">PRAKTIKUM PEMROGRAMAN WEB SHIFT A</div>

            <div class="creators-container">
                <div class="god-card" id="card1">
                    <div class="god-card-inner">
                        <div class="god-img-box">
                            <img src="asseT/ERMAS.jpeg" class="god-img" alt="Ermas">
                        </div>
                        <div class="god-info">
                            <div class="rune-bg">ᛖᚱᛗᚨᛊ</div> 
                            <h2 class="god-name">Ermas Muhammad<br>Syatafa</h2>
                            <div class="god-divider"></div>
                            <p class="god-nim">H1D024030</p>
                        </div>
                    </div>
                </div>

                <div class="god-card" id="card2">
                    <div class="god-card-inner">
                        <div class="god-img-box">
                            <img src="asset/EZRA.jpg" class="god-img" alt="Ezra">
                        </div>
                        <div class="god-info">
                            <div class="rune-bg">ᛖᛉᚱᚨ</div> 
                            <h2 class="god-name">Ezra Zackysa<br>Yendi Putra</h2>
                            <div class="god-divider"></div>
                            <p class="god-nim">H1D024111</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="scroll-indicator" onclick="window.scrollTo({top: window.innerHeight, behavior: 'smooth'})">
                <span class="scroll-text">SCROLL</span>
                <span class="scroll-arrow">⌄</span>
            </div>
        </div>

        <div class="section-content">
            <h1 class="page-title reveal-text" style="font-size: 2.5rem;">THE VISION</h1>
            <div class="vision-box reveal-vision">
                <p class="vision-text">
                    Website ini dibangun sebagai persembahan untuk saga legendaris God of War. 
                    Membawa pengguna melintasi sembilan alam, dari panasnya Yunani hingga dinginnya Fimbulwinter di Midgard. 
                    Dibuat tanpa bantuan Library modern, murni menggunakan kekuatan HTML, CSS, dan JavaScript layaknya Spartan sejati.
                </p>
                <div class="vision-quote">
                    "Do not be sorry. Be Better."
                </div>
            </div>
        </div>

        <div class="section-content" style="padding-top: 0;">
            <h1 class="page-title reveal-text" style="font-size: 2.5rem;">THE ARSENAL</h1>
            <div class="page-subtitle" style="margin-bottom: 50px;">WEAPONS OF CREATION</div>
            
            <div class="tech-grid">
                <div class="tech-item reveal-tech"><div class="tech-content"><span class="tech-icon">&lt;/&gt;</span><div class="tech-name">HTML </div></div></div>
                <div class="tech-item reveal-tech"><div class="tech-content"><span class="tech-icon">#</span><div class="tech-name">CSS </div></div></div>
                <div class="tech-item reveal-tech"><div class="tech-content"><span class="tech-icon">JS</span><div class="tech-name">JAVASCRIPT</div></div></div>
                <div class="tech-item reveal-tech"><div class="tech-content"><span class="tech-icon">?</span><div class="tech-name">PHP</div></div></div>
            </div>

            <div class="footer-final">
                PRAKTIKUM PEMROGRAMAN WEB 2025
            </div>
        </div>

    </div>

    <script>
        // --- 1. 3D TILT EFFECT ---
        const cards = document.querySelectorAll('.god-card');
        cards.forEach(card => {
            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left; 
                const y = e.clientY - rect.top;  
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                const rotateX = ((y - centerY) / centerY) * -10; 
                const rotateY = ((x - centerX) / centerX) * 10; 
                card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale(1.05)`;
            });
            card.addEventListener('mouseleave', () => {
                card.style.transform = `perspective(1000px) rotateX(0deg) rotateY(0deg) scale(1)`;
            });
        });

        // --- 2. SCROLL REVEAL ANIMATION ---
        window.addEventListener('scroll', reveal);

        function reveal() {
            var reveals = document.querySelectorAll('.reveal-vision');
            for (var i = 0; i < reveals.length; i++) {
                var windowHeight = window.innerHeight;
                var elementTop = reveals[i].getBoundingClientRect().top;
                var elementVisible = 150;
                if (elementTop < windowHeight - elementVisible) {
                    reveals[i].classList.add('active');
                }
            }

            var techs = document.querySelectorAll('.reveal-tech');
            for (var i = 0; i < techs.length; i++) {
                var windowHeight = window.innerHeight;
                var elementTop = techs[i].getBoundingClientRect().top;
                var elementVisible = 100;
                if (elementTop < windowHeight - elementVisible) {
                    (function(index) {
                        setTimeout(function() {
                            techs[index].classList.add('active');
                            techs[index].classList.remove('reveal-tech');
                            techs[index].classList.add('reveal');
                        }, index * 150);
                    })(i);
                }
            }
        }
    </script>

</body>
</html>