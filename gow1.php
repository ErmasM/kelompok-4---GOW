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
    <title>God of War - The Story Begins</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700;900&family=Lato:wght@400;700&display=swap" rel="stylesheet">
    
    <style>
        /* --- 1. RESET & VARS --- */
        * { margin: 0; padding: 0; box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
        
        :root {
            --gold: #cfa35e;
            --red: #b30000;
            --dark-bg: #050505;
            --card-bg: #111;
            
            /* Stat Colors */
            --stat-dmg: #ff3b3b;
            --stat-spd: #ffd700;
            --stat-rng: #00bfff;
            --stat-cc: #9932cc;
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Cinzel', serif;
            background-color: var(--dark-bg);
            color: #ccc;
            overflow-x: hidden;
        }

        /* --- 2. NAVBAR --- */
        .navbar {
            display: flex; justify-content: space-between; align-items: center;
            padding: 20px 50px; position: fixed; width: 100%; top: 0; z-index: 1000;
            background: linear-gradient(to bottom, rgba(0,0,0,0.95), rgba(0,0,0,0.6));
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .nav-logo img { height: 40px; }
        .nav-links a {
            text-decoration: none; color: #ccc; margin-left: 20px; font-size: 12px; letter-spacing: 2px;
            transition: .3s;
        }
        .nav-links a:hover { color: var(--gold); }
        .nav-logout { color: var(--red) !important; font-weight: bold; }

        /* --- 3. HERO SECTION (PARALLAX EFFECT ADDED) --- */
        .hero {
            height: 100vh; width: 100%;
            background: linear-gradient(to right, rgba(0,0,0,0.4), rgba(0,0,0,0.1)), url('asset/GOW.jpg'); 
            background-size: cover; background-position: center;
            display: flex; flex-direction: column; justify-content: center; align-items: flex-start;
            padding-left: 8%; position: relative; overflow: hidden;
        }
        
        /* Layer Parallax */
        .hero-content {
            position: relative; z-index: 2;
            transition: transform 0.1s ease-out; /* Smooth movement */
        }

        .hero-logo { width: 450px; margin-bottom: 10px; filter: drop-shadow(0 5px 10px black); }
        .hero-subtitle { 
            font-family: 'Cinzel', serif; font-size: 1.8rem; color: white; 
            text-shadow: 0 2px 4px black; margin-bottom: 30px; letter-spacing: 2px;
        }
        .btn-start {
            background: #b30000; color: white; padding: 15px 40px; text-decoration: none;
            font-size: 1.2rem; letter-spacing: 2px; border: 1px solid rgba(255,255,255,0.3);
            box-shadow: 0 0 20px rgba(179, 0, 0, 0.5);
            clip-path: polygon(5% 0, 100% 0, 95% 100%, 0 100%);
            transition: .3s; display: inline-block;
        }
        .btn-start:hover { transform: scale(1.05); background: #ff0000; }

        /* --- 4. THE BEGINNING --- */
        .section-container { padding: 80px 10%; border-bottom: 3px solid var(--gold); position: relative; }
        
        .section-title {
            text-align: center; font-size: 2.5rem; color: #fff; letter-spacing: 5px;
            margin-bottom: 60px; text-transform: uppercase; border-bottom: 1px solid var(--red);
            display: inline-block; padding-bottom: 5px; position: relative; left: 50%; transform: translateX(-50%);
        }

        .begin-content { display: flex; gap: 50px; align-items: flex-start; }
        
        .throne-img-box {
            flex: 0 0 400px; position: relative; border: 2px solid #222; overflow: hidden;
        }
        .throne-img { width: 100%; display: block; filter: contrast(1.1); transition: 0.5s; }
        .throne-img-box:hover .throne-img { transform: scale(1.05); } /* Zoom dikit pas hover */
        
        .admin-text {
            position: absolute; bottom: 20px; width: 100%; text-align: center;
            font-family: sans-serif; font-weight: 900; font-size: 3.5rem; color: white;
            text-shadow: 3px 3px 0 #000; letter-spacing: -2px; pointer-events: none;
        }

        .story-text {
            font-family: 'Lato', sans-serif; font-size: 1rem; line-height: 1.8; 
            text-align: justify; color: #ddd;
        }

        /* --- 5. TIMELINE --- */
        .timeline-wrapper { position: relative; max-width: 1000px; margin: 0 auto; }
        
        .timeline-line {
            position: absolute; width: 3px; background: var(--red);
            top: 0; bottom: 0; left: 50%; transform: translateX(-50%);
            box-shadow: 0 0 5px var(--red);
        }

        .timeline-row {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 80px; position: relative; width: 100%;
        }

        .omega-node {
            position: absolute; left: 50%; transform: translateX(-50%);
            font-size: 2rem; color: var(--red); background: var(--dark-bg);
            padding: 5px; font-weight: bold; z-index: 2;
            text-shadow: 0 0 10px var(--red); transition: 0.3s;
        }
        .timeline-row:hover .omega-node { transform: translateX(-50%) scale(1.2); text-shadow: 0 0 20px red; }

        .tl-card {
            width: 42%; background: #fff; padding: 8px; border: 2px solid var(--gold);
            box-shadow: 0 0 20px rgba(0,0,0,0.5); position: relative; transition: 0.3s;
        }
        .tl-card:hover { transform: translateY(-5px); border-color: var(--red); }

        .tl-img { width: 100%; height: 180px; object-fit: cover; border: 1px solid #aaa; margin-bottom: 10px; }
        .tl-title { font-size: 1rem; font-weight: bold; color: #000; text-transform: uppercase; margin-bottom: 5px; }
        .tl-desc { font-family: 'Lato'; font-size: 0.8rem; color: #333; line-height: 1.4; }

        .timeline-row.left .tl-card { margin-right: auto; }
        .timeline-row.right .tl-card { margin-left: auto; }

        /* --- 6. CHARACTER (CINEMATIC SPOTLIGHT EFFECT) --- */
        .char-section {
            background: url('https://w0.peakpx.com/wallpaper/397/23/HD-wallpaper-god-of-war-cloud-dark-game-video-game.jpg');
            background-size: cover; background-blend-mode: multiply; background-color: #222;
        }
        
        .char-scroll-container {
            display: flex; overflow-x: auto; gap: 30px; padding: 20px 50px;
            scroll-behavior: smooth; scrollbar-width: none; -ms-overflow-style: none;
        }
        .char-scroll-container::-webkit-scrollbar { display: none; }

        .char-card {
            flex: 0 0 250px; background: rgba(20, 25, 40, 0.8); border: 1px solid #445;
            display: flex; flex-direction: column; align-items: center;
            transition: all 0.4s ease; /* Smooth transition */
            filter: brightness(1); /* Default brightness */
        }
        
        /* LOGIC SPOTLIGHT: Saat container di-hover, semua kartu jadi gelap */
        .char-scroll-container:hover .char-card {
            filter: brightness(0.4) blur(1px); /* Gelapin & blur dikit */
            transform: scale(0.95);
        }
        
        /* KECUALI kartu yang sedang ditunjuk mouse */
        .char-scroll-container .char-card:hover {
            filter: brightness(1.2) blur(0); /* Terangin */
            transform: scale(1.1) translateY(-10px); /* Zoom in */
            z-index: 10; border-color: var(--gold);
            box-shadow: 0 0 30px rgba(207, 163, 94, 0.4);
        }
        
        .char-img { width: 100%; height: 280px; object-fit: cover; }
        .char-info { padding: 15px; text-align: center; width: 100%; border-top: 1px solid #445; }
        .char-name { font-size: 1rem; color: #fff; text-transform: uppercase; margin-bottom: 5px; }
        .char-role { font-family: 'Lato'; font-size: 0.8rem; color: #aaa; text-transform: uppercase; letter-spacing: 1px; }

        /* --- 7. REALMS MAP --- */
        .map-section { background: #15100d; text-align: center; }
        .map-frame {
            position: relative; width: 90%; max-width: 1000px; margin: 0 auto;
            box-shadow: 0 0 50px rgba(0,0,0,0.8); transition: 0.5s;
        }
        .map-frame:hover { transform: perspective(1000px) rotateX(2deg); } /* Efek miring dikit pas hover */

        .map-bg { width: 100%; display: block; border-radius: 5px; }
        
        .map-pointer {
            position: absolute; width: 40px; height: 40px;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white"><path d="M9 11.24V7.5C9 6.12 10.12 5 11.5 5S14 6.12 14 7.5v3.74c1.21-.81 2-2.18 2-3.74C16 5.01 13.99 3 11.5 3S7 5.01 7 7.5c0 1.56.79 2.93 2 3.74zm9.84 4.63l-2.54-.78c-.61-.19-1.28.15-1.48.76l-.6 1.94c-.16.52-.73.8-1.25.64-.52-.16-.8-.74-.64-1.25l.48-1.58c-1.35.66-2.91.86-4.29.35l-2.07-.76c-.52-.19-.79-.77-.6-1.29.19-.52.77-.79 1.29-.6l2.07.76c.72.27 1.52.19 2.19-.19l-1.09-3.52c-.19-.61.15-1.28.76-1.48.61-.19 1.28.15 1.48.76l1.24 4.02c.81-.36 1.63-.49 2.44-.31l2.54.78c.52.16.82.72.66 1.24-.16.52-.72.82-1.24.66z"/></svg>'); 
            background-repeat: no-repeat; background-size: contain;
            filter: drop-shadow(2px 2px 2px black);
            cursor: pointer; animation: floatHand 1.5s infinite alternate; transition: 0.3s;
        }
        .map-pointer:hover { filter: drop-shadow(0 0 5px gold); transform: scale(1.2) !important; }

        .pt-1 { top: 18%; left: 48%; transform: rotate(-10deg); } 
        .pt-2 { top: 35%; left: 20%; transform: rotate(-20deg); } 
        .pt-3 { top: 68%; left: 25%; transform: rotate(-10deg); } 
        .pt-4 { top: 48%; left: 53%; transform: rotate(-15deg); } 
        .pt-5 { top: 36%; left: 73%; transform: rotate(-10deg); } 

        @keyframes floatHand { from { transform: translateY(0) rotate(-10deg); } to { transform: translateY(-10px) rotate(-10deg); } }

        /* --- 8. ARSENAL (SLASH EFFECT ADDED) --- */
        .arsenal-section { background: #000; text-align: center; }
        .arsenal-grid {
            display: flex; flex-wrap: wrap; justify-content: center; gap: 40px; margin-top: 50px;
        }
        
        .weapon-card {
            flex: 0 0 350px; height: 500px;
            background: rgba(30, 35, 45, 0.6); border: 1px solid #445;
            padding: 20px; position: relative; cursor: pointer; transition: .3s;
            overflow: hidden;
        }
        
        /* SLASH ANIMATION ELEMENT */
        .slash-overlay {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(120deg, transparent, rgba(255, 255, 255, 0.8), transparent);
            transform: translateX(-100%); opacity: 0; pointer-events: none; z-index: 10;
        }
        
        /* Animasi Slash aktif saat class 'slashing' ditambahkan via JS */
        .weapon-card.slashing .slash-overlay {
            animation: slashAnim 0.4s ease-out;
        }
        @keyframes slashAnim {
            0% { transform: translateX(-100%) skewX(-20deg); opacity: 0; }
            50% { opacity: 1; }
            100% { transform: translateX(100%) skewX(-20deg); opacity: 0; }
        }

        .weapon-card:hover { border-color: var(--gold); background: rgba(30, 35, 45, 0.9); }

        .wp-img-box { height: 200px; display: flex; align-items: center; justify-content: center; transition: .4s; }
        .wp-img { width: 80%; filter: drop-shadow(0 10px 10px #000); transform: rotate(-45deg); transition: .4s; }
        
        .wp-title { color: var(--gold); font-size: 1.2rem; margin: 20px 0; text-transform: uppercase; }
        
        .wp-desc { 
            font-family: 'Lato'; font-size: 0.85rem; color: #ccc; line-height: 1.6;
            transition: .4s; opacity: 1; transform: translateY(0);
        }

        .wp-stats {
            position: absolute; bottom: 0; left: 0; width: 100%; padding: 20px;
            background: rgba(20,20,20,0.95);
            transform: translateY(100%); transition: .4s; opacity: 0;
            display: flex; flex-direction: column; gap: 10px;
        }

        .weapon-card.active .wp-desc { opacity: 0; transform: translateY(-20px); }
        .weapon-card.active .wp-stats { transform: translateY(0); opacity: 1; bottom: 20px; }
        .weapon-card.active .wp-img-box { transform: scale(0.8) translateY(-20px); }

        .stat-row { display: flex; flex-direction: column; align-items: flex-start; gap: 5px; }
        .stat-label { font-size: 0.7rem; color: #aaa; font-family: 'Lato'; width: 100%; display: flex; justify-content: space-between; }
        .bar-bg { width: 100%; height: 8px; background: #333; border-radius: 4px; overflow: hidden; }
        .bar-fill { height: 100%; width: 0; transition: 1s ease-out; }

        .fill-dmg { background: var(--stat-dmg); }
        .fill-spd { background: var(--stat-spd); }
        .fill-rng { background: var(--stat-rng); }
        .fill-cc { background: var(--stat-cc); }

        .weapon-card:nth-child(3) { margin-top: 20px; }

        /* --- 9. FOOTER --- */
        .footer {
            padding: 80px 0; text-align: center; background: #000; border-top: 2px solid var(--gold);
        }
        .footer-text { font-family: 'Lato'; font-style: italic; color: #fff; margin-bottom: 30px; letter-spacing: 1px; }
        .btn-back {
            background: var(--red); color: white; padding: 10px 30px; text-decoration: none;
            font-family: 'Cinzel'; border: 1px solid #ff5555; transition: .3s;
        }
        .btn-back:hover { background: #800000; }
        .copyright { margin-top: 50px; font-size: 10px; color: #555; }

        @media (max-width: 768px) {
            .begin-content { flex-direction: column; }
            .throne-img-box { flex: 0 0 auto; width: 100%; }
            .timeline-row { flex-direction: column; align-items: flex-start; padding-left: 30px; }
            .timeline-line { left: 10px; }
            .omega-node { left: 10px; }
            .tl-card { width: 100%; margin-bottom: 20px; }
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="nav-logo"><img src="asset/logo.png" alt="GOW"></div>
        <div class="nav-links">
            <a href="home.php">HOME</a>
            <a href="series.php">SERIES</a>
            <a href="about.php">ABOUT</a>
            <a href="series.php" class="nav-logout">LOGOUT</a>
        </div>
    </nav>

    <section class="hero" id="hero-section">
        <div class="hero-content">
            <img src="asset/logo.png" class="hero-logo">
            <h2 class="hero-subtitle">GOD OF WAR : STORY BEGINS</h2>
            <a href="#beginning" class="btn-start">START STORY</a>
        </div>
    </section>

    <section id="beginning" class="section-container">
        <h2 class="section-title">THE BEGINNING</h2>
        
        <div class="begin-content">
            <div class="throne-img-box">
                <img src="https://i.pinimg.com/736x/8f/94/28/8f94286f91f1624b4344474776840748.jpg" class="throne-img">
                <div class="admin-text">AKU ADMIN</div>
            </div>
            
            <div class="story-text">
                <p>
                    <span style="font-size:3rem; float:left; line-height:0.8; margin-right:5px; color:var(--gold);">K</span>RATOS ADALAH PRAJURIT SPARTA YANG MENJADI PELAYAN ARES SETELAH MEMINTA KEKUATAN UNTUK MENGALAHKAN MUSUH-MUSUHNYA, TETAPI TRAGEDI TERJADI KETIKA IA TANPA SENGAJA MEMBUNUH ISTRI DAN PUTRINYA DALAM SERANGAN YANG DIPICU ARES, MEMBUATNYA DIKUTUK SEBAGAI "HANTU SPARTA".
                </p>
                <br>
                <p>
                    SELAMA SEPULUH TAHUN IA MELAYANI PARA DEWA HINGGA ATHENA MENJANJIKAN PENGAMPUNAN JIKA IA MEMBUNUH ARES, DAN UNTUK ITU KRATOS HARUS MENCARI KOTAK PANDORA YANG TERSEMBUNYI DI KUIL DI PUNGGUNG TITAN CRONOS. SETELAH MENEMUKAN KOTAK TERSEBUT, KRATOS DIBUNUH ARES TETAPI BERHASIL BANGKIT DARI DUNIA BAWAH, MEREBUT KEMBALI KOTAK PANDORA, DAN MENGGUNAKAN KEKUATANNYA UNTUK BERTARUNG SERTA MEMBUNUH ARES DENGAN BLADE OF THE GODS. MESKI PARA DEWA MENGAMPUNI DOSANYA, MEREKA TIDAK DAPAT MENGHAPUS MIMPI BURUKNYA, NAMUN ATHENA KEMUDIAN MENGANGKATNYA MENJADI DEWA PERANG YANG BARU.
                </p>
            </div>
        </div>
    </section>

    <section class="section-container" style="background: #0a0a0a; border-bottom: 3px solid var(--gold);">
        <h2 class="section-title" style="color:var(--gold);">JOURNEY TIMELINE<br><span style="font-size:12px; letter-spacing:2px; color:#666; border:none;">THE PATH OF ATHENS</span></h2>
        
        <div class="timeline-wrapper">
            <div class="timeline-line"></div>

            <div class="timeline-row left">
                <div class="omega-node">Ω</div>
                <div class="tl-card">
                    <img src="https://static.wikia.nocookie.net/godofwar/images/6/63/Aegean_Sea_GOW.jpg" class="tl-img">
                    <div class="tl-title">AEGEAN SEA</div>
                    <div class="tl-desc">Kratos membantu Athena mempertahankan kotanya dari serangan Ares dan menembus pasukan monster untuk mencapai Oracle yang mengetahui cara mengalahkan Ares.</div>
                </div>
            </div>

            <div class="timeline-row right">
                <div class="omega-node">Ω</div>
                <div class="tl-card">
                    <img src="https://static.wikia.nocookie.net/godofwar/images/e/e9/Athens_GOWI.jpg" class="tl-img">
                    <div class="tl-title">TEMPLE OF THE ORACLES</div>
                    <div class="tl-desc">Kratos membantu Athena mempertahankan kotanya dari serangan Ares dan menembus pasukan monster untuk mencapai Oracle yang mengetahui cara mengalahkan Ares.</div>
                </div>
            </div>

            <div class="timeline-row left">
                <div class="omega-node">Ω</div>
                <div class="tl-card">
                    <img src="https://static.wikia.nocookie.net/godofwar/images/c/c9/Desert_of_Lost_Souls.jpg" class="tl-img">
                    <div class="tl-title">DESERT OF LOST SOULS</div>
                    <div class="tl-desc">Kratos melintasi gurun, memanjat Cronos, dan menaklukkan tiga tantangan di Kuil Pandora untuk mendapatkan Kotak Pandora sebagai kekuatan melawan Ares.</div>
                </div>
            </div>

            <div class="timeline-row right">
                <div class="omega-node">Ω</div>
                <div class="tl-card">
                    <img src="https://static.wikia.nocookie.net/godofwar/images/5/52/Pandora%27s_Temple.jpg" class="tl-img">
                    <div class="tl-title">MOUNT OLYMPUS</div>
                    <div class="tl-desc">Kratos kembali ke Athena, membuka Kotak Pandora, lalu mengalahkan Ares dalam pertarungan terakhir dan akhirnya diangkat menjadi Dewa Perang yang baru.</div>
                </div>
            </div>
        </div>
    </section>

    <section class="char-section">
        <div style="text-align:center; padding-top:40px;">
            <h2 class="section-title">CHARACTER</h2>
        </div>
        
        <div class="char-scroll-container">
            <div class="char-card">
                <img src="https://static.wikia.nocookie.net/godofwar/images/8/87/Kratos_GoW1.png" class="char-img">
                <div class="char-info"><div class="char-name">KRATOS</div><div class="char-role">PROTAGONIST</div></div>
            </div>
            <div class="char-card">
                <img src="https://static.wikia.nocookie.net/godofwar/images/3/3c/Ares_GoW1.png" class="char-img">
                <div class="char-info"><div class="char-name">ARES</div><div class="char-role">ANTAGONIST</div></div>
            </div>
            <div class="char-card">
                <img src="https://static.wikia.nocookie.net/godofwar/images/d/d8/Athena_GoW2.png" class="char-img">
                <div class="char-info"><div class="char-name">ATHENA</div><div class="char-role">MENTOR</div></div>
            </div>
            <div class="char-card">
                <img src="https://static.wikia.nocookie.net/godofwar/images/5/5c/Medusa_GoW1.png" class="char-img">
                <div class="char-info"><div class="char-name">ARTEMIS</div><div class="char-role">DEITY ALLY</div></div>
            </div>
            <div class="char-card">
                <img src="https://static.wikia.nocookie.net/godofwar/images/6/67/Zeus_GoW2.png" class="char-img">
                <div class="char-info"><div class="char-name">ZEUS</div><div class="char-role">KING OF GODS</div></div>
            </div>
        </div>
    </section>

    <section class="map-section section-container">
        <h2 class="section-title" style="color:white; text-shadow:none; border:none;">REALMS MAP</h2>
        <div class="map-frame">
            <img src="https://i.pinimg.com/originals/18/88/2c/18882c974957e84d4719541a0210e729.jpg" class="map-bg">
            <div class="map-pointer pt-1" title="Olympus"></div>
            <div class="map-pointer pt-2" title="Athens"></div>
            <div class="map-pointer pt-3" title="Aegean Sea"></div>
            <div class="map-pointer pt-4" title="Desert of Lost Souls"></div>
            <div class="map-pointer pt-5" title="Pandora's Temple"></div>
        </div>
    </section>

    <section class="arsenal-section section-container">
        <p style="color:var(--red); letter-spacing:2px; font-family:'Cinzel';">CHOOSE YOUR TOOL</p>
        <h2 class="section-title" style="font-size:3rem; border:none; margin-bottom:20px;">ARSENAL OF <span style="color:var(--gold);">SPARTA</span></h2>
        
        <div class="arsenal-grid">
            
            <div class="weapon-card" onclick="triggerWeapon(this)">
                <div class="slash-overlay"></div> <div class="wp-img-box"><img src="https://static.wikia.nocookie.net/godofwar/images/8/82/Blades_of_Chaos_GoW.png" class="wp-img"></div>
                <h3 class="wp-title">BLADES OF CHAOS</h3>
                <p class="wp-desc">BLADE OF CHAOS DITEMPA DI KEDALAMAN HADES DAN DIIKAT SECARA PERMANEN PADA TUBUH KRATOS.</p>
                <div class="wp-stats">
                    <div class="stat-row"><div class="stat-label"><span>DAMAGE</span><span>8/10</span></div><div class="bar-bg"><div class="bar-fill fill-dmg" style="width:80%"></div></div></div>
                    <div class="stat-row"><div class="stat-label"><span>SPEED</span><span>10/10</span></div><div class="bar-bg"><div class="bar-fill fill-spd" style="width:100%"></div></div></div>
                    <div class="stat-row"><div class="stat-label"><span>RANGE</span><span>9/10</span></div><div class="bar-bg"><div class="bar-fill fill-rng" style="width:90%"></div></div></div>
                    <div class="stat-row"><div class="stat-label"><span>CROWD CONTROL</span><span>10/10</span></div><div class="bar-bg"><div class="bar-fill fill-cc" style="width:100%"></div></div></div>
                </div>
            </div>

            <div class="weapon-card" onclick="triggerWeapon(this)">
                <div class="slash-overlay"></div>
                <div class="wp-img-box"><img src="https://static.wikia.nocookie.net/godofwar/images/9/94/Blade_of_Artemis.png" class="wp-img"></div>
                <h3 class="wp-title">BLADE OF ARTEMIS</h3>
                <p class="wp-desc">AKU MEMBERIMU PEDANG YANG KUPAKAI UNTUK MEMBUNUH SEORANG TITAN. AMBILLAH HADIAH INI.</p>
                <div class="wp-stats">
                    <div class="stat-row"><div class="stat-label"><span>DAMAGE</span><span>9/10</span></div><div class="bar-bg"><div class="bar-fill fill-dmg" style="width:90%"></div></div></div>
                    <div class="stat-row"><div class="stat-label"><span>SPEED</span><span>5/10</span></div><div class="bar-bg"><div class="bar-fill fill-spd" style="width:50%"></div></div></div>
                    <div class="stat-row"><div class="stat-label"><span>RANGE</span><span>6/10</span></div><div class="bar-bg"><div class="bar-fill fill-rng" style="width:60%"></div></div></div>
                    <div class="stat-row"><div class="stat-label"><span>CROWD CONTROL</span><span>5/10</span></div><div class="bar-bg"><div class="bar-fill fill-cc" style="width:50%"></div></div></div>
                </div>
            </div>

            <div class="weapon-card" onclick="triggerWeapon(this)">
                <div class="slash-overlay"></div>
                <div class="wp-img-box"><img src="https://static.wikia.nocookie.net/godofwar/images/e/e0/Blade_of_the_Gods.png" class="wp-img"></div>
                <h3 class="wp-title">BLADE OF GODS</h3>
                <p class="wp-desc">INGATLAH, KRATOS! PEDANG TEMPAT KAU BERPIJAK INILAH YANG MEMBERIMU KEMENANGAN ATAS ARES.</p>
                <div class="wp-stats">
                    <div class="stat-row"><div class="stat-label"><span>DAMAGE</span><span>10/10</span></div><div class="bar-bg"><div class="bar-fill fill-dmg" style="width:100%"></div></div></div>
                    <div class="stat-row"><div class="stat-label"><span>SPEED</span><span>9/10</span></div><div class="bar-bg"><div class="bar-fill fill-spd" style="width:90%"></div></div></div>
                    <div class="stat-row"><div class="stat-label"><span>RANGE</span><span>9/10</span></div><div class="bar-bg"><div class="bar-fill fill-rng" style="width:90%"></div></div></div>
                    <div class="stat-row"><div class="stat-label"><span>CROWD CONTROL</span><span>8/10</span></div><div class="bar-bg"><div class="bar-fill fill-cc" style="width:80%"></div></div></div>
                </div>
            </div>

        </div>
    </section>

    <footer class="footer">
        <p class="footer-text">"THE JOURNEY DOES NOT END HERE..."</p>
        <a href="series.php" class="btn-back">BACK TO SERIES</a>
        <p class="copyright">God of War © Sony Interactive Entertainment</p>
    </footer>

    <script>
        // --- 1. PARALLAX HERO ---
        const heroSection = document.getElementById('hero-section');
        const heroContent = document.querySelector('.hero-content');
        
        heroSection.addEventListener('mousemove', (e) => {
            const x = (window.innerWidth - e.pageX * 2) / 100;
            const y = (window.innerHeight - e.pageY * 2) / 100;
            
            heroContent.style.transform = `translate(${x}px, ${y}px)`;
            heroSection.style.backgroundPosition = `calc(50% + ${x/2}px) calc(50% + ${y/2}px)`;
        });

        // --- 2. WEAPON CLICK & SLASH EFFECT ---
        function triggerWeapon(card) {
            // Toggle Stats
            card.classList.toggle('active');
            
            // Add Slash Animation Class
            card.classList.add('slashing');
            
            // Remove Slash Class after animation ends to allow re-trigger
            setTimeout(() => {
                card.classList.remove('slashing');
            }, 400); // 400ms match CSS animation time
        }
    </script>
</body>
</html>