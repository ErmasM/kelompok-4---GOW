<?php
session_start();

// Cek apakah user sudah login. Jika belum, tendang ke login.php
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
    <title>God of War - Series Selection</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700;900&family=Lato:wght@400;700&display=swap" rel="stylesheet">
    
    <style>
        /* --- RESET & BASIC --- */
        * { margin: 0; padding: 0; box-sizing: border-box; user-select: none; }

        body {
            font-family: 'Cinzel', serif;
            color: white;
            background-color: #000; /* Fallback color */
            overflow: hidden; /* Mencegah scroll halaman utama */
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* --- BACKGROUND IMAGE DENGAN EFEK BERNAPAS (CINEMATIC) --- */
        .bg-container {
            position: fixed; top: 0; left: 0;
            width: 100vw; height: 100vh;
            z-index: -10;
            overflow: hidden;
        }

        .bg-image {
            width: 100%; height: 100%;
            object-fit: cover;
            /* Pastikan nama file background benar */
            background-image: url('asset/GOWRG_Wallpapaper_KeyArt_Background_Desktop_1080 (1).jpg'); 
            background-size: cover;
            background-position: center;
            
            /* Animasi Zoom In-Out perlahan */
            animation: breatheEffect 25s infinite alternate ease-in-out;
            filter: brightness(0.4); /* Gelapkan agar kartu terlihat jelas */
        }

        @keyframes breatheEffect {
            0% { transform: scale(1); }
            100% { transform: scale(1.15); }
        }

        /* --- NAVBAR (SAMA DENGAN HOME) --- */
        .navbar {
            display: flex; justify-content: space-between; align-items: center;
            padding: 20px 50px; position: relative; z-index: 50;
            background: linear-gradient(to bottom, rgba(0,0,0,0.9), transparent);
        }

        .nav-logo img { height: 50px; filter: drop-shadow(0 0 5px rgba(0,0,0,0.8)); }

        .nav-links a {
            text-decoration: none; color: #ccc; margin: 0 8px;
            letter-spacing: 2px; font-size: 14px;
            padding: 8px 18px; background: rgba(0, 0, 0, 0.5);
            border-radius: 25px; border: 1px solid transparent; transition: .3s;
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
        .logout-btn:hover {
            background: #ff3b3b; color: white; box-shadow: 0 0 15px #ff3b3b;
        }

        /* --- MAIN CONTENT --- */
        .main-content {
            flex-grow: 1; display: flex; flex-direction: column;
            justify-content: center; position: relative;
        }

        .page-title {
            text-align: center; font-size: 2.5rem; margin-bottom: 10px;
            color: #cfa35e; letter-spacing: 8px; text-transform: uppercase;
            text-shadow: 0 0 20px rgba(207,163,94,0.4);
            animation: slideDown 1s ease-out;
            position: relative; z-index: 20;
        }

        /* --- SLIDER CONTAINER --- */
        .slider-wrapper {
            position: relative; width: 100%; height: 550px;
            display: flex; align-items: center; justify-content: center;
        }

        .scroll-container {
            display: flex; gap: 40px; padding: 0 80px;
            overflow-x: auto; scroll-behavior: smooth;
            width: 100%; height: 100%; align-items: center;
            /* Sembunyikan Scrollbar */
            scrollbar-width: none; -ms-overflow-style: none;
            cursor: grab; /* Indikator bisa digeser */
        }
        .scroll-container::-webkit-scrollbar { display: none; }
        .scroll-container:active { cursor: grabbing; }

        /* --- CARD DESIGN (GLASSMORPHISM) --- */
        .card {
            flex: 0 0 auto; /* Supaya kartu tidak mengecil */
            width: 280px; height: 460px;
            background: rgba(30, 30, 30, 0.5); /* Semi transparan */
            backdrop-filter: blur(10px); /* Efek Blur Kaca */
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px; padding: 15px;
            display: flex; flex-direction: column; align-items: center;
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            position: relative; overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            opacity: 0; /* Untuk animasi masuk */
        }

        .card:hover {
            transform: translateY(-20px) scale(1.02);
            background: rgba(40, 40, 40, 0.85);
            border-color: #cfa35e;
            box-shadow: 0 0 30px rgba(207, 163, 94, 0.3);
            z-index: 10;
        }

        .card-image-container {
            width: 100%; height: 280px; overflow: hidden;
            border-radius: 6px; margin-bottom: 15px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .card-img { width: 100%; height: 100%; object-fit: cover; transition: 0.6s; }
        .card:hover .card-img { transform: scale(1.15); }

        .card-title {
            font-size: 1rem; text-transform: uppercase; margin-bottom: 5px;
            color: #fff; text-align: center; font-weight: 700;
            min-height: 40px; display: flex; align-items: center; justify-content: center;
        }

        .card-info {
            font-family: 'Lato', sans-serif; font-size: 0.8rem; color: #aaa; margin-bottom: 15px;
        }

        /* --- TELEPORT BUTTON --- */
        .btn-teleport {
            background: linear-gradient(45deg, #8b0000, #b30000);
            color: white; width: 100%; border: none; padding: 12px 0;
            font-family: 'Cinzel', serif; font-weight: bold; font-size: 13px;
            letter-spacing: 2px; cursor: pointer; margin-top: auto;
            clip-path: polygon(10px 0, 100% 0, 100% 100%, 0% 100%, 0% 10px);
            transition: 0.3s;
        }
        .btn-teleport:hover {
            background: #ff0000;
            box-shadow: 0 0 20px rgba(255, 0, 0, 0.6);
            letter-spacing: 3px;
        }

        /* --- NAVIGATION ARROWS --- */
        .nav-arrow {
            position: absolute; top: 50%; transform: translateY(-50%);
            width: 50px; height: 50px;
            background: rgba(0,0,0,0.6); border: 1px solid #cfa35e;
            color: #cfa35e; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px; cursor: pointer; z-index: 30;
            transition: 0.3s;
            backdrop-filter: blur(5px);
        }
        .nav-arrow:hover {
            background: #cfa35e; color: black; box-shadow: 0 0 20px #cfa35e;
        }
        #prevBtn { left: 30px; }
        #nextBtn { right: 30px; }

        /* --- PORTAL OVERLAY EFFECT --- */
        #portal-overlay {
            position: fixed; inset: 0; background: white; z-index: 9999;
            opacity: 0; pointer-events: none; transition: opacity 0.8s ease-in;
        }
        #portal-overlay.active { opacity: 1; pointer-events: all; }

        /* --- ANIMATIONS --- */
        @keyframes slideDown { from { transform: translateY(-40px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        @keyframes fadeInUp { from { transform: translateY(50px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

        /* Animasi kartu muncul satu per satu */
        .card { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; }
        .card:nth-child(1) { animation-delay: 0.1s; }
        .card:nth-child(2) { animation-delay: 0.2s; }
        .card:nth-child(3) { animation-delay: 0.3s; }
        .card:nth-child(4) { animation-delay: 0.4s; }
        .card:nth-child(5) { animation-delay: 0.5s; }
        .card:nth-child(6) { animation-delay: 0.6s; }
        .card:nth-child(7) { animation-delay: 0.7s; }
        .card:nth-child(8) { animation-delay: 0.8s; }

    </style>
</head>
<body>

    <div class="bg-container">
        <div class="bg-image"></div>
    </div>

    <div id="portal-overlay"></div>

    <nav class="navbar">
        <a href="home.php" class="nav-logo"><img src="asset/logo.png" alt="God of War"></a>
        <div class="nav-links">
            <a href="home.php">HOME</a>
            <a href="series.php" class="active">SERIES</a>
            <a href="about.php">ABOUT</a>
        </div>
        <a href="logout.php" class="logout-btn">LOGOUT</a>
    </nav>

    <div class="main-content">
        <h1 class="page-title">CHOOSE YOUR SAGA</h1>

        <div class="slider-wrapper">
            <button id="prevBtn" class="nav-arrow">❮</button>
            
            <div class="scroll-container" id="slider">

              <div class="card">
    <div class="card-image-container"><img src="asset/GOW.jpg" class="card-img"></div>
    <h2 class="card-title">GOD OF WAR</h2>
    <p class="card-info">2005 - PS 2</p>
    <button class="btn-teleport" onclick="openPortal('gow1.php')">TELEPORT</button> 
</div>

                <div class="card">
    <div class="card-image-container">
        <img src="asset/GOW2.jpg" class="card-img" alt="GOW 2">
    </div>
    <h2 class="card-title">GOD OF WAR II</h2>
    <p class="card-info">2007 - PS 2</p>
    
    <button class="btn-teleport" onclick="openPortal('gow2.php')">TELEPORT</button>
</div>

                <div class="card">
                    <div class="card-image-container"><img src="asset/GOW Chains of olympus.jpg" class="card-img" alt="CoO"></div>
                    <h2 class="card-title">CHAINS OF OLYMPUS</h2>
                    <p class="card-info">2008 - PSP</p>
                    <button class="btn-teleport" onclick="openPortal('chains_olympus.html')">TELEPORT</button>
                </div>

                <div class="card">
                    <div class="card-image-container"><img src="asset/GOW3.jpg" class="card-img" alt="GOW 3"></div>
                    <h2 class="card-title">GOD OF WAR III</h2>
                    <p class="card-info">2010 - PS 3</p>
                    <button class="btn-teleport" onclick="openPortal('gow3_layout.html')">TELEPORT</button>
                </div>

                <div class="card">
                    <div class="card-image-container"><img src="asset/GOW ghost of sparta.jpg" class="card-img" alt="GoS"></div>
                    <h2 class="card-title">GHOST OF SPARTA</h2>
                    <p class="card-info">2010 - PSP</p>
                    <button class="btn-teleport" onclick="openPortal('#ghost')">TELEPORT</button>
                </div>

                <div class="card">
                    <div class="card-image-container"><img src="asset/GOW ascension.jpg" class="card-img" alt="Ascension"></div>
                    <h2 class="card-title">GOW: ASCENSION</h2>
                    <p class="card-info">2013 - PS 3</p>
                    <button class="btn-teleport" onclick="openPortal('#ascension')">TELEPORT</button>
                </div>

                <div class="card">
                    <div class="card-image-container"><img src="asset/GOW 2018.jpg" class="card-img" alt="GOW 2018"></div>
                    <h2 class="card-title">GOD OF WAR (2018)</h2>
                    <p class="card-info">2018 - PS 4</p>
                    <button class="btn-teleport" onclick="openPortal('#gow2018')">TELEPORT</button>
                </div>

                <div class="card">
                    <div class="card-image-container"><img src="asset/GOW RAGNAROK.jpg" class="card-img" alt="Ragnarok"></div>
                    <h2 class="card-title">GOW: RAGNARÖK</h2>
                    <p class="card-info">2022 - PS 5</p>
                    <button class="btn-teleport" onclick="openPortal('#ragnarok')">TELEPORT</button>
                </div>

            </div>
            
            <button id="nextBtn" class="nav-arrow">❯</button>
        </div>
    </div>

    <script>
        const slider = document.getElementById("slider");
        const nextBtn = document.getElementById("nextBtn");
        const prevBtn = document.getElementById("prevBtn");

        // --- TOMBOL PREV/NEXT ---
        nextBtn.onclick = () => slider.scrollBy({ left: 320, behavior: "smooth" });
        prevBtn.onclick = () => slider.scrollBy({ left: -320, behavior: "smooth" });

        // --- FITUR DRAG SCROLL (KLIK & TAHAN MOUSE) ---
        let isDown = false;
        let startX;
        let scrollLeft;

        slider.addEventListener('mousedown', (e) => {
            isDown = true;
            slider.style.cursor = 'grabbing';
            startX = e.pageX - slider.offsetLeft;
            scrollLeft = slider.scrollLeft;
        });
        slider.addEventListener('mouseleave', () => { isDown = false; slider.style.cursor = 'grab'; });
        slider.addEventListener('mouseup', () => { isDown = false; slider.style.cursor = 'grab'; });
        slider.addEventListener('mousemove', (e) => {
            if(!isDown) return;
            e.preventDefault();
            const x = e.pageX - slider.offsetLeft;
            const walk = (x - startX) * 2; // Kecepatan scroll
            slider.scrollLeft = scrollLeft - walk;
        });

        // --- FUNGSI PORTAL TELEPORT ---
        function openPortal(url) {
            // Jika link diawali #, berarti belum tersedia
            if(url.startsWith('#')) {
                alert("Realm ini masih terkunci oleh sihir Odin.");
                return;
            }

            const overlay = document.getElementById('portal-overlay');
            const btn = event.target;
            
            // Ubah teks tombol jadi loading
            btn.innerHTML = "WARPING...";
            btn.style.background = "white";
            btn.style.color = "black";
            btn.style.boxShadow = "0 0 30px white";

            // Munculkan layar putih
            overlay.classList.add('active'); 

            // Pindah halaman setelah delay 0.8 detik
            setTimeout(() => {
                window.location.href = url;
            }, 800);
        }
    </script>
</body>
</html>